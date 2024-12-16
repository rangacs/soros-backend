<?php

//namespace app\components;
//use Yii;
//define('PRODUCT_NAME_VERSION', 'helios2');
define('PRODUCT_NAME_VERSION', 'helios1');

//define('USE_IN_YII', 1);

define('TESTING', 1);

class ScaleParams {

    public $log;
    public $con;
    public $setting_enableCronHourlyUpdateDB;
    public $setting_changeOnSingleCurrentRecordDB;
    public $setting_useRealValuesDB;
    public $setting_useTrendDB;

    public function __construct($log = null) {
        $this->log = $log;
        if (defined('TESTING')) {
            $this->con = connectDB($log);
        }

        $this->setting_enableCronHourlyUpdateDB = $this->readSettingDB('AUTO_CALIB_ENABLE_CRON_HOURLY_UPDATE');
        $this->setting_changeOnSingleCurrentRecordDB = $this->readSettingDB('AUTO_CALIB_CHANGE_ON_SINGLE_CURRENT_RECORD');
        $this->setting_useRealValuesDB = $this->readSettingDB('AUTO_CALIB_REAL_VALUES');
        $this->setting_useTrendDB = $this->readSettingDB('AUTO_CALIB_USE_TREND');
        return;
    }

    public function __destruct() {

        if (defined('TESTING')) {
            disconnectDB($this->log, $this->con);
        }

        return;
    }

    function readSettingDB($db_value){
        $setting = 0;
        $sQuery = "select * from rm_settings where varName = '$db_value'";
        if (defined('USE_IN_YII')) {

            if (PRODUCT_NAME_VERSION == 'helios1') {
                $command = Yii::app()->db->createCommand($sQuery);
                $result = $command->queryRow();
            } else {
                $command = Yii::$app->db->createCommand($sQuery);
                $result = $command->queryOne();
            }
            if ($result)
                $setting = $result['varValue'];
        } else {
            $result = mysqli_query($this->con, $sQuery);
            if ($result && $result->num_rows) {
                $row = $result->fetch_array();
                $setting = $row['varValue'];
            } else {
                $setting = 0;
            }
        } 
        
        return $setting;
        
    }
    
    function readBigDeviationDB($element_name) {

        $bigDeviation = 0;
        $sQuery = "SELECT big_deviation FROM `ac_settings` WHERE `element_name` = '" . $element_name . "'";
        if (defined('USE_IN_YII')) {

            if (PRODUCT_NAME_VERSION == 'helios1') {
                $command = Yii::app()->db->createCommand($sQuery);
                $result = $command->queryRow();
            } else {
                $command = Yii::$app->db->createCommand($sQuery);
                $result = $command->queryOne();
            }
            if ($result)
                $bigDeviation = $result['big_deviation'];
        } else {
            $result = mysqli_query($this->con, $sQuery);
            if ($result && $result->num_rows) {
                $row = $result->fetch_array();
                $bigDeviation = $row['big_deviation'];
            } else {
                $bigDeviation = 0;

                $p1 = "Query returned no results, Query is: ";
                $p2 = $sQuery;
                $this->msgLog($p1, $p2);
            }
        }

        return $bigDeviation;
    }

    function readAcceptableDeviationDB($element_name) {

        $acceptableDeviation = 0;
        $sQuery = "SELECT acceptable_deviation FROM `ac_settings` WHERE `element_name` = '" . $element_name . "'";
        if (defined('USE_IN_YII')) {

            if (PRODUCT_NAME_VERSION == 'helios1') {
                $command = Yii::app()->db->createCommand($sQuery);
                $result = $command->queryRow();
            } else {
                $command = Yii::$app->db->createCommand($sQuery);
                $result = $command->queryOne();
            }
            if ($result)
                $acceptableDeviation = $result['acceptable_deviation'];
        } else {
            $result = mysqli_query($this->con, $sQuery);
            if ($result && $result->num_rows) {
                $row = $result->fetch_array();
                $acceptableDeviation = $row['acceptable_deviation'];
            } else {
                $acceptableDeviation = 0;

                $p1 = "Query returned no results, Query is: ";
                $p2 = $sQuery;
                $this->msgLog($p1, $p2);
            }
        }

        return $acceptableDeviation;
    }

