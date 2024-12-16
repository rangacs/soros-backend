<?php

//require 'define.php';
//------------------------------------------------------------------------------
//! 
//------------------------------------------------------------------------------
class OffsetObject {

    public $eleName;
    public $currentSettings;
    public $newCalibSettings;
    public $rangeSettings;
    public $analysisObject;
    public $formData;
    public $processingTime;
    public $samplingTime;
    public $currentTimeTick;
    public $currentTimeString;
    public $maxValidTime;
    public $validTimeString;
    public $dayDiff;
    public $labFileIds;
    public $labRecords;
    public $labTimeStamps;
    public $lookUp;
    public $labController;
    public $labUtility;
    public $calibConfig;
    public $calibFile;
    public $existingOffsets;
    public $analysisAvg;
    public $labEntryTime;

    public function __construct($eleName) {

        $this->eleName = $eleName;
        $this->labEntryTime = date('Y-m-d H:i:s');
        $query = 'select * from ac_settings where element_name = "' . $this->eleName . '"';

        $results = Yii::app()->db->createCommand($query)->queryRow();

        $this->rangeSettings = $results;

        //TODOP
        //read from db, config
        $useWeightedSum = 1;
        $useSDtoRemoveOutlier = 0;
        $useAvgOffsetMeanToRemoveOutlier = 0;
        $this->rangeSettings['eleName'] = $eleName;
        $this->rangeSettings['useWeightedSum'] = $useWeightedSum;
        $this->rangeSettings['useSDtoRemoveOutlier'] = $useSDtoRemoveOutlier;
        $this->rangeSettings['useAvgOffsetMeanToRemoveOutlier'] = $useAvgOffsetMeanToRemoveOutlier;
		
        $Query = "select * from rm_settings where varName = 'AUTO_CALIB_REAL_VALUES'";
        $Commnad = Yii::app()->db->createCommand($Query);
        $Result = $Commnad->queryRow();
        $this->rangeSettings['useRealValues'] = $Result['varValue'];

        $controller = Yii::app()->createController('Labdata');

        $this->labController = $controller[0];
        $this->currentTimeTick = time();

        //Sampling and processing time
        $samplingTimeQuery = "select * from rm_settings where varName = 'LAB_HISTORY_SAMPLING_TIME'";
        $samplingCommnad = Yii::app()->db->createCommand($samplingTimeQuery);
        $samplingResult = $samplingCommnad->queryRow();
        $this->samplingTime = ($samplingResult['varValue'] * 60);

        $fallBackTimeQuery = "select * from rm_settings where varName = 'LAB_HISTORY_FALLBACK_TIME'";
        $fallBackCommnad = Yii::app()->db->createCommand($fallBackTimeQuery);
        $fallBackResult = $fallBackCommnad->queryRow();
        $this->processingTime = ($fallBackResult['varValue'] * 60);

        //Current Elment calibration settings 

        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILE;
        $cfg->load($calAdjust);
        $cfg->setPath("/USRCAL");
        $calibFile = $cfg->readEntry("lib_file");

        $this->calibFile = $calibFile;
        $this->calibConfig = new ConfigFile();
        $this->calibConfig->load($calibFile);

        if (!file_exists($calibFile))
            return;

        $this->calibConfig->setPath("/" . $this->eleName);

        $cSettings['type'] = $this->calibConfig->readEntry("type", 'output');
        $cSettings['offset'] = $this->calibConfig->readEntry("offset", 0.0);
        $cSettings['gain'] = $this->calibConfig->readEntry("gain", 1);

        $this->currentSettings = $cSettings;
    }

