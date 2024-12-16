<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TagHelper
 *
 * @author veda
 */
class TagHelper {
    //put your code here

    /**
     * 
     * @param TagCompleted  $tagObject
     * @param array $elements
     */
    public static function renderTagtable($tagObject, $elements) {

        $dateTimeFormat = RmSettings::getValueFromKey('GLOBAL_TIME_FORMAT', "Y-m-d H:i:s");
        $tagName = $tagObject->tagName;

        $starTime = $tagObject->LocalstartTime;
        $endTime = $tagObject->LocalendTime;

        /*         * * Get Tag Data ** */
        $records = $tagObject->getRecords();
        $data = array_reverse($records);

        $type = $_REQUEST['type'];
        $dumppy = "";
        $exportUrl = Yii::app()->createAbsoluteUrl("Export/index", array('id' => $tagObject->tagID, 'type' => $type));

        $interval = new IntervalObject();

        $interval->setStartTime($tag['LocalstartTime']);
        $interval->setEndTime($tag['LocalendTime']);
        //setStartTime
        //setEndTime
        $analysisDataProvider = new AnalysisDataProvider();
        $analysisDataProvider->setElements($elements);

        $average = $analysisDataProvider->getTagAvg($tagObject);


        echo '  <div class="clearfix">
                <header class="ui-widget-header ui-corner-top">
                <h2> showing data for ' . $dumppy . " (" . date($dateTimeFormat, strtotime($starTime)) . " to " . date($dateTimeFormat, strtotime($endTime)) . ') </h2>
                </header>
                <section class="ui-widget-content ui-corner-bottom">
                <a class="pull-right DTTT_button" href=' . $exportUrl . '>Export</a>
                <div class="clearfix">';

        echo '<table  class="customStyle dataTable no-footer">';

        self::renderHeader($elements);
        //array_unshift($average,$endTime);
        $average['End-Time'] = $endTime;
        self::renderAverageRow($elements, $average);
        echo '</table>';
        echo '<table id="tagview" class="customStyle dataTable no-footer">';

        self::renderHeader($elements);


        self::renderBody($data, $elements);

        echo '</table></div>
                </section>
                </div>';

        self::initDataTable();
    }

    public static function renderHeader($elements) {

        echo '<thead><tr>';
        echo '<th class="ui-state-default">End-Time</th>';

        foreach ($elements as $ele) {

            if ($ele == "BTU")
                $ele = "GCV";
            echo '<th class="ui-state-default">' . $ele . '</th>';
        }

        echo '</tr></thead>';
    }

    public static function renderAverageRow($elements, $average) {


        echo '<tr>';
        echo '<td class="">' . $average['End-Time'] . '</td>';
        $average[$ele];
        foreach ($elements as $ele) {

            if ($ele == "BTU")
                $ele = "GCV";
            echo '<td class="">' . $average[$ele] . '</td>';
        }

        echo '</tr>';
    }

    public static function renderBody($data, $elements) {

        $dataTimeFormat = RmSettings::getValueFromKey('GLOBAL_TIME_FORMAT', 'Y-m-d H:i:s');
        echo "<tbody>";
        foreach ($data as $row) {

            echo '<tr>';
            echo "<td>" . date($dataTimeFormat, strtotime($row["LocalendTime"])) . "</td>";

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

                echo "<td>" . $row[$ele] . "</td>";
            }
            echo '</tr>';
        }
        echo "</tbody>";
    }

    public static function initDataTable() {
        
    }

    public static function DumpTable($array_assoc, $exAr) {

        if (is_array($array_assoc)) {
            echo '<table class="dataTable">';
            echo '<thead>';
            echo '<tr>';

            foreach ($array_assoc as $key => $value):
                if (!in_array($key, $exAr))
                    echo '<th>' . str_replace("w_", "", $key) . '</th>';
            endforeach;
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            foreach ($array_assoc as $key => $value):
                if (!in_array($key, $exAr)) {
                    $editBtn = "";
                    if ($key == "w_chQty"):
                        $editBtn = '<img src="/soros/images/update.png" alt="Update" onclick="editChQat(' . $value . ')">';
                    endif;
                    echo '<td>' . $value . $editBtn . '</td>';
                }
            endforeach;
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
            return;
        }
    }

    public static function getTagAvg($tagObject) {

        $startDate = $tagObject->LocalstartTime;
        $endTime = $tagObject->LocalendTime;


        $elements = HeliosUtility::getDisplayElements();

        $dElements = array();

        foreach ($elements as $ele) {
            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $dElements[$ele] = "round(" . $formula->formula . " , 3) as $ele";
            } else {
                $dElements[$ele] = $ele;
            }
        }
        $dElements['LocalendTime'] = 'LocalendTime';
        $dElements['LocalstartTime'] = 'LocalstartTime';

        $selectColumns = implode(',', $dElements);

        $tagID = $tagObject->tagID;


        $subTags = SubTag::model()->findAll("tagID=:tagid", array(":tagid" => $tagID));
	$subTagcount = Yii::app()->db->createCommand("select count(tagID) from rta_tag_index_sub_tag where tagID = $tagID")->queryScalar();
	$subTagcount = (int) $subTagcount;
        $data = array();
