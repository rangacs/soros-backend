<?php

class ExportController extends Controller {

    public $startTime;
    public $endTime;

    public function init() {

        $startTime = $_REQUEST['startDate'];
        $endTime = ($_REQUEST['endDate'] );
        $len = strlen($startTime);
        $elen = strlen($endTime)   ;     
        if ($len != 0) {
            $this->startTime = $_REQUEST['startDate'];
        } else {

            $this->startTime = date("Y-m-d H:i:s", (time() - 8 * 360));
        }

        if ($elen != 0) {

            $this->endTime = $_REQUEST['endDate'];
        } else {


            $this->endTime = date("Y-m-d H:i:s", (time()));
        }
    }

    public function actionIndex($id) {
        //$this->render('index');
        $this->exportTagData($id);
    }

    public function actionHourlyAverageTable() {

        $defaultHour = isset($_SESSION["default_avg_range"]) ? $_SESSION["default_avg_range"] : 1;
        $data = $this->getAverageTable(24, "AN", $defaultHour, $this->startTime, $this->endTime);

        $this->exportXlsData($data, "Hourly Analysis", $elements);
    }

    public function actionAnalysis() {

        $elemArray = explode(";", "Moisture;Ash;Sulfur;BTU;TPH;totalTons;");
        $dispElem = "";
        $cntElem = 0;

        foreach ($elemArray as $elem) {
            if ($elem) {
                //AS03222015
                if ($elem == "KH")
                    $elem = "IF( (CaO <= 0), 0 , ROUND(((CaO - 1.65 * Al2O3 - 0.35 * Fe2O3) / (2.80 * sio2)),2) ) as KH";
                else if ($elem == "LSF")
                    $elem = "IF( (CaO <= 0), 0 , ROUND((CaO/(2.8*SiO2+1.18*Al2O3+0.65*Fe2O3) * 100),2) ) as LSF";
                else if ($elem == "C3S")
                    $elem = "IF( (CaO <= 0), 0 , ROUND((4.07*CaO - 7.6*SiO2 - 6.72*Al2O3 - 1.43*Fe2O3 - 2.852*SO3 ),2) ) as C3S";
                else if ($elem == "C3A")
                    $elem = "IF( (Al2O3<= 0), 0 , ROUND((Al2O3 * 2.65 - 1.69 * Fe2O3),2) ) as C3A";
                else if ($elem == "NAEQ")
                    $elem = "IF( (K2O <= 0), 0 , ROUND((K2O * 0.658 + Na2O ),2) ) as NAEQ";
                else if ($elem == "SM")
                    $elem = "IF( (SiO2 <= 0), 0 , ROUND((SiO2/(Al2O3+Fe2O3)),2) ) as SM";
                else if ($elem == "IM")
                    $elem = "IF( (Al2O3 <= 0), 0 , ROUND((Al2O3/Fe2O3),2) ) as IM";
                else if ($elem == "R_TPH" && ($detector_source == "rm_source_feeder_inputs")) {
                    continue;
                } else
                    $elem = "`$elem`";

                $dispElem .= ",$elem";
                $cntElem++;
            }
        }

        $query = "SELECT `LocalendTime` $dispElem FROM `analysis_A1_A2_Blend` WHERE LocalendTime BETWEEN( '" . $this->startTime . "') AND ('" . $this->endTime . "') ORDER BY LocalendTime ASC";

        $rawData = Yii::app()->db->createCommand($query)->queryAll();

        $setting_filter_bad_records = RmSettings::getValueFromKey("ANALYZER_FILTER_BAD_RECORDS", false);
        if ($setting_filter_bad_records)
            $rs = DashHelper::filterAnalysisData($rawData);
        else
            $rs = $rawData;

        $data = array_reverse($rs);
        array_unshift($elemArray, "Localend Time");
        array_unshift($data, $elemArray);
        $this->exportXlsData($data, "Analysis Results", $elemArray);
    }