    public function setLabEntryTime($labEntryTime){
        
        $timeTick = strtotime($labEntryTime);
        $this->labEntryTime = date('Y-m-d H:i:s',$timeTick);
    }
    public function getValidLabRecords() {


        $validTime = $this->getValidTime();
        $this->getLabFilesToProcess();

        $this->populateTableFromLabFiles($this->labFileIds);



        $labSql = 'select *,lab_data_id AS id from lab_history_data  where EndTime >="' . $this->validTimeString . '" and EndTime <= "'. $this->currentTimeString .'" order by EndTime DESC';
                
        //Logger::calibLogger(Logger::INFO,'Query', $labSql);

        Logger::calibLogger(Logger::INFO, $this->eleName . ' Fetching Lab Records from time ', $this->validTimeString." to ".$this->currentTimeString);

        $results = Yii::app()->db->createCommand($labSql)->queryAll();

        //Logger::calibLogger(Logger::INFO,'Result', $results);

        $noOfRecords = count($results);


        Logger::calibLogger(Logger::INFO, $this->eleName . ' Total Lab Records Found', "" . $noOfRecords);

        
        $changeOnSingleRecord = 0;

        $sQuery = "select * from rm_settings where varName = 'AUTO_CALIB_CHANGE_ON_SINGLE_CURRENT_RECORD'";
        $command = Yii::app()->db->createCommand($sQuery);
        $result = $command->queryRow();
        $changeOnSingleRecord = $result['varValue'];

        if($changeOnSingleRecord == 1)
            $MIN_RECORDS_FOR_AUTOCAL = 1;
        else
            $MIN_RECORDS_FOR_AUTOCAL = 2;

        if ($noOfRecords >= $MIN_RECORDS_FOR_AUTOCAL) {
            $this->labRecords = $this->filterLabRecords($results);
            $noOfRecords = $this->getAnalysisData();
            return $noOfRecords;
        } else
            return false;
    }

    public function filterLabRecords($records) {

        $filteredRecords = array();

        //bad records
        //TODO: PAW2, need to fix this
        $setting_filter_bad_records = 0;
        /*
        $query = "select * from rm_settings where varKey = 'ANALYZER_FILTER_BAD_RECORDS' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue'])
            $setting_filter_bad_records = (int) $result['varValue'];
        */
        
        //use _real
        $useRealValues = 0;
        $query = "select * from rm_settings where varKey = 'AUTO_CALIB_REAL_VALUES' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue'])
            $useRealValues = (int) $result['varValue'];

        foreach ($records as $labRecord) {

            //select count(TPH), avg(TPH) from analysis_a1_a2_blend ;

            $endTime = $labRecord['EndTime'];

            $hours = date('H:i');

            $hourAr = explode(':', $hours);


            $tmArLab = array_map('intval', $hourAr);

            //06:17
            if ($tmArLab[1] > 0) {
                $starttimeStr = $tmArLab[0] + 1;
                $endtimeStr = $tmArLab[0];
            } else {
                $starttimeStr = $tmArLab[0] - 1;
                $endtimeStr = $tmArLab[0]; //17:00
            }

            $offsetMinTph = Yii::app()->db->createCommand('select * from rm_settings where varKey="AUTO_OFFSET_HOURLY_MIN_TPH"')->queryRow();

            $endTimeTick = strtotime($endTime) - $this->processingTime;
            $startTimeTick = $endTimeTick - $this->samplingTime;

            if ($useRealValues)
                $fQuery = "select count(TPH) as count , avg(TPH) as AvgTph from analysis_A1_A2_Blend_real where LocalendTime >='" . date('Y-m-d H:i:s', $startTimeTick) . "' AND LocalendTime <= '" . date('Y-m-d H:i:s', $endTimeTick) . "'";
            else
                $fQuery = "select count(TPH) as count , avg(TPH) as AvgTph from analysis_A1_A2_Blend where LocalendTime >='" . date('Y-m-d H:i:s', $startTimeTick) . "' AND LocalendTime <= '" . date('Y-m-d H:i:s', $endTimeTick) . "'";
            //Logger::calibLogger(Logger::INFO,'Query', $fQuery);

            $result = Yii::app()->db->createCommand($fQuery)->queryRow();

            $minTPH = isset($offsetMinTph['varValue']) ? $offsetMinTph['varValue'] : 0;
            
            $autoCalibTestMode = Yii::app()->db->createCommand('select * from rm_settings where varKey="AUTO_CALIB_TEST"')->queryRow();
            if($autoCalibTestMode)
                $minTPH = 0;
            
            $avgTph = isset($result['AvgTph']) ? $result['AvgTph'] : 0;

            $count = $result['count'];
            if ($avgTph >= $minTPH) {
                $filteredRecords[] = $labRecord;
                //Logger::calibLogger(Logger::INFO, 'Inserted Lab Record', "Record " . $starttimeStr . ":00 -" . $endtimeStr . ":00 Dropped, TPH = " . $result['AvgTph'] . " => Required TPH =" . $minTPH . "");
            } else {
                Logger::calibLogger(Logger::INFO, 'Dropped Lab Record', "Record " . $starttimeStr . ":00 -" . $endtimeStr . ":00 Dropped, TPH = " . $result['AvgTph'] . " => Required TPH =" . $minTPH . "");
            }
            
            /*
            //Filter Here
            if ($setting_filter_bad_records){
                //find $anRecord
                //TODO: PAW2, find anrecord
                $rowValid = DashHelper::validateAndSetAnalyzerRecordUsingRange($anRecord);
                if($rowValid){
                    $filteredRecords[] = $labRecord;
                }
                
                Logger::calibLogger(Logger::INFO, 'Dropped Bad Lab Record', "Record " . $starttimeStr . ":00 -" . $endtimeStr . ":00 Dropped, TPH = " . $minTPH . " => Bad Record");
            }
            else
                $filteredRecords[] = $labRecord;
            */

            $this->labRecords = $filteredRecords;
        }

        return $filteredRecords;
    }