    function updateHourlyTable($element_name, $calculated_offset) {
        $ret_value = false;
        //we want part update, store in DB
        $hourly_offset = round($calculated_offset, 2);

        $validTill = date('Y-m-d H:i:s', (strtotime(date('Y-m-d H') . ":15:00") + (1 * 60 * 60)));
        $sQuery = "UPDATE `calibration_hourly` SET `consumed` = '0',`" . $element_name . "_offset`='" . $hourly_offset . "', `valid_till` = '" . $validTill . "',`updated_by` = '1',`updated` = now() WHERE `cal_id` = '1'";

        if (defined('USE_IN_YII')) {

            if (PRODUCT_NAME_VERSION == 'helios1') {
                $command = Yii::app()->db->createCommand($sQuery);
                $result = $command->execute();
            } else {
                $command = Yii::$app->db->createCommand($sQuery);
                $result = $command->execute();
            }
        } else {
            $result = mysqli_query($this->con, $sQuery);
        }

        if ($result) {
            $ret_value = true;
            $p1 = "cron calibration_hourly updated ";
            $p2 = "valid_till= " . $validTill . ", " . $element_name . "_offset=$hourly_offset";
            $this->msgLog($p1, $p2);
        } else {
            $p1 = "Error Query returned no results, Query is: ";
            $p2 = $sQuery;
            $this->msgLog($p1, $p2);
        }

        return $ret_value;
    }

    function filterRecords($arLab, $arAnalyzer, $elementName, $settings) {

        $ignore_offset_difference = $settings['diff'];

        //assign weights
        $weight = 1;
        foreach ($arLab as $key => $value) {
            $weight = $weight * 2;
        }

        //find simple trend
        $trend = 0;
        $sumOffset = 0;
        foreach ($arLab as $key => $value) {
            $offsetT = round($arLab[$key] - $arAnalyzer[$key], 2);
            $combineArray[] = array('lab' => $arLab[$key], 'analyzer' => $arAnalyzer[$key], 'valid' => 1, 'offset' => $offsetT, 'weight' => $weight);
            $sumOffset += $offsetT;
            $weight = $weight / 2;

            if ($offsetT > 0)
                $trend++;
            else
                $trend--;
        }
        
        $countOffset = count($combineArray);

        //find mean offset
        $meanOffset = $sumOffset / $countOffset;

        if ($this->setting_useRealValuesDB) {
            $min = $settings['min_real'];
            $max = $settings['max_real'];
        } else {
            $min = $settings['min'];
            $max = $settings['max'];
        }

        //1.drop the values that are not in range for analyzer
        //2.drop all the values for which difference with lab is < $settings['difference']
        foreach ($combineArray as $key => $item) {

            //1.drop the values that are not in range for analyzer
            if ($item['analyzer'] < $min || $item['analyzer'] > $max) {
                $combineArray[$key]['valid'] = 0;
                $item['valid'] = 0;

                $p1 = $elementName . ' Analyzer item dropped, out of range';
                $p2 = $elementName . ' :value ' . $item['analyzer'] . ' tolerance ' . $min . '-' . $max;
                $this->msgLog($p1, $p2);
            }

            //1a.drop the values that are not in range for Lab
            if ($item['lab'] < $min || $item['lab'] > $max) {
                $combineArray[$key]['valid'] = 0;
                $item['valid'] = 0;

                $p1 = $elementName . ' Lab item dropped, out of range';
                $p2 = $elementName . ' :value ' . $item['lab'] . ' tolerance ' . $min . '-' . $max;
                $this->msgLog($p1, $p2);
            }

            //2.drop all the values for which difference with lab is < $settings['difference']
            $value = abs($item['offset']);
            if ($value <= abs($ignore_offset_difference) && abs($trend) < $countOffset && !$this->setting_useTrendDB) {
                $combineArray[$key]['valid'] = 0;
                $item['valid'] = 0;

                $p1 = $elementName . ' Lab item dropped, lab-analyzer difference';
                $p2 = $elementName . ' :value ' . $item['analyzer'] . '-' . $item['lab'] . ' = ' . $value;
                $this->msgLog($p1, $p2);
            }


            //drop 0 & negative values as well
            if ($item['lab'] <= 0 || $item['analyzer'] <= 0) {
                $combineArray[$key]['valid'] = 0;
                $item['valid'] = 0;

                $p1 = $elementName . ' Values dropped, value -ve or 0';
                $p2 = $item['analyzer'] . ',' . $item['lab'];
                $this->msgLog($p1, $p2);
            }
        }

        return $combineArray;
    }

    function msgLog($p1, $p2) {
        $msg = $p1 . $p2;
        if ($this->log)
            $this->log->trace($msg);
        if (defined('USE_IN_YII'))
            Logger::calibLogger('log', $p1, $p2);
    }

    function getOffsetsRemovingOuliers2($arLab, $arAnalyzer, $cOffsetSettings) {

        $this->calculateOffset($arLab, $arAnalyzer, $settings);
    }