    public function exportTagData($id) {

        $elements = HeliosUtility::getDisplayElements();


        $type = $_REQUEST['type'];
        $tagObject = $this->getTagObject($id, $type);


        $records = $tagObject->getRecords();
        $tagData = array_reverse($records);

        $header = $this->getHeader($elements);
        $bodyData = $this->getBody($tagData, $elements);


        array_unshift($bodyData, $header);

        $this->exportXlsData($bodyData, $tagObject->tagName, $elements);
    }

    public static function getAverageTable($defaultPrevHourLimit, $dataSrc, $defAvgHours = 1, $startTime = '', $endTime = '') {

        $dateTimeFormat = RmSettings::getValueFromKey('GLOBAL_TIME_FORMAT', 'Y-m-d H:i:s');
        $defaultHour = $defAvgHours * 3600;

        if (strtotime($startTime) > 0) {

            $startTime = date('Y-m-d H:i:s', strtotime($startTime));
            $endTime = date('Y-m-d H:i:s', strtotime($endTime));
        } else {
            $currentTimeTick = time();

            $tmpDatw = date('Y-m-d H', time());
            $HourTime = strtotime($tmpDatw . ":00:00");


            if ($currentTimeTick > $HourTime) {

                $currentTimeTick = $HourTime + ( $defaultHour);
            }

            $endTime = date('Y-m-d H:i:s', $currentTimeTick);
            $startTime = date('Y-m-d H:i:s', $currentTimeTick - ($defaultPrevHourLimit * $defaultHour));
        }//else


        if ($dataSrc == "AN") {
            $default = 1;
        } else if ($dataSrc == "FDR") {
            $default = 2;
        }

        $uid = Yii::app()->user->id;
        $def_layout = Layouts::model()->find(array('condition' => 'user_id=:x AND default_layout=:y', 'params' => array(':x' => $uid, ':y' => $default)));

        $SystemMessagesWidget = GadgetsData::model()->find(array('condition' => 'lay_id=:x AND gadget_type=:y', 'params' => array(':x' => $def_layout->lay_id, ':y' => 'System_Messages')));

        $elementString = $SystemMessagesWidget->data_source;

        $elements = explode(';', $elementString);
        array_unshift($elements, 'LocalendTime');
//        $elements[0] = 'LocalendTime';
        array_pop($elements); //remove last empty col

        $displayElements = $elements;
        $querysr = "SELECT src_id,src_name FROM rm_source WHERE product_id=1";

        $tcommandt = Yii::app()->db->createCommand($querysr);
        $tresultt = $tcommandt->query()->readAll();

        if (count($tresultt) > 0) {
            foreach ($tresultt as $roAr) {
                $srcArray[$roAr["src_id"]] = $roAr["src_name"];
            }
        }


        $srcNameModAr = array("LimeStone" => "Lime", "LimeStone1" => "Lime1");

        $tableHeadTagHtml = array();

        foreach ($displayElements as $dele) {
//			if(substr($dele, -3, 3) == "_sp") continue;
            if ($dele == 'AM')
                $dele = "IM";

            if ($dele == "BTU")
                $dele = "GCV";

            if (substr($dele, 0, 4) == "src_") {
                $tsrcId = substr($dele, 4, 1);
                $dele = (isset($srcArray[$tsrcId])) ? $srcArray[$tsrcId] : $dele;

                if (isset($srcNameModAr[$dele]))
                    $dele = $srcNameModAr[$dele];
            }

            if ($dele == 'LocalendTime') {
                $tableHeadTagHtml[] = Yii::t('app', 'LocalendTime');
            } else {
                $tableHeadTagHtml [] = $dele;
            }
        }//foreach

        $tableBodyTagHtml = array();

        $i = (int) strtotime($endTime);

        $stopTime = (int) strtotime($startTime);

        $ri = 0;
        $dispAr = array();


        while ($i > $stopTime) {
            $rowTr = "";
            $sTime = date('Y-m-d H:i:s', $i - $defaultHour);
            $eTime = date('Y-m-d H:i:s', $i);

            $dashHelper = new DashHelper();
            $avgArray = $dashHelper::getaIntervalAvg($sTime, $eTime, $dataSrc, $elements);
            $avgArray['LocalendTime'] = $eTime;
            if (1):

                $rowTr = array();
                foreach ($elements as $ele) {
                    if (substr($ele, -3, 3) == "_sp")
                        continue;

                    if ($ele == "LocalendTime")
                        $rowTr [] = date($dateTimeFormat, strtotime($avgArray[$ele]));
                    else
                        $rowTr [] = round($avgArray[$ele], 2);
                }

                $ri++;
            endif;

            $i -= $defaultHour;

            array_push($dispAr, $rowTr);
        }

        array_unshift($dispAr, $tableHeadTagHtml);
        return $dispAr;
    }