    public function getLabFilesToProcess() {



        if ($this->dayDiff > 0) {

            $query = "select * from lab_data_history where temp_name = 'lab_data_" . date('Y_m_d', time()) . "'";

            $result = Yii::app()->db->createCommand($query)->queryRow();

            if (!empty($result)) {

                $this->labFileIds[] = $result['lab_hist_id'];
                //echo $model->lab_hist_id;
            }

            $query2 = "select * from lab_data_history where temp_name = 'lab_data_" . date('Y_m_d', strtotime($this->validTimeString)) . "'";

            $result2 = Yii::app()->db->createCommand($query2)->queryRow();

            if (!empty($result2)) {


                $this->labFileIds[] = $result2['lab_hist_id'];
            }
        } else {
            $query = "select * from lab_data_history where temp_name = 'lab_data_" . date('Y_m_d', time()) . "'";

            $result = Yii::app()->db->createCommand($query)->queryRow();

            if (!empty($result)) {


                $this->labFileIds[] = $result['lab_hist_id'];
                //echo $model->lab_hist_id;
            }
        }
    }

    public function populateTableFromLabFiles($labFileIds = array()) {



        if (count($labFileIds) == 0)
            return false;


        $schemaCreated = false;

        foreach ($labFileIds as $labHistId) {


            $historyQuery = "SELECT * FROM lab_data_history where lab_hist_id ={$labHistId} ";

            $oDbConnection = Yii::app()->db;
            $oCommand = $oDbConnection->createCommand($historyQuery);
            $labHistoryEntry = $oCommand->queryRow(); // Run query and get all results in a CDbDataReader
            $historyData = json_decode($labHistoryEntry['data']);
            $templateModel = LabTemplate::model()->find('template_id = :template_id', array(':template_id' => $labHistoryEntry['template_id']));
            $columnReader = array();

            foreach ($historyData->elements as $col) {

                $columnReader[] = $this->labController->normalise($col);
            }
            $oCDbDataReader = $historyData->ele_data;

            $query = "select * from rm_settings where varKey = 'profile' ";
            $result = Yii::app()->db->createCommand($query)->queryRow();
            $profile = $result['varValue'];


            //create temp table
            $tData = str_getcsv($templateModel['content'], "\n");

            $tempInfo = $this->labController->parseCsvfile($tData);

            if (empty($tempInfo['col'])) {
                // $this->sendFailedResponse('Invalid file format');
                Logger::calibLogger(Logger::WARN, 'unable to create temp data table', 'Unable to create temp data table');
            }
            $tempTableCreatequery = $this->labController->buildCreateTableQuery($tempInfo, 'lab_history_data');


            if (!$schemaCreated) {
                $result = Yii::app()->db->createCommand($tempTableCreatequery)->execute();

                $schemaCreated = true;
            }

            $tableSchema = Yii::app()->db->schema->getTable('lab_history_data');


            $lData = str_getcsv($labHistoryEntry['raw_data'], "\n");
            $tableInfo = $this->labController->parseCsvDataFile($lData, $templateModel);
            $labDataquery = $this->labController->buildInsertDataQuery($tableInfo, 'lab_history_data');


            try {
                $result = Yii::app()->db->createCommand($labDataquery)->execute();
            } catch (Exception $ex) {
                // $this->sendFailedResponse('Invalid data');
                Logger::calibLogger(Logger::WARN, 'unable to create temp data table', 'Unable to create temp data table');
            }
        }//end of foreach 
    }