    function calculateOffset($arLab, $arAnalyzer, $settings) {

        $offset = 0;
        //check for valid values
        $anaSum = array_sum($arAnalyzer) / count($arAnalyzer);
        $labSum = array_sum($arLab) / count($arLab);
        $elementName = $settings['element_name'];

        if (!$anaSum || !$labSum) {
            $offset = $settings['current_value'];
            $response = array("found" => false, "offset" => $offset, array(0));
            // Yii::debug('**********************', var_export($category,true));
            //   Yii::error('Blank array sent, check lab or analyzer samples
            //      Logger::calibLogger('log', $elementName . 'Blank array sent', 'Blank array sent, check lab or analyzer samples', '');
            $p1 = $elementName . ' Blank array sent check lab or analyzer samples';
            $p2 = "";
            $this->msgLog($p1, $p2);

            return $response;
        }

        $countSamples = count($arAnalyzer);

        //if single array but AUTO_CALIB_ENABLE_CRON_HOURLY_UPDATE not enabled
        if ($countSamples == 0 || ($this->setting_changeOnSingleCurrentRecordDB == 0 && $countSamples == 1 )) {
            $offset = $settings['current_value'];
            $response = array("found" => false, "offset" => $offset, array(0));
            $p1 = $elementName . " Not Enough Samples";
            $p2 = '0 or 1 sample sent, minimum 2 required';
            $this->msgLog($p1, $p2);
            return $response;
        }

        $currentOffset = round($arLab[0] - $arAnalyzer[0], 2);
        $acceptableDeviation = $this->readAcceptableDeviationDB($elementName);

        // if current deviation is less than acceptable deviation don't do anything
        if (abs($acceptableDeviation) > 0 && (abs($currentOffset) < abs($acceptableDeviation))) {
            $offset = $settings['current_value'];
            $response = array("found" => false, "offset" => $offset, array(0));
            $p1 = $elementName . " Current Deviation in acceptable range ";
            $p2 = "Current Offset- " . $currentOffset . ' < ' . $acceptableDeviation . " -Acceptable Deviation";
            $this->msgLog($p1, $p2);

            return $response;
        }

        $meanAnalyzer = array_sum($arAnalyzer) / count($arAnalyzer);
        $meanLab = array_sum($arLab) / count($arLab);

        $sdevAnalyzer = LabUtility::stdDeviation($arAnalyzer);
        $sdevLab = LabUtility::stdDeviation($arLab);

        $filtered_combined_array = $this->filterRecords($arLab, $arAnalyzer, $elementName, $settings);

        // remove outliers using standard deviation method
        if ($settings['useSDtoRemoveOutlier'] == 1) {
            foreach ($filtered_combined_array as $key => $item) {

                $tolUp = $meanLab + 2 * $sdevLab;
                $tolDown = $meanLab - 2 * $sdevLab;
                if ($item['lab'] > $tolUp || $item['lab'] < $tolDown) {
                    $filtered_combined_array[$key]['valid'] = 0;
                    $item['valid'] = 0;
                    $p1 = 'Lab item dropped, useSDtoRemoveOutlier';
                    $p2 = 'lab ' . $item['lab'] . 'tolerance ' . $tolUp . '/' . $tolDown;
                    $this->msgLog($p1, $p2);
                }

                $tolUp = $meanAnalyzer + $sdevAnalyzer;
                $tolDown = $meanAnalyzer - $sdevAnalyzer;
                if ($item['analyzer'] > $tolUp || $item['analyzer'] < $tolDown) {
                    $filtered_combined_array[$key]['valid'] = 0;
                    $item['valid'] = 0;
                    $p1 = 'Analyzer item dropped, useSDtoRemoveOutlier';
                    $p2 = 'analyzer ' . $item['lab'] . 'tolerance ' . $tolUp . '/' . $tolDown;
                    $this->msgLog($p1, $p2);
                }
            }
        }

        if ($settings['useAvgOffsetMeanToRemoveOutlier'] == 1) {
            //find mean of differences
            foreach ($filtered_combined_array as $key => $item) {

                //   $item['valid'] = 1;
                //mar all items which are > than offset as invalid
                if (abs($item['offset']) > abs($meanOffset))
                    $item['valid'] = 0;
            }
        }

        $weighted_sum = 0;

        //delete all invalid values
        foreach ($filtered_combined_array as $key => $item) {
            if ($item['valid'] == 1) {
                if ($settings['useWeightedSum'] == 1) {
                    $finalarray[] = $item['weight'] * $item['offset'];
                    $validOffset = $item['offset'];
                } else {
                    $finalarray[] = $item['offset'];
                    $validOffset = $item['offset'];
                }
                $weighted_sum += $item['weight'];
                $validarray[] = array('lab' => $arLab[$key], 'analyzer' => $arAnalyzer[$key], 'offset' => $validOffset);
            }
        }

        $finalCount = 0;
        if (!empty($finalarray))
            $finalCount = count($finalarray);

        $is_ancompare = $settings['from_ancompare'];
        //turnoff hourly cron update flag if coming from ancompare
        if($is_ancompare)
            $this->setting_enableCronHourlyUpdateDB = 0;
        
        switch ($finalCount) {
            //empty
            case 0:
                $offset = 0;
                $offset = $settings['current_value'];
                $response = array("found" => false, "offset" => $offset, array(0));
                $p1 = $elementName . " No Valid Records";
                $p2 = "No of valid records is $tempCount ";
                $this->msgLog($p1, $p2);
                break;
            //one valid item
            case 1:
                //$settings_actionable_current_deviation = $settings['actionable_current_deviation'];
                $settings_big_deviation = $settings['big_deviation'];

                //single filtered record & its current then do the change
                if ($validOffset == $currentOffset && (abs($validOffset) > abs($settings_big_deviation))) {
                    $calculated_offset = $currentOffset;
                    if ($this->setting_enableCronHourlyUpdateDB) {
                        $correction_percentage_till_cron = $settings['correction_percentage_till_cron'];
                        if ($correction_percentage_till_cron <= 0)
                            $correction_percentage_till_cron = 0.8; //default is 80%				
                        //update only when its not from an_compare
                        if (!$is_ancompare) {
                            $correction_offset = $calculated_offset * $settings['correction_pct'];
                            $correction_offset = round($correction_offset, 2);
                            $combinedoffset = $correction_offset + $settings['current_value'];
                            $result = $this->updateHourlyTable($elementName, $combinedoffset);
                        }
                        //we want one full update till next hour
                        $correction_offset = $currentOffset * $correction_percentage_till_cron;
                        $correction_offset = round($correction_offset, 2);
                        $percentage_applied = $correction_percentage_till_cron;
                    } else {
                        $correction_offset = $currentOffset * $settings['correction_pct'];
                        $correction_offset = round($correction_offset, 2);
                        $percentage_applied = $settings['correction_pct'];
                    }

                    $combinedoffset = $correction_offset + $settings['current_value'];
                    $p1 = $elementName . ' single point deviation above allowed, % applied is ' . $percentage_applied;
                    $p2 = "Combined offset is :" . $combinedoffset;
                    $this->msgLog($p1, $p2);

                    $response = array("found" => true, "offset" => $combinedoffset, $finalarray);
                    $info_string = "Offsets Calculated: " . $currentOffset . "$ allowed: " . $correction_offset . " ###Old: " . $settings['current_value'] . " $ New Proposed: " . $combinedoffset;
                    $p1 = $elementName . " ***Offset Found****";
                    $p2 = $info_string;
                    $this->msgLog($p1, $p2);
                } else {
                    $offset = 0;
                    $offset = $settings['current_value'];
                    $response = array("found" => false, "offset" => $offset, array(0));
                    $p1 = $elementName . " Valid record is within max deviation";
                    $p2 = "No of valid records is $tempCount ";
                    $this->msgLog($p1, $p2);
                }
                break;

            default:

                if ($settings['useWeightedSum'] != 1) {
                    $weighted_sum = $tempCount;
                } else {
                    $p1 = $elementName . ' Using Weighted Method';
                    $p2 = "Weighted Sum is " . $weighted_sum;
                    $this->msgLog($p1, $p2);
                }

                $finalarraysum = array_sum($finalarray);
                $calculated_offset = $finalarraysum / $weighted_sum;
                $calculated_offset = round($calculated_offset, 2);

                if (abs($calculated_offset) > $settings['max_offset_change']) {

                    //make max offset change
                    $calculated_offset = ($calculated_offset / abs($calculated_offset)) * $settings['max_offset_change'];

                    $correction_offset = $calculated_offset * $settings['correction_pct'];
                    $correction_offset = round($correction_offset, 2);
                    $combinedoffset = $correction_offset + $settings['current_value'];

                    $response = array("found" => true, "offset" => $combinedoffset, $validarray);
                    $error_string = "Calculated offset required " . $calculated_offset . " is more than allowed " . $settings['max_offset_change'];
                    $p1 = $elementName . " Huge Calculated offset detected, applying maximum offset change ";
                    $p2 = $error_string;
                    $this->msgLog($p1, $p2);
                } else {

                    if ($this->setting_enableCronHourlyUpdateDB) {
                        $correction_percentage_till_cron = $settings['correction_percentage_till_cron'];
                        if ($correction_percentage_till_cron <= 0)
                            $correction_percentage_till_cron = 0.8; //default is 80%
                        //update only when its not from an_compare
                        if (!$is_ancompare) {
                            $correction_offset = $calculated_offset * $settings['correction_pct'];
                            $correction_offset = round($correction_offset, 2);
                            $combinedoffset = $correction_offset + $settings['current_value'];
                            $result = $this->updateHourlyTable($elementName, $combinedoffset);
                        }
                        //we want one full update till next hour
                        $correction_offset = $currentOffset * $correction_percentage_till_cron;
                        $correction_offset = round($correction_offset, 2);
                    } else {
                        $correction_offset = $calculated_offset * $settings['correction_pct'];
                        $correction_offset = round($correction_offset, 2);
                    }

                    //for using with real values
                    if ($this->setting_useRealValuesDB) {
                        $combinedoffset = $correction_offset;
                        $p1 = $elementName . ' Using Real Values';
                        $p2 = "Absolute offset is :" . $combinedoffset;
                        $this->msgLog($p1, $p2);
                    } else {
                        $combinedoffset = $correction_offset + $settings['current_value'];
                        $p1 = $elementName . ' Using last modified calibration method';
                        $p2 = "Combined offset is :" . $combinedoffset;
                        $this->msgLog($p1, $p2);
                    }
                    $response = array("found" => true, "offset" => $combinedoffset, $validarray);
                    $info_string = "Offsets Calculated: " . $calculated_offset . "$ allowed: " . $correction_offset . " ###Old: " . $settings['current_value'] . " $ New Proposed: " . $combinedoffset;
                    $p1 = $elementName . " ***Offset Found****";
                    $p2 = $info_string;
                    $this->msgLog($p1, $p2);
                }

                break;
        }
        return $response;
    }

}