    public function exportXlsData($data, $sheetName, $columns) {



        // Turn off our amazing library autoload 
        spl_autoload_unregister(array('YiiBase', 'autoload'));


        Yii::import('ext.PHPExcel.Classes.PHPExcel', true);


        //$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        //$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);


        $objPHPExcel = new PHPExcel(); // Create new PHPExcel object
        $objPHPExcel->getProperties()->setCreator("Sigit prasetya n")
                ->setLastModifiedBy("Sigit prasetya n")
                ->setTitle("Creating file excel with php Test Document")
                ->setSubject("Creating file excel with php Test Document")
                ->setDescription("How to Create Excel file from PHP with PHPExcel 1.8.0 Classes by seegatesite.com.")
                ->setKeywords("phpexcel")
                ->setCategory("Test result file");
// create style
        $default_border = array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '1006A3')
        );
        $style_header = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'E1E0F7'),
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $style_content = array(
            'borders' => array(
                'bottom' => $default_border,
                'left' => $default_border,
                'top' => $default_border,
                'right' => $default_border,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'eeeeee'),
            ),
            'font' => array(
                'size' => 12,
            )
        );
// Create Header

        $index = count(($columns));
        $colString = PHPExcel_Cell::stringFromColumnIndex($index);
//        echo $colString;
//        die();
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $colString . "1")->applyFromArray($style_header); // give style to header
        // Create Data


        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->fromArray($data, null, "A1");


        //$objPHPExcel->getActiveSheet()->getStyle($firststyle . ':' . $laststyle)->applyFromArray($style_content); // give style to header
// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Result');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $sheetName . '.xls"'); // file name of excel
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');


        // Once we have finished using the library, give back the 
        // power to Yii... 
        spl_autoload_register(array('YiiBase', 'autoload'));
    }

    public function getTagObject($id, $tagType) {



        if ($tagType == 'completed') {

            $tagObject = TagCompleted::model()->find('tagID  = :tagID', array(':tagID' => $id));
        } else {

            $tagObject = TagQueued::model()->find('tagID  = :tagID', array(':tagID' => $id));
        }


        return $tagObject;
    }

    public static function getHeader($elements) {

        $header = array();

        $header [] = "End-Time";
        foreach ($elements as $ele) {


            $header [] = $ele;
        }

        return $header;
    }

    public static function getBody($data, $elements) {

        $dataTimeFormat = RmSettings::getValueFromKey('GLOBAL_TIME_FORMAT', 'Y-m-d H:i:s');

        $result = array();
        foreach ($data as $key => $row) {

            $date = date($dataTimeFormat, strtotime($row["LocalendTime"]));
            $result[$key][] = $date;
            foreach ($elements as $ele) {


                $tAl2O3 = (float) $row['Al2O3'];

                $tSiO2 = (float) $row['SiO2'];

                $tFe2O3 = (float) $row['Fe2O3'];

                $tCaO = (float) $row['CaO'];


                //Calculate formulas
                if ($ele == 'KH') {
                    if ($tSiO2 > 0) {
                        $formulaVal = round((($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2)), 2);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                } elseif ($ele == "SM") {
                    if (($tAl2O3 + $tFe2O3) > 0) {
                        $formulaVal = round((($tSiO2) / ($tAl2O3 + $tFe2O3)), 2);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                } elseif ($ele == "AM") {
                    if ($tFe2O3 > 0) {
                        $formulaVal = round(($tAl2O3 / $tFe2O3), 2);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                } elseif ($ele == "IM") {
                    if ($tFe2O3 > 0) {
                        $formulaVal = round(($tAl2O3 / $tFe2O3), 2);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                }

                $result[$key][] = $row[$ele];
            }
        }

        return $result;
    }

}
