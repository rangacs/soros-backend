<?php

//------------------------------------------------------------------------------
//! Lab Utility holds a list of AlignmentListEntry
//------------------------------------------------------------------------------
class LabUtility {

    public $activeTemplate;
    public $dataObject;
    public $column;
    public $lookUp;

    public function __construct($dataId) {

        $this->lookUp = array(
            'SiliceOxideContent' => 'SiO2',
            'AluminumOxideContent' => 'Al2O3',
            'IronOxideContent' => 'Fe2O3',
            'CalciumOxideContent' => 'CaO',
            'SulfurOxideContent' => 'SO3',
            'MagnesiumOxideContent' => 'MgO',
            'PotassiumOxideContent' => 'P2O5',
            'SodiumOxideContent' => 'SO2',
            'ChlorideContent' => 'Cl',
            'AluminaModulus' => 'AM',
            'IronModulus' => 'IM',
            'TricalciumSilicateContentASTM' => 'C3S',
            'TricalciumAluminateContentASTM' => 'C3A',
            'LimeSaturationFactor' => 'LSF',
            'SilicaModulus' => 'SM',
            'DicalciumSilicateContentASTM' => 'C2S',
            'TetracaliumAluminoferriteContentASTM' => 'C4AF',
            'SodiumEquivalent' => 'NaEQ'
        );
        //Get data row
        $this->dataObject = LabDataHistory::model()->find('lab_hist_id = :lab_hist_id', array(':lab_hist_id' => $dataId));

        //Get template associated with data
        $this->activeTemplate = LabTemplate::model()->find('template_id = :template_id', array(':template_id' => $this->dataObject->template_id));



        //parse template
        $tableInfo = $this->parseCsvTempString();

        $query = $this->buildCreateTableQuery($tableInfo);

        //create lab_data_temp;
        $result = Yii::app()->db->createCommand($query)->execute();


        //

        $tableInfo = $this->parseCsvDataString();
        $labDataquery = $this->buildInsertDataQuery($tableInfo);


        try {
            $result = Yii::app()->db->createCommand($labDataquery)->execute();
        } catch (Exception $ex) {
            $this->sendFailedResponse('Invalid data');
        }
    }

    public function buildCreateTableQuery($table) {

        $columns = array();
        //indexing 
        $columns[] = "`lab_data_id`  int NOT NULL  AUTO_INCREMENT PRIMARY KEY";

        foreach ($table['col'] as $key => $name) {
            $columnName = $this->normalise(trim($name));
            $type = $this->clean($table['type'][$key]);

            $this->column[] = $columnName;

            if ($type == 'datetime)') {

                $type = 'datetime';
            } else if ($type == 'decimal(10,3)') {

                $type = ' decimal(10,3) ';
            } else if (strtolower(trim($type)) == 'decimal') {

                $type = ' decimal(10,3) ';
            } else if ($type == 'string') {

                $type = ' varchar(20)  ';
            } else if (strtolower($type) == 'name') {

                $type = ' varchar(20)  ';
            }

            $sQuery = "`{$columnName}`  {$type}  ";
            $columns[] = $sQuery;
        }

        $columnQuery = implode(",", $columns);
        $tmpArray = "";
        $query = "DROP TABLE IF EXISTS `lab_data_temp`; " . "CREATE TABLE `lab_data_temp` (
                                        {$columnQuery}
                        $tmpArray              );";

        // echo $query;                                