if (defined('TESTING')) {
    testmain();
    exit();
}

function testmain() {

    chdir(dirname(__FILE__));
    require_once '../common/log4php/Logger.php';
    require_once '../common/sabiaLogger.php';
    require_once '../common/utils.php';

    error_reporting(E_ALL);

    global $db;
    global $log;

    Logger::configure('auto_offset_logproperties.xml');

    $db = require '../../protected/config/db.php';

    $log = new sabiaLogger("auto_offset_logger");
    $offsetObj = new ScaleParams($log);

    $arLabArray = array();
    $arAnalyzerArray = array();
    $i = 0;

    $arLabArrayT = array(array("datetime" => "2019-01-27 10:00:00", "order" => 6, "value" => 13.10),
//                    array("datetime" => "2019-01-27 09:00:00","order" => 5,"value" => 13.09),
        array("datetime" => "2019-01-27 08:00:00", "order" => 4, "value" => 13.08),
        array("datetime" => "2019-01-27 07:00:00", "order" => 3, "value" => 13.07),
//                    array("datetime" => "2019-01-27 06:00:00","order" => 2,"value" => 13.06),
        array("datetime" => "2019-01-27 05:00:00", "order" => 2, "value" => 13.05),
        array("datetime" => "2019-01-27 04:00:00", "order" => 2, "value" => 13.04),
        array("datetime" => "2019-01-27 03:00:00", "order" => 2, "value" => 13.03),
        array("datetime" => "2019-01-27 02:00:00", "order" => 2, "value" => 13.02),
        array("datetime" => "2019-01-27 01:00:00", "order" => 1, "value" => 13.01));


    $settings = array("element_name" => "Al2O3", "min" => 1.6,
        "max" => 6.9, "diff" => 0.5, "correction_pct" => 0.6,
        "last_calibration_time" => "2019-01-27 10:00:00", "current_value" => "-0.2"
        , "useWeightedSum" => 1
        , "useSDtoRemoveOutlier" => 0
        , "useAvgOffsetMeanToRemoveOutlier" => 0
        , 'max_offset_change' => 3
        , "current_value" => 0.06
        , "correction_percentage_till_cron" => 0.8
        , "actionable_current_deviation" => 0.6
//    ,"useAvgOffsetMeanToRemoveOutlier" =>1
    );

    $arLabArray[$i] = array(4.934);
    $arAnalyzerArray[$i++] = array(2.834);

    $n = $i;
    for ($i = 0; $i < $n; $i++) {
        $arLab = $arLabArray[$i];
        $arAnalyzer = $arAnalyzerArray[$i];
        $offset = $offsetObj->calculateOffset($arLab, $arAnalyzer, $settings);
        echo "Lab";
        var_dump($arLab);
        echo "analyzer";
        var_dump($arAnalyzer);
        echo "offset";
        var_dump($offset);
        echo "===============\n";
    }
}