    public function getAnalysisData() {

        $labTimeStamp = LabUtility::getColumn($this->labRecords, 'EndTime');

        $noOfRecords = count($labTimeStamp);

        if ($noOfRecords == 0) {
            Logger::calibLogger(Logger::INFO, $this->eleName . ' Error: Filtered Lab Records not found', 'for the time range' );
            return false;
        }

        Logger::calibLogger(Logger::INFO, $this->eleName . ' Filtered Lab Records(on TPH) are ' , $noOfRecords );

        $useRealValues = 0;
        $query = "select * from rm_settings where varKey = 'AUTO_CALIB_REAL_VALUES' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue'])
            $useRealValues = (int)$result['varValue'];
        
        if($useRealValues == 1)
            $this->analysisObject = new AnalysisHistoryFactory($labTimeStamp, 'analysis_A1_A2_Blend_real');
        else
            $this->analysisObject = new AnalysisHistoryFactory($labTimeStamp, 'analysis_A1_A2_Blend');
        
        $anRecords     = $this->analysisObject->getElementRecords($this->rangeSettings);
        $this->analysisAvg   = $this->analysisObject->calculateEleAvg($anRecords,$this->eleName);
        $noOfAnRecords = count($this->analysisAvg);
//
        if ($noOfAnRecords <= 0) {
            Logger::calibLogger(Logger::INFO, $this->eleName . 'Error: Analysis Records not found', 'for corresponding lab records');
            return false;
        }
        Logger::calibLogger(Logger::INFO, $this->eleName . ' Corresponding Analyzer Records found ' , $noOfAnRecords);

        return true;
    }

    public function calculateScale() {


        $scaleParamsObj = new ScaleParams();

        $this->calibConfig->setPath("/" . $this->eleName);

        //Getting Lab data and analysis data for element

        $tmArLab = array_slice(LabUtility::getColumn($this->labRecords, $this->eleName), 0, 15);
        $arLab = array_map('floatval', $tmArLab);
        
        
        $arAnalyzer = empty($this->analysisAvg ) ? array() :array_values($this->analysisAvg);


        //$settings = array("element_name" => "SiO2","min" => 12.8,"max" => 14.1,"difference" => 0.1, "correction_pct" => 0.6, "last_calibration_time"=> "2019-01-27 10:00:00", "current_value"=> "-0.2");

        $cOffsetSettings = $this->rangeSettings;
        $cOffsetSettings['current_value'] = $this->calibConfig->readEntry("offset", 0.0);
        $cOffsetSettings['useWeightedSum'] = 1;
        $cOffsetSettings['useSDtoRemoveOutlier'] = 0;
        $cOffsetSettings['useAvgOffsetMeanToRemoveOutlier'] = 0;
        $cOffsetSettings['from_ancompare'] = 0;


        Logger::calibLogger(Logger::INFO, $this->eleName . " Settings ", $cOffsetSettings);
        Logger::calibLogger(Logger::INFO, $this->eleName . ' Sending Analyzer Records, Array is', $arAnalyzer);
        Logger::calibLogger(Logger::INFO, $this->eleName . ' Sending Lab Records, Array is', $arLab);


        //Get flag here
        $testAuto = false;
        $query = "select * from rm_settings where varKey = 'AUTO_CALIB_TEST' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue'])
            $testAuto =  $result['varValue'];

        if($testAuto){
         
            $arAnalyzer = $this->getTestData($arLab, $cOffsetSettings);
        }
        
        $offsetResultArray = $scaleParamsObj->calculateOffset($arLab, $arAnalyzer, $cOffsetSettings);
      

        Logger::calibLogger(Logger::INFO, $this->eleName . " OffsetCalculator returned", $offsetResultArray);
        return $offsetResultArray;
    }