//        $elements = $this->getElements();
//        echo "Found Sub tags" . $tagID;
//echo "debug";
	//var_dump($subTagcount);
	//die();
	if($subTagcount != 0 ){

        foreach ($subTags as $sTag) {

            $ansql = "select $selectColumns from analysis_A1_A2_Blend  where LocalendTime >= '" . $sTag->LocalstartTime . "'  AND LocalendTime <= '" . $sTag->LocalendTime . "' AND totalTons != 0 ORDER BY LocalendTime DESC";
            $average = array();
            $getRecords = Yii::app()->db->createCommand($ansql)->queryAll();
//echo $ansql;
            $data[] = $getRecords;
        }
}else{

            $ansql = "select $selectColumns from analysis_A1_A2_Blend  where LocalendTime >= '" . $startDate . "'  AND LocalendTime <= '" . $endTime . "' AND totalTons != 0 ORDER BY LocalendTime DESC";

            $average = array();
            $getRecords = Yii::app()->db->createCommand($ansql)->queryAll();
//echo $ansql;
            $data[] = $getRecords;


}

	//echo $ansql;
	//die();
        $dataResuts = array();
        foreach ($data as $temp) {
            foreach ($temp as $row) {
                $dataResuts[] = $row;
            }
        }
        $average = array();
        $acQuery = 'select * from data_boundries ';
        $acResults = Yii::app()->db->createCommand($acQuery)->query();
        $acSettings = array();


        foreach ($acResults as $item) {
            $acSettings[$item['element_name']] = $item;
        }

        $query = "select * from rm_settings where varKey = 'ANALYZER_FILTER_BAD_RECORDS' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue']) {
            $setting_filter_bad_records = (int) $result['varValue'];
        }



        foreach ($dataResuts as $rawData) {

            //Filter Here
            if ($setting_filter_bad_records) {
                $row = DashHelper::validateAndSetAnalyzerRecordUsingRange($rawData, $acSettings);
            } else {
                $row = $rawData;
            }

            foreach ($elements as $ele) {


                //Calculate formulas
                if ($ele == 'LocalstartTime') {
                    $row[$ele] = $startDate;
                } elseif ($ele == 'LocalendTime') {

                    $row[$ele] = $endTime;
                }

                if ($row['totalTons'] > 0) {
                    $average[$ele][] = $row[$ele];
                }
            }
        }

        $eleAvg = array();

        if (!empty($average)) {
            foreach ($average as $key => $tmpArray) {
                if ($key == 'totalTons') {
                    $sum = array_sum($tmpArray);
                    $eleAvg[$key] = round($sum, 2);

                    continue;
                }

                if ($key == 'LocalendTime') {
                    $eleAvg[$key] = ($tmpArray[0]);
                    continue;
                }
                if ($key == 'LocalstartTime') {
                    $eleAvg[$key] = array_pop($tmpArray);
                    continue;
                }
                $count = count($tmpArray);
                $sum = array_sum($tmpArray);
                $avg = $sum / $count;
                $eleAvg[$key] = round($avg, 2);
            }
        } else {


            foreach ($elements as $ele) {
                $eleAvg[$ele] = '0';
            }
        }
        $result = HeliosUtility::deriveAverage($eleAvg);
        return $result;
    }

}