if (defined('OLD_CODE')) {

    function linearRegressionBasic($x, $y) {

        $n = count($x);     // number of items in the array
        $x_sum = array_sum($x); // sum of all X values
        $y_sum = array_sum($y); // sum of all Y values

        $xx_sum = 0;
        $xy_sum = 0;

        for ($i = 0; $i < $n; $i++) {
            $xy_sum += ( $x[$i] * $y[$i] );
            $xx_sum += ( $x[$i] * $x[$i] );
        }

        // Slope
        $slope = ( ( $n * $xy_sum ) - ( $x_sum * $y_sum ) ) / ( ( $n * $xx_sum ) - ( $x_sum * $x_sum ) );

        // calculate intercept
        $intercept = ( $y_sum - ( $slope * $x_sum ) ) / $n;

        return array(
            'slope' => $slope,
            'intercept' => $intercept,
        );
    }

    function linearRegressionWithR2($x, $y) {

        // calculate number points
        $n = count($x);

        // ensure both arrays of points are the same size
        if ($n != count($y)) {
            trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);
        }

        // calculate sums
        $x_sum = array_sum($x);
        $y_sum = array_sum($y);
        $xx_sum = 0;
        $xy_sum = 0;
        $yy_sum = 0;

        for ($i = 0; $i < $n; $i++) {
            $xy_sum += ($x[$i] * $y[$i]);
            $xx_sum += ($x[$i] * $x[$i]);
            $yy_sum += ($y[$i] * $y[$i]);
        }

        // calculate slope
        $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

        // calculate intercept
        $b = ($y_sum - ($m * $x_sum)) / $n;

        // calculate r
        $r = ($xy_sum - ((1 / $n) * $x_sum * $y_sum)) /
                (sqrt((($xx_sum) - ((1 / $n) * (pow($x_sum, 2)))) * (($yy_sum) - ((1 / $n) * (pow($y_sum, 2))))));

        $r2 = $r * $r;

        // return result
        return array("m" => $m, "b" => $b, "r" => $r, "r2" => $r2);
    }

    function findMeanIndex($array, $value) {

        $n = count($array);
        $idx = 0;
        for ($i = 0; $i < $n; $i++) {

            if ($array[$i] == $value) {
                $idx = $i;
                break;
            }
        }
        return $idx;
    }

    function findMeanValueAndIndex($array, $value) {

        $n = count($array);
        $idx = 0;
        for ($i = 0; $i < $n; $i++) {

            if ($array[$i] == $value) {
                $idx = $i;
                break;
            }
        }
        return array("idx" => $idx, "val" => $value);
    }

    function findDiffBetweenTwoArrays($arAnalyzer, $arLab) {
        $n = count($arAnalyzer);
        for ($i = 0; $i < $n; $i++) {
            $diffArray[$i] = $arAnalyzer[$i] - $arLab[$i];
        }
        return $diffArray;
    }

    function findMeanDiff($array, $average) {
        $meanArray = array();
        $n = count($array);

        for ($i = 0; $i < $n; $i++) {
            $value = abs($array[$i] - $average);
            $meanArray[$i] = $value;
        }

        return $meanArray;
    }

    function mergeAll($arAnalyzerMeanDiffAbs, $arLabMeanDiffAbs, $arAnalyzerLabDiff) {
        $mergedArray = array();
        $n = count($arAnalyzerMeanDiffAbs);

        for ($i = 0; $i < $n; $i++) {
            $mergedArray[$i] = $arAnalyzerMeanDiffAbs[$i] + $arLabMeanDiffAbs[$i] + $arAnalyzerLabDiff[$i];
        }

        return $mergedArray;
    }

    function findMinErrorUsingMeanAndAgregation($arAnalyzer, $arLab) {

        $idx = -1;
        $nextPop = 0;

        $avgAnalyzer = array_sum($arAnalyzer) / count($arAnalyzer);
        $avgLab = array_sum($arLab) / count($arLab);

        $arAnalyzerMeanDiffAbs = $this->findMeanDiff($arAnalyzer, $avgAnalyzer);
        $arLabMeanDiffAbs = $this->findMeanDiff($arLab, $avgLab);
        $arAnalyzerLabDiff = $this->findDiffBetweenTwoArrays($arAnalyzer, $arLab);

        switch ($nextPop) {
            //first delete from integrated array
            case 0:
                $allIntegratedArray = $this->mergeAll($arAnalyzerMeanDiffAbs, $arLabMeanDiffAbs, $arAnalyzerLabDiff);
                $meanArray = $allIntegratedArray;
                rsort($meanArray);
                $n = count($meanArray);
                $topIdx = $this->findMeanIndex($allIntegratedArray, $meanArray[0]);
                //$bottomIdx = $this->findMeanIndex($allIntegratedArray, $meanArray[$n - 1]);
                $idx = $topIdx;
                $nextPop = 1;
                break;
            case 1:
                $meanArray = $arAnalyzerMeanDiffAbs;
                rsort($meanArray);
                $value1 = $this->findMeanValueAndIndex($arAnalyzerMeanDiffAbs, $meanArray[0]);

                $meanArray = $arLabMeanDiffAbs;
                rsort($meanArray);
                $value2 = $this->findMeanValueAndIndex($arLabMeanDiffAbs, $meanArray[0]);

                if ($value1["val"] > $value2["val"])
                    $idx = $value1["idx"];
                else
                    $idx = $value2["idx"];

                $nextPop = 1;
                break;
        }

        return $idx;
    }

    function buildTempArray($arArray, $idxArray) {

        $tempArray = array();

        $n = count($idxArray);
        for ($i = 0; $i < $n; $i++) {
            $tempArray[$i] = $arArray[$idxArray[$i]];
        }

        return $tempArray;
    }

    function getGainOffsetUsingPerm($arAnalyzer, $arLab, /* $existingScale, */ $minErrorLimit) {

        //check for valid values
        $anaSum = array_sum($arAnalyzer) / count($arAnalyzer);
        $labSum = array_sum($arLab) / count($arLab);

        if (!$anaSum || !$labSum) {
            $response = array("found" => false);
            // Yii::debug('**********************', var_export($category,true));
            Yii::error('Blank array sent, check lab or analyzer samples');
            return $response;
        }

        $sampleValues = count($arAnalyzer);

        if ($sampleValues < 2 && $sampleValues > 56) {
//            $log->fatal("Sample set should be either 10 or 15");
            Yii::error('Sample value $sampleValues not in range (10-15)');
            return null;
        }

        $index = (string) $sampleValues;
        $permIndexArray = PermutationsArray::getPermIdxArray($index);

        $lrResult = 0;
        $maxlrResult["r2"] = 0;

        //remove existing scale
        /*      for ($i = 0; $i < $sampleValues; $i++)
          $arAnalyzer[$i] = $arAnalyzer[$i] - ($arAnalyzer[$i] * $existingScale['gain'] + $existingScale['offset']);
         */
        $n = count($permIndexArray);
        for ($i = 0; $i < $n; $i++) {

            //build temp array
            $tempArAnalyzer = $this->buildTempArray($arAnalyzer, $permIndexArray[$i]);
            $tempArLab = $this->buildTempArray($arLab, $permIndexArray[$i]);

            $lrResult = $this->linearRegressionWithR2($tempArAnalyzer, $tempArLab);

            if ($lrResult["r2"] > $maxlrResult["r2"]) {
                $maxlrResult = $lrResult;
            }
        }

        if ($maxlrResult["r2"] > $minErrorLimit) {
//            $log->trace("found gain and offset");

            $gain = round($maxlrResult["m"], 2);
            $offset = round($maxlrResult["b"], 2);
            $r = round($maxlrResult["r"], 2);
            $r2 = round($maxlrResult["r2"], 2);

            if ($gain == 0) {
                $test = 1;
            }

            $response = array("found" => true, "gain" => $gain, "offset" => $offset, "r" => $r, "r2" => $r2);
            // Yii::debug('**********************', var_export($response,true));
        } else {
            $r2 = round($maxlrResult["r2"], 2);
            $response = array("found" => false, "r2" => $r2, "error" => "Not able to calculate gain/offset from selected params");
            Yii::error('"Not able to calculate gain/offset from selected params');
        }

        return $response;
    }

    function getGainOffsetUsingMeanMethod($log, $arAnalyzer, $arLab) {

        $n = count($arAnalyzer);
        $lrResult = 0;

        for ($i = 0; ( $i < $n / 3 && $lrResult["r2"] < 0.70); $i++) {

            $lrResult = $this->linearRegressionWithR2($arAnalyzer, $arLab);

            if ($lrResult["r2"] > 0.69) {
                $found = true;
                break;
            }

            $idx = $this->findMinErrorUsingMeanAndAgregation($arAnalyzer, $arLab);

            unset($arAnalyzer[$idx]);
            unset($arLab[$idx]);

            $arAnalyzer = array_values($arAnalyzer);
            $arLab = array_values($arLab);
        }

        if ($found) {
            $log->debug("found gain and offset");
            $response = array("found" => true, "gain" => $lrResult["m"], "offset" => $lrResult["b"], "r" => $lrResult["r"], "r2" => $lrResult["r2"]);
        } else
            $response = array("found" => false);

        return $response;
    }

    function getOffsetsRemovingOuliers($arLab, $arAnalyzer) {

        Logger::calibLogger('log', 'getOffsetsRemovingOutliers starts', array($arLab, $arAnalyzer));

        $offset = 0;
        //check for valid values
        $anaSum = array_sum($arAnalyzer) / count($arAnalyzer);
        $labSum = array_sum($arLab) / count($arLab);

        if (!$anaSum || !$labSum) {
            $response = array("found" => false);
            // Yii::debug('**********************', var_export($category,true));
            //   Yii::error('Blank array sent, check lab or analyzer samples
            Logger::calibLogger('log', 'Blank array sent, check lab or analyzer samples', 'Blank array sent, check lab or analyzer samples', '');

            return $response;
        }

        $countSamples = count($arAnalyzer);

        $meanAnalyzer = array_sum($arAnalyzer) / count($arAnalyzer);
        $meanLab = array_sum($arLab) / count($arLab);

        $sdevAnalyzer = LabUtility::stdDeviation($arAnalyzer);
        $sdevLab = LabUtility::stdDeviation($arLab);

        Logger::calibLogger('log', 'Std Deviation for Analyzer array', array($sdevAnalyzer));
        Logger::calibLogger('log', 'Std Deviation for Lab array', array($sdevLab));

        //build temp array from both arrays
        $sumOffset = 0;

        foreach ($arLab as $key => $value) {
            $offsetT = $arLab[$key] - $arAnalyzer[$key];
            $combineArray[] = array('lab' => $arLab[$key], 'analyzer' => $arAnalyzer[$key], 'valid' => 1, 'offset' => $offsetT);
            $sumOffset += $offsetT;
        }
        $countOffset = count($combineArray);
        $meanOffset = $sumOffset / $countOffset;

        // run the loop 
        foreach ($combineArray as $key => $item) {

            $tolUp = $meanLab + $sdevLab;
            $tolDown = $meanLab - $sdevLab;
            if ($item['lab'] > $tolUp || $item['lab'] < $tolDown) {
                $item['valid'] = 0;
                Logger::calibLogger('log', 'Lab item dropped, item, tolUp and tolDown', array($item['lab'], $tolUp, $tolDown));
            }

            $tolUp = $meanAnalyzer + $sdevAnalyzer;
            $tolDown = $meanAnalyzer - $sdevAnalyzer;
            if ($item['analyzer'] > $tolUp || $item['analyzer'] < $tolDown) {
                $item['valid'] = 0;
                Logger::calibLogger('log', 'Analyzer item dropped, item, tolUp and tolDown', array($item['analyzer'], $tolUp, $tolDown));
            }
            //delete all invalid values
            if ($item['valid'] == 1)
                $finalarray[] = $item['offset'];
        }

        Logger::calibLogger('log', 'Final Array(round 1 <> standard deviation) after removing outliers is ', $finalarray);


        $tempCount = count($finalarray);

        $tempCount = 0;

        //the first method failed, so lets use next method
        if ($tempCount == 0) {
            //find mean of differences
            foreach ($combineArray as $key => $item) {

                $item['valid'] = 1;
                //mar all items which are > than offset as invalid
                if ($item['offset'] > $meanOffset)
                    $item['valid'] = 0;

                //delete all invalid values
                if ($item['valid'] == 1)
                    $finalarray[] = $item['offset'];
            }
        }

        Logger::calibLogger('log', 'Final Array(round 2 > mean_offset) after removing outliers is ', $finalarray);

        $tempCount = count($finalarray);

        if ($tempCount > 2)
            $offset = array_sum($finalarray) / $tempCount;
        else {
            $offset = 0;
            Logger::calibLogger('log', 'Valid Records not found, no of records is $tempCount', $finalarray);
        }

        $offset = round($offset, 2);
        Logger::calibLogger('log', 'getOffsetsRemovingOutliers ends , sending offset ', array($offset));

        return $offset;
    }

    function consecutiveRecords($arAnalyzer/* $arLab, $arAnalyzer, $settings */) {

        //find if records are consecutive
        $count = count($arAnalyzer);
        $i = 0;
        $continous = true;
        while (($i + 1) < $count) {
            $timeCurrent = strtotime($arAnalyzer[$i]['datetime']);
            $timePrevious = strtotime($arAnalyzer[$i + 1]['datetime']);
            $hourdiff = ($timeCurrent - $timePrevious) / 3600;
            if (($hourdiff == 1 || $hourdiff == 2 || $hourdiff == 3) && $continous) {
                //$valid = 1;
                echo "valid: ";
            } else {
                //$valid = 0;
                echo "invalid: ";
                $continous = false;
            }
            echo $i . " :" . $hourdiff . "<br/>";
            $i++;
        }
    }

}
?>