    public function saveAutoCalib() {


        $this->calibConfig->setPath("/" . $this->eleName);

        $currentOffset = $this->calibConfig->readEntry('offset');

        if (isset($this->newCalibSettings['offset'])) {
            $this->calibConfig->writeEntry('offset', $this->newCalibSettings['offset']);
            //save part yes



            $lastTimeQquery = "update ac_settings set  last_updated  ='" . date('Y-m-d H:i:s') . "' where element_name ='" . $this->eleName . "'";

            $this->calibConfig->flush();
            Yii::app()->db->createCommand($lastTimeQquery)->query();

            $logMessage = $this->eleName . " offset changed  old offset => $currentOffset new offset " . $this->newCalibSettings['offset'];
            Logger::calibLogger(Logger::INFO, $logMessage . " offset changed", $this->newCalibSettings['offset']);
        }
    }
    
    
    public function getTestData($labdata,$offset){
        
        //$labdata
        
        $newAnData = array();
        
        foreach($labdata as $value){
            
            $newAnData[] = 0;
            
        }
        
        
        return $newAnData;
        
    }

    public function getValidTime() {

        //get from the selected time
        $this->currentTimeTick = strtotime($this->labEntryTime);
        $this->currentTimeString = date('Y-m-d H:i:s',strtotime($this->labEntryTime));

        
        
        //default db duration is 6 hours
        $dbDuration = 6;

        $query = "select * from rm_settings where varKey = 'AUTO_CALIB_LOOKBACK_DURATION' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue'])
            $dbDuration = $result['varValue'];

        if ($dbDuration) {
            $duration = (int) $dbDuration;
            if (!$duration) {
                $duration = 6;
            }
        } else {
            $duration = 6;
        }

        $this->maxValidTime = $this->currentTimeTick - ( $duration * 3600);

        
        $useRealValues = 0;
        $query = "select * from rm_settings where varKey = 'AUTO_CALIB_REAL_VALUES' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue'])
            $useRealValues = (int)$result['varValue'];

        $offsetChangeTick = 0;
        
        if(!$useRealValues)//if not realvalues drop to last changed user calib
            $offsetChangeTick = strtotime($this->rangeSettings['last_updated']);

        if ($offsetChangeTick > $this->maxValidTime) {

            //Put this code here. Later lets try your first.
            $timeTick = strtotime($this->rangeSettings['last_updated']);
            $validTime = $timeTick + ($this->samplingTime);
            $this->validTimeString = date('Y-m-d H:i:s', $validTime);
            Logger::calibLogger(Logger::INFO, $this->eleName . ' finding last calibration time', "found!, calibrated time: " . date('Y-m-d H:i:s', $timeTick));
        } else {

            $this->validTimeString = date('Y-m-d H:i:s', $this->maxValidTime);
            Logger::calibLogger(Logger::INFO, $this->eleName . ' Using real values or No calibration done in last 6 hours', "start time: " . $this->validTimeString);
        }

         $this->getDayDiff();
/*
        $hours = date('H:i', strtotime($this->validTimeString));

        $hourAr = explode(':', $hours);

        $tmArLab = array_map('intval', $hourAr);

        $dateString = date('Y-m-d', strtotime($this->validTimeString));

       
        //06:17
        if ($tmArLab[1] > 0) {
            $starttimeStr = $tmArLab[0] + 1;
            $endtimeStr = $tmArLab[0];

            $celingTime = $dateString . " $starttimeStr:00:00";
        } else {
            //$starttimeStr 	= $tmArLab[0];
            //$endtimeStr 	= $tmArLab[0]-1;//17:00

            $celingTime = $dateString . " $starttimeStr:00:00";
        }

       

        $this->validTimeString = $celingTime;
         */
        //echo 'curretn time'.$this->validTimeString.'celing'.$celingTime ;
        // 
        //valid time 16:01 => 17:0orre0
        //TODOR next ceiling timet 

        /*

          $timeTick = strtotime($this->rangeSettings['last_updated']);	//08:37
          $validTime = $timeTick + ($this->samplingTime);				//09:37
          $this->validTimeString = date('Y-m-d H:i',$validTime) . ":00";  //09:00
          $newTimeString = strtotime($this->validTimeString) + 3600;
          $this->validTimeString = date('Y-m-d H:i',$newTimeString);

         */
       return $this->validTimeString;
    }

    private function getDayDiff() {


        $datetime1 = new DateTime(date('Y-m-d', $this->currentTimeTick));
        $datetime2 = new DateTime(date('Y-m-d', strtotime($this->validTimeString)));
        $interval = $datetime1->diff($datetime2);
        $this->dayDiff = (int) $interval->format('%d');
    }

}
