<?php

//ob_start();    // buffer any output until the end

//----------------------------------------------------------------------------------------
// Class definition and functions for the analysis object
//----------------------------------------------------------------------------------------
// SABIA Proprietary
// Copyright (c) 2003-2007, 2008, 2009 by SABIA, Inc.
//
//! \file
//! add this to cron
//! */5 * * * * php -f /var/www/html/helios2/daemon/daemonMain.php > /var/www/html/helios2/daemon/runlog/`date +\%Y\%m\%d\%H\%M\%S`-cron.log 2>&1
//! */5 * * * * php -f /var/www/html/helios2/daemon/daemonMain.php >> /var/www/html/helios2/daemon/runlog/`date +\%Y-\%m-\%d`-cron.log 2>&1
//! 
//! 
//! 
//! 
//
require_once '../common/log4php/Logger.php';
require_once '../common/sabiaLogger.php';
require_once '../common/utils.php';
require_once 'ScaleParams_cmd.php';

//GLOBAL Declare
global $log;
global $db;

Logger::configure('auto_offset_logproperties.xml');
$db = require '../../protected/config/db.php';

main();
echo ("normal exit");
exit("normal exit");



function test($log){
    $offsetObj = new ScaleParams($log);

    $arLabArray = array();
    $arAnalyzerArray = array();
    $i = 0;

    $arLabArrayT =array(array("datetime" => "2019-01-27 10:00:00","order" => 6,"value" => 13.10),
    //                    array("datetime" => "2019-01-27 09:00:00","order" => 5,"value" => 13.09),
                        array("datetime" => "2019-01-27 08:00:00","order" => 4,"value" => 13.08),
                        array("datetime" => "2019-01-27 07:00:00","order" => 3,"value" => 13.07),
    //                    array("datetime" => "2019-01-27 06:00:00","order" => 2,"value" => 13.06),
                        array("datetime" => "2019-01-27 05:00:00","order" => 2,"value" => 13.05),
                        array("datetime" => "2019-01-27 04:00:00","order" => 2,"value" => 13.04),
                        array("datetime" => "2019-01-27 03:00:00","order" => 2,"value" => 13.03),
                        array("datetime" => "2019-01-27 02:00:00","order" => 2,"value" => 13.02),
                        array("datetime" => "2019-01-27 01:00:00","order" => 1,"value" => 13.01));


    $settings = array("element_name" => "Al2O3","min" => 2.6,
                "max" => 3.9,"diff" => 0.5, "correction_pct" => 0.6, 
        "last_calibration_time"=> "2019-01-27 10:00:00", "current_value"=> "-0.2"
        ,"max_offset_change"=>1.5
        ,"useWeightedSum"=>1
        ,"useSDtoRemoveOutlier" =>0
        ,"useAvgOffsetMeanToRemoveOutlier" => 0
        ,"current_value" => 0.06
    //    ,"useAvgOffsetMeanToRemoveOutlier" =>1
        );

    $arLabArray[$i] = array(2.934,2.911,2.897);
    $arAnalyzerArray[$i++] = array(2.834,2.821,2.797);

    $n = $i;
    for ($i = 0; $i < $n; $i++) {
        $arLab = $arLabArray[$i];
        $arAnalyzer = $arAnalyzerArray[$i];
    //    $offset = $offsetObj->getOffsetsRemovingOuliers($arLab, $arAnalyzer);
    //    $offset = $offsetObj->consecutiveRecords($arLabArrayT);
        $offset = $offsetObj->getOffsetsRemovingOuliers2($arLab, $arAnalyzer,$settings);
        // $offset =  $offsetObj->getGainOffsetUsingPerm($arAnalyzer, $arLab, $existingScale, $minErrorLimit);
        echo "Lab";
        var_dump($arLab);
        echo "analyzer";
        var_dump($arAnalyzer);
        echo "offset";
        var_dump($offset);
        echo "===============\n";
    }
}

class AutoOffset {

    public $log;

    public function __construct($log = null) {
        $this->log = $log;
        return;
    }

    public function read_user_calibration() {
        
    }

    public function save_user_calibration() {
        
    }    
    
    public function get_settings() {
        
    }    
    
    public function get_time_range() {
        
    }    
    
    public function get_lab_entries() {
        
    }    
    
    public function get_analyzer_entries() {
        
    }    
    
    public function get_analyzer_avgs() {
        
    }
    
    
}

//------------------------------------------------------------------------------
// main routine
//------------------------------------------------------------------------------
function main()
{
    echo ("main enter\n");
    $log = new sabiaLogger();
    
    $mysqlCon = connectDB($log);

    $log->setDbCon($mysqlCon);
    $log->trace("auto offset calculator daemon started !");

    test($log);
    
    $auto_offset_obj = new AutoOffset($log);
    //1a. read current offset calibrations
    $old_offsets = $auto_offset_obj->read_user_calibration();
    $settings = $auto_offset_obj->get_settings();

    //2. got back 6 hours of whatever time, and get start and end time range
    $time_range = $auto_offset_obj->get_time_range();
    //3. find lab entries for start and end time range
    $lab_records = $auto_offset_obj->get_lab_entries($time_range);
    //4. find analyzer entries for the corresponding lab entries start and end time
    //copy analyzer values in temp array for TPH > min TPH
    //substract offset from recorded values from temp array
    //
    $raw_analyzer_records = $auto_offset_obj->get_analyzer_entries($time_range);
    //5. for lab records timings create analyzer hourly averages
    $analyzer_records = $auto_offset_obj->get_analyzer_avgs($lab_records);
   
    //we have now lab records table, analyer avg table, settings table    
    //6. call offset calculatr with lab_records,Analyzer_records,Settings per element
    $offset_calc_obj = new ScaleParams($log);
    $response = $offset_calc_obj->getOffsetsRemovingOuliers2($lab_records, $analyzer_records, $settings);
    
    //response if array of old values and new values
    $new_offset = $response['offset'];
    
    //7. if save is enabled, save to default.cfg
    $result = $auto_offset_obj->save_user_calibration($new_offset);   
    
    disconnectDB($log, $mysqlCon);
    echo ("main exit");
}	

?>