        return $query;
    }

    public function buildInsertDataQuery($data) {


        $colType = Yii::app()->db->createCommand('describe lab_data_temp ')->queryAll();

        $cLookup;
        foreach ($colType as $key => $value) {

            $cLookup [$value['Field']] = $value;
        }
        $elements = $data['elements'];
        $values = $data['ele_data'];
//        $lookUp = $data['lookUp'];
        //Check columns 
        $eleSubQuery = "INSERT INTO   `lab_data_temp` (" . implode(',', $elements) . ")";

        $valuesString = array();
        foreach ($values as $item) {

            $fData = array();

            $tmpData = explode(',', $item);
            foreach ($elements as $index => $col) {


                $cType = $cLookup[$col];

                $TYPE = $cType['Type'];

                $pos = strpos($TYPE, 'decimal');

                if ($pos === 0) {
                    $TYPE = 'decimal';
                }

                if ($TYPE == 'datetime' || $TYPE == 'timestamp' || $TYPE == 'EndTime') {


                    $dateString = str_replace('/', '-', $tmpData[$index]);
                    $fData[] = date('Y-m-d H:i:s', strtotime($dateString));
                } else if ($TYPE == 'decimal') {

                    $formatted = sprintf("%01.2f", $tmpData[$index]);
                    $fData[] = $formatted;
                } else {

                    $fData[] = $tmpData[$index];
                }
            }// End of foreach
            $count = count($item);



            $dataEntry = $result = "'" . implode("', '", $fData) . "'";
            $tmpQuery = "(" . $dataEntry . ")";

            $valuesString[] = $tmpQuery;
        }// end of foreach



        $valuesQuery = implode(", ", $valuesString);

        $insertQuery = $eleSubQuery . " VALUES " . $valuesQuery;

        return $insertQuery;
    }

    public function parseCsvTempString() {


        $csv = $this->activeTemplate['content'];
        $csvData = $this->parse_csv($csv);


        $skipRow = 2; // $csvData[0][1] ; // Skip row


        $skipCol = 1; //$csvData[1][1]; // Skip col

        $table = array();
        $column = array();
        $types = array();
        $row = 1;

        $table['skipRow'] = $csvData[0][1];
        $table['skipCol'] = $csvData[1][1];
        $colNameRawArray = $csvData[$skipRow];
        $typeRowRawArray = $csvData[$skipRow + 1];

        $column = array_slice($colNameRawArray, $skipCol);

        $types = array_slice($typeRowRawArray, $skipCol);


        if (isset($column) || $types) {
            $table['col'] = $column;
            $table['type'] = $types;

            return $table;
        } else {
            return $table;
        }
    }

    public function clean($string) {

        $variable = str_replace('', '_', $string); // Replaces all spaces with hyphens.
        $normString = preg_replace('/[^A-Za-z0-9\_]/', '', $variable); // Removes special chars.
        return $variable;
    }

    public function normalise($string) {

        $tempStr = strtolower($string);
        //pawan
        $type = "";

        if (strpos($tempStr, 'date') !== false && $type !== 'type') {

            $string = 'EndTime';
        }
        $variable = str_replace('', '_', $string); // Replaces all spaces with hyphens.
        $normString = preg_replace('/[^A-Za-z0-9\_]/', '', $variable); // Removes special chars.

        return $this->lookuptable($normString);
    }

    public function lookuptable($key) {

        if (array_key_exists($key, $this->lookUp)) {
            return $this->lookUp[$key];
        } else {

            return $key;
        }
    }

    public function parseCsvDataString() {




        $database = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();

        $colquery = "SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_schema = '" . $database . "'
          AND table_name = 'lab_data_temp'";

        $colCount = Yii::app()->db->createCommand($colquery)->queryScalar();


        $rawData = json_decode($this->dataObject->data);



        $dataCsv = $rawData->ele_data; //array_splice($rawData->ele_data, $this->activeTemplateModel->skip_row);
        //pawan removed
        $table = array();
        $eleData = array();
        $columns = array();
        $row = 1;

        $lookUpdata = array();


        $colCsv = $rawData->elements; //array_slice($csvData[0], $this->activeTemplateModel->skip_col, $colCount - 1);




        foreach ($colCsv as $key => $column) {

            $tmpName = $this->normalise(trim($column));

            if (strlen($tmpName) != 0) {
                $columns[] = $tmpName;
                $lookUpdata[$tmpName] = $key;
            }
        }


        $table['elements'] = $columns;
        $table['lookup'] = $lookUpdata;
        $table['ele_data'] = $dataCsv;
        return $table;
    }

    function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true) {
        return array_map(
                function ($line) use ($delimiter, $trim_fields) {
            return array_map(
                    function ($field) {
                return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
            }, $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line)
            );
        }, preg_split(
                        $skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', preg_replace_callback(
                                '/"(.*?)"/s', function ($field) {
                            return urlencode(utf8_encode($field[1]));
                        }, $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string)
                        )
                )
        );
    }

    static function getAvg($aTable, $colName) {

        $splForms = array("C3S", "LSF", "SM", "IM", "AM", "KH", "TPH");

        if(!isset($aTable) || !count($aTable) )
            return 0;
        
        if (in_array($colName, $splForms)) {
            $formVal = 0;
            if ($colName) {

                $AnSiO2 = array_sum($aTable['SiO2']) / count($aTable['SiO2']);
                $AnFe2O3 = array_sum($aTable['Fe2O3']) / count($aTable['Fe2O3']);
                $AnAl2O3 = array_sum($aTable['Al2O3']) / count($aTable['Al2O3']);
                $AnSO3 = array_sum($aTable['SO3']) / count($aTable['SO3']);
                $AnCaO = array_sum($aTable['CaO']) / count($aTable['CaO']);

                if ($colName == "LSF" && isset($AnCaO)) {

                    //TODO: to review the formula
                    $val = ($AnCaO / (2.8 * $AnSiO2 + 1.18 * $AnAl2O3 + 0.65 * $AnFe2O3)) * 100;
                    $formVal = round($val, 2);
                } else if ($colName == "C3S" && isset($AnCaO)) {

                    //TODO: to review the formula
                    $val = (4.07 * $AnCaO) - (7.6 * $AnSiO2 + 6.72 * $AnAl2O3 + 1.43 * $AnFe2O3 + 2.852 * $AnSO3);
                    $formVal = round($val, 3);
                    //else if ($colName == "NAEQ")
                    //	$colName = "IF( (K2O <= 0), 0 , ROUND((K2O * 6.58 + Na2O ),3) ) as NAEQ";//NAEQ Equation Correction JW160412
                } else if ($colName == "SM" && isset($AnSiO2)) {

                    $formVal = round((( $AnSiO2) /
                            ($AnAl2O3 +
                            $AnFe2O3)), 3);
                } else if ($colName == "IM" && isset($AnSiO2)) {
                    $formVal = round(( $AnAl2O3 /
                            $AnFe2O3), 3);
                } else if ($colName == "AM" && isset($AnSiO2)) {
                    $formVal = round(( $AnAl2O3 /
                            $AnFe2O3), 3);
                } else if ($colName == "TPH") {
                    $formVal = round($results[0]['A_' . $value], 3);
                }if ($colName == "KH") {
                    $formVal = (($AnCaO - 1.65 * $AnAl2O3 - 0.35 * $AnFe2O3) / (2.80 * $AnSiO2));
                }
            }
            return $formVal;
        } else {

            return array_sum($aTable[$colName]) / count($aTable[$colName]);
        }
    }

    static function calculateStandardDeviation($aValues) {
        
        $fMean = array_sum($aValues) / count($aValues);
        $fVariance = 0.0;
        foreach ($aValues as $i) {
            $fVariance += pow($i - $fMean, 2);
        }
        // $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
        return (float) sqrt($fVariance);
    }
    
   static function stdDeviation($data) {
    // Check if the array is not empty
    if (empty($data)) {
        return null; // or handle it as per your requirement
    }

    // Calculate the mean (average) of the data
    $mean = array_sum($data) / count($data);

    // Calculate the squared differences from the mean
    $squaredDifferences = array_map(function ($value) use ($mean) {
        return pow($value - $mean, 2);
    }, $data);

    // Calculate the variance
    $variance = array_sum($squaredDifferences) / count($data);

    // Calculate the standard deviation
    $standardDeviation = sqrt($variance);

    return $standardDeviation;
}


    static function stdError($aValues) {
        $noEle = count($aValues);
        $fMean = array_sum($aValues) / $noEle;
        $fVariance = 0.0;
        foreach ($aValues as $i) {
            $fVariance += pow($i - $fMean, 2);
        }
        $fVariance /= $noEle;
        $sd = (float) sqrt($fVariance);
        $se = (float) ($sd / sqrt($noEle));

        return $se;
    }

    static function getColumn($array, $ele) {

        $col = array();
        if (isset($array)) {


            foreach ($array as $row) {

                if (isset($row[$ele])) {

                    $col[] = $row[$ele];
                }
            }
        }
        return $col;
    }

    public function getNoFilterTons($startTime,$endTime){

        $sql = "SELECT SUM(totalTons)
        FROM analysis_A1_A2_Blend
        WHERE LocalendTime >= '" . $startTime . "'
          AND LocalendTime <= '" . $endTime . "'";
        $sum = Yii::app()->db->createCommand($sql)->queryScalar();
        return $sum;
    }

    public static function getSpValue($eleArrays, $spEle) {



        if (isset($eleArrays['Al2O3'])) {

            $tempSpArray = array();
            $al203Array = $eleArrays['Al2O3'];


            //Calculate formula array
            foreach ($al203Array as $key => $val) {

                $tAl2O3 = $eleArrays['Al2O3'][$key];

                $tSiO2 = $eleArrays['SiO2'][$key];
              
                $tFe2O3 = $eleArrays['Fe2O3'][$key];

                $tCaO = $eleArrays['CaO'][$key];


                //Calculate formulas
                if ($spEle == 'KH') {
                    $formulaVal = (($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2));
                } else if ($spEle == "SM") {
                    $formulaVal = round((( $tSiO2) /
                            ($tAl2O3 +
                            $tFe2O3)), 3);
                } else if ($spEle == "AM") {
                    $formulaVal = round(( $tAl2O3 / $tFe2O3), 3);
                } else {

                    $formulaVal = 0;
                }



                $tempSpArray [] = $formulaVal;
            }


            return $tempSpArray;
        } else {


            return array();
        }
    }

    public static function spStdError($eleArrays, $spEle) {



        if (isset($eleArrays['Al2O3'])) {

            $tempSpArray = array();
            $al203Array = $eleArrays['Al2O3'];


            //Calculate formula array
            foreach ($al203Array as $key => $val) {

                $tAl2O3 = $eleArrays['Al2O3'][$key];

                $tSiO2 = $eleArrays['SiO2'][$key];

                $tFe2O3 = $eleArrays['Fe2O3'][$key];

                $tCaO = $eleArrays['CaO'][$key];


                //Calculate formulas
                if ($spEle == 'KH') {
                    $formulaVal = (($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2));
                } else if ($spEle == "SM") {

                    $formulaVal = round((( $tSiO2) /
                            ($tAl2O3 +
                            $tFe2O3)), 3);
                } else if ($spEle == "AM") {
                    $formulaVal = round(( $tAl2O3 /
                            $tFe2O3), 3);
                } else {

                    $formulaVal = 0;
                }



                $tempSpArray [] = $formulaVal;
            }



            $stdError = LabUtility::stdError($tempSpArray);

            return $stdError;
        } else {


            return 0;
        }
    }

    public static function spStdDeviation($eleArrays, $spEle) {



        if (isset($eleArrays['Al2O3'])) {

            $tempSpArray = array();
            $al203Array = $eleArrays['Al2O3'];


            //Calculate formula array
            foreach ($al203Array as $key => $val) {

                $tAl2O3 = $eleArrays['Al2O3'][$key];

                $tSiO2 = $eleArrays['SiO2'][$key];
 
                $tFe2O3 = $eleArrays['Fe2O3'][$key];

                $tCaO = $eleArrays['CaO'][$key];


                //Calculate formulas
                if ($spEle == 'KH') {
                    $formulaVal = (($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2));
                } else if ($spEle == "SM") {

                    $formulaVal = round((( $tSiO2) /
                            ($tAl2O3 +
                            $tFe2O3)), 3);
                } else if ($spEle == "AM") {
                    $formulaVal = round(( $tAl2O3 /
                            $tFe2O3), 3);
                } else {

                    $formulaVal = 0;
                }

                $tempSpArray [] = $formulaVal;
            }



            $stdDev = LabUtility::stdDeviation($tempSpArray);

            return $stdDev;
        } else {


            return 0;
        }
    }

    public static function getOffsetRangeSettings() {

        $query = 'select * from ac_settings';
        $results = Yii::app()->db->createCommand($query)->queryAll();


        $lookUp = array();
        foreach ($results as $offset) {

            $lookUp[$offset['element_name']] = $offset;
        }
        return $lookUp;
    }
    
    
    
    static function spElements() {

        $spList = Yii::app()->db->createCommand('select * from rm_set_points where 1 order by sp_priority')->queryAll();

        $elements = self::getColumn($spList, 'sp_name');


        $elements[] = 'TPH';

        return $elements;
    }
    
    
    static function getChartColumn($array, $ele) {

        $data = $array;
        $col = array();
        if (isset($data)) {


            foreach ($data as $row) {

                if (isset($row[$ele])) {

                    $time = (int) strtotime($row['LocalendTime']) * 1000; //converting it into javascript time format
                    $value = (float) $row[$ele];
                    $col[] = array($time, $value);
                }
            }
        }
        return $col;
    }

    static function getChartSpColumn($array, $ele) {

        $data = $array;
        $col = array();
        if (isset($data)) {


            foreach ($data as $row) {

                if (isset($row[$ele])) {

                    $time = (int) strtotime($row['LocalendTime']) * 1000; //converting it into javascript time format
                    $value = (float) self::getSpVal($ele);
                    $col[] = array($time, $value);
                }
            }
        }
        return $col;
    }

    static function getChartSpUtoleranceColumn($array, $ele) {

        $data = $array;
        $col = array();
        if (isset($data)) {


            foreach ($data as $row) {

                if (isset($row[$ele])) {

                    $time = (int) strtotime($row['LocalendTime']) * 1000; //converting it into javascript time format
                    $spValueArray = self::getSpRow($ele);
                    $col[] = array($time, $spValueArray['sp_value_num'] + $spValueArray['sp_tolerance_ulevel']);
                }
            }
        }
        return $col;
    }

    static function getChartSpLtoleranceColumn($array, $ele) {

        $data = $array;
        $col = array();
        if (isset($data)) {


            foreach ($data as $row) {

                if (isset($row[$ele])) {

                    $time = (int) strtotime($row['LocalendTime']) * 1000; //converting it into javascript time format
                    $spValueArray = self::getSpRow($ele);
                    $col[] = array($time, $spValueArray['sp_value_num'] - $spValueArray['sp_tolerance_llevel']);
                }
            }
        }
        return $col;
    }

    static function isSpEle($spEle) {

        $spval = Yii::app()->db->createCommand("select sp_name from rm_set_points where sp_name ='$spEle'")->queryScalar();

        return isset($spval) ? true : false;
    }

    static function getSpVal($spEle) {

        $spval = Yii::app()->db->createCommand("select sp_value_num from rm_set_points where sp_name ='$spEle'")->queryScalar();

        return isset($spval) ? $spval : false;
    }

    static function getSpRow($spEle) {

        $result = Yii::app()->db->createCommand("select * from rm_set_points where sp_name ='$spEle'")->queryRow();

        return isset($result) ? $result : array();
    }

    static function getCoreElements() {


        $splist = Yii::app()->db->createCommand("select * from rm_set_points");
    }

    static function shotFeedName($name) {

        $lower = strtolower($name);
        $pos = strpos($lower, 'limestone');
        if (strpos($lower, 'limestone') !== false) {

            $fstring = substr($name, $pos, 9);
            $sName = str_replace($fstring, 'Lime', $name);

            return $sName;
        } else {
            return $name;
        }
    }

    /**
     * 
     * @param string $key
     * @param string|int  $defaultValue
     * @return  int|string
     */
    static function getRmSettings($key, $defaultValue) {


        $val = Yii::app()->db->createCommand("select varValue from rm_settings where varKey ='{$key}'")->queryScalar();

        if ($val) {

            return $val;
        } else {

            return $defaultValue;
        }
    }


}
