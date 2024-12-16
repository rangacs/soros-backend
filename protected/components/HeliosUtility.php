<?php

/**
 * Description of Utility
 *
 * @author webtatva
 */
class HeliosUtility {

    //put your code here




    static function renderThcol($array, $filter, $sourceLookUp) {


        foreach ($sourceLookUp as $column) {

            $strPos = strpos($column, $filter);
            if ($strPos !== false) {


                //Getting source id only by striping chars
                //Feeder
                $key = preg_replace('/[^0-9]/', '', $column);


                echo "<th>" . $sourceLookUp[$key] . "</th>";
            } else {


                echo "<th>" . $column . "</th>";
            }
        }
    }

    static function siloShortName($siloName) {



        $nameValPairs = array("New_LimeStone" => "Lime(N)",
            "Old_LimeStone" => "Lime(O)",
            "Clay" => "Clay",
            "Old_Iron" => "Iron(O)",
            "New_Iron" => "Iron(N)",
            "New_Sand" => "Sand(N)",
            "Old_Sand" => "Sand(O)",
            "Bottom_Ash" => "B-Ash"
        );

        if (array_key_exists($siloName, $nameValPairs)) {
            return $nameValPairs[$siloName];
        } else {

            return $siloName;
        }
    }

    static function nameMap($name) {




        $lookUp = array(
            'SiliceOxideContent' => 'SiO2',
            'AluminumOxideContent' => 'Al2O3',
            'IronOxideContent' => 'Fe2O3',
            'CalciumOxideContent' => 'CaO',
            'SulfurOxideContent' => 'SO3',
            'MagnesiumOxideContent' => 'MgO',
            'PotassiumOxideContent' => 'P2O5',
            'SodiumOxideContent' => 'SO2',
            'ChlorideContent' => 'Cl',
            //'AluminaModulus' => 'AM',
            'AluminaModulus' => 'IM',
            'TricalciumSilicateContentASTM' => 'C3S',
            'TricalciumAluminateContentASTM' => 'C3A',
            'LimeSaturationFactor' => 'LSF',
            'SilicaModulus' => 'SM'
        );

        $tempStr = strtolower($name);


        if (strpos($tempStr, 'date') !== false && $type !== 'type') {

            $name = 'EndTime';
        }
        $variable = str_replace('', '_', $name); // Replaces all spaces with hyphens.
        $normString = preg_replace('/[^A-Za-z0-9\_]/', '', $variable); // Removes special chars.



        if (array_key_exists($normString, $lookUp)) {
            return $lookUp[$normString];
        } else {

            return $name;
        }
    }

    static function arrayToString($array = array()) {
        $string = implode("\r\n", $array);
        return $string;
    }

    static function roundToNextHour($dateString) {
        $date = new DateTime($dateString);
        $minutes = $date->format('i');
        if ($minutes > 0) {
            $date->modify("+1 hour");
            $date->modify('-' . $minutes . ' minutes');
        }
        return $date;
    }

    static function getDisplayElements() {

        $resultString = Yii::app()->db->createCommand(" select varValue from rm_settings where varkey ='SOROS_DISPLAY_ELEMENTS'")->queryScalar();

        $spList = Yii::app()->db->createCommand(" select * from rm_set_points")->queryAll();

        $spElements = array(); //LabUtility::getColumn($spList, 'sp_name');


        $elementsString = $resultString != false ? $resultString : 'SiO2,Fe2O3,Al2O3,CaO';
        $elementArray = explode(',', $elementsString);

        $eleAr = array();
        foreach ($elementArray as $index => $ele) {

            if (!in_array($ele, $spElements)) {
                $eleAr[] = $ele;
            }
        }


        $displayElements = array_merge($eleAr, $spElements);
        return array_unique($displayElements);
    }

    static function stringToArray($string = '') {
        $array = explode("\r\n", trim($string)); // trim() gets rid of the last \r\n
        foreach ($array as $key => $item) {
            if ($item == '') {
                unset($array[$key]);
            }
        }
        return $array;
    }

    static function getAutoCalibSettings() {

        $query = 'select * from ac_settings ';

        $results = Yii::app()->db->createCommand($query)->query();

        $acSettings = array();

        foreach ($results as $item) {

            $acSettings[$item['element_name']] = $item;
        }


        return $acSettings;
    }

    static function getAvgElements($default = 1) {

        $uid = Yii::app()->user->id;
        $def_layout = Layouts::model()->find(array('condition' => 'user_id=:x AND default_layout=:y', 'params' => array(':x' => $uid, ':y' => $default)));

        $SystemMessagesWidget = GadgetsData::model()->find(array('condition' => 'lay_id=:x AND gadget_type=:y', 'params' => array(':x' => $def_layout->lay_id, ':y' => 'System_Messages')));

        $elementString = $SystemMessagesWidget->data_source;

        $elements = explode(';', $elementString);
        $elements[0] = 'LocalendTime';
        array_pop($elements); //remove last empty col
        return $elements;
        //System_Messages , lay_id
    }

    static function spElements() {

        $spList = Yii::app()->db->createCommand('select * from rm_set_points where 1 order by sp_priority')->queryAll();

        $elements = LabUtility::getColumn($spList, 'sp_name');


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

    
    static function deriveAverage($row) {
        
        if($row['Al2O3'] ==0 || !isset($row['Al2O3'])){
            
            return $row;
            
        }
        $tAl2O3 = (float) $row['Al2O3'];

        $tSiO2 = (float) $row['SiO2'];

        $tFe2O3 = (float) $row['Fe2O3'];

        $tCaO = (float) $row['CaO'];

        $kh = (($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2));
        $sm = round((( $tSiO2) / ($tAl2O3 + $tFe2O3)), 2);
        $lsf = ( $tCaO / (2.8 * $tSiO2 + 1.18 * $tAl2O3 + 0.65 * $tFe2O3 )  * 100);
        $am = round(($tAl2O3/ $tFe2O3),2);
        $im = round(($tAl2O3/ $tFe2O3),2);
	$sm = round(($tSiO2/($tAl2O3+$tFe2O3)),2); //(SiO2/(Al2O3+Fe2O3))

        $row['AM'] = $am;
        $row['IM'] = $im;
        $row['LSF'] = $lsf;
	$row['SM'] = $sm;
        
        return $row;
        
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

    static function getColumnsForTable($table) {
        $tableColumns = array();
        $sql = "SHOW COLUMNS FROM $table";
        $columns = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($columns as $key => $value) {
            $tableColumns[] = ($value['Field']);
        }

        return $tableColumns;
    }

}
