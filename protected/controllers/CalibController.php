<?php

class CalibController extends BaseController {

    public $layout = 'metronic-column2';

    public function init() {


        $cSettings = UISettings::getConfig(Yii::app()->user->getId());

        if (isset($_GET['langId']) && in_array($_GET['langId'], Yii::app()->params['langArray'])) {
            Yii::app()->language = $_GET['langId'];
        } else if (isset($cSettings['language'])) {
            Yii::app()->language = $cSettings['language'];
        } else {
            Yii::app()->language = 'en';
        }
        $pathinfo = pathinfo(Yii::app()->request->scriptFile);

        $uploaddir = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;

        require($uploaddir . 'defines.php');
    }

    public function performDefault($usrCal) {
        $usrCal->performLoadUsrCal($_SESSION['loadUsrCalDir'], $_SESSION['loadUsrCalFile']);
    }

//
    public function actionView() {

        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILE;

        $cfg->load(CAL_ADJUST_FILE);
        $cfg->setPath("/USRCAL");

        $calibFile = $cfg->readEntry("lib_file");


        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);

        $calibFactory = new ConfigFactory($calibFile);

        $existingScaleList = $calibFactory->getDirNames();

        return $this->render('calib', array('existingScaleList' => $existingScaleList));
    }

    public function actionScale() {

        $cfg = new ConfigFile();
//        $this->doMiscItemsMain();

        $calAdjust = CAL_ADJUST_FILE;

        $cfg->load($calAdjust);

        $cfg->setPath("/USRCAL");
        $calibFile = $cfg->readEntry("lib_file");

        $calibFactory = new ConfigFactory($calibFile);
        return $this->render('submitPr', array('calibFactory' => $calibFactory));
    }

    public function prepareScaleData($lab_hist_id) {

        $historyQuery = "SELECT * FROM lab_data_history where lab_hist_id = '" . $lab_hist_id . "' order by start_time limit 1";

        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand($historyQuery);
        $labHistoryEntry = $oCommand->queryOne(); // Run query and get all results in a CDbDataReader
        $historyData = json_decode($labHistoryEntry['data']);

        $templateModel = LabTemplate::modal()->find('template_id = :template_id', array(':template_id' => $labHistoryEntry['template_id']));

        $columnReader = array();

        foreach ($historyData->elements as $colI) {

            $columnReader[] = $this->normalise($colI);
        }

        //create temp table
        $tData = str_getcsv($templateModel['content'], "\n");
        $tempInfo = $this->parseCsvfile($tData);

        if (empty($tempInfo['col'])) {
            $this->sendFailedResponse('Invalid file format');
        }
        $query = $this->buildCreateTableQuery($tempInfo, 'lab_history_data');

        $result = Yii::app()->db->createCommand($query)->execute();
        $tableSchema = Yii::app()->db->schema->getTable('lab_history_data');

        //Populate temp table 
        //create temp table
        $lData = str_getcsv($labHistoryEntry['raw_data'], "\n");

        $tableInfo = $this->parseCsvDataFile($lData, $templateModel);
        $labDataquery = $this->buildInsertDataQuery($tableInfo, 'lab_history_data');

        try {
            $result = Yii::app()->db->createCommand($labDataquery)->execute();
        } catch (Exception $ex) {
            $this->sendFailedResponse('Invalid data');
        }

        $labHistorData = Yii::app()->db->createCommand('select * from lab_history_data')->queryAll();
        //<------ ----->
        $dataArray = array();

        foreach ($labHistorData as $key => $row) {

            foreach ($row as $j => $value) {
                $dataArray[$key][$j] = $value;
            }
        }
    }

    public function actionSaveProposed() {

        $scaleList = $_REQUEST['scaleList'];

        $configLog = new ConfigLog();

        $scaleList = array_filter($scaleList);

        $formData = $_REQUEST['formData'];

        $cfg = new ConfigFile();
        $calibLog = new CalibrationLog();
        $calibLog->run_type = 'MC';

        $calAdjust = CAL_ADJUST_FILE;

        $cfg->load($calAdjust);

        $cfg->setPath("/USRCAL");
        $calibFile = $cfg->readEntry("lib_file");


        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);

        $calibFactory = new ConfigFactory($calibFile);

//        $scaleList = $calibFactory->getDirNames();
        $existSacaleList = $calibFactory->getDirNames();

        $content = '';
        foreach ($scaleList as $key => $scale) {


            $caliConfig->setPath("/" . $scale);
            $logBM .= $scale . '=' . $caliConfig->readEntry('offset', 'offset') . ';';
            if (in_array($scale, $existSacaleList)) {

                if (isset($formData[$scale . '_overwrite'])) {

                    $lastTimeQquery = "update ac_settings set  last_updated  ='" . date('Y-m-d H:i:s') . "' where element_name ='" . $scale . "'";
                    Yii::app()->db->createCommand($lastTimeQquery)->query();

                    
                    $eleName = $scale . '_offset';
                    $calibLog->$eleName = $formData[$scale . '_proposed_offset'];
                    $content .= "[$scale]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=1" . PHP_EOL;
                    $content .= "offset=" . $formData[$scale . '_proposed_offset'] . PHP_EOL;
                } else {

                    
                    $eleName = $scale . '_offset';
                    $calibLog->$eleName = $formData[$scale . '_proposed_offset'];
                    $caliConfig->setPath("/" . $scale);
                    $content .= "[$scale]" . PHP_EOL;
                    $content .= "type=" . $caliConfig->readEntry('type', 'output') . PHP_EOL;
                    $content .= "gain=1" . PHP_EOL;
                    $content .= "offset=" . $caliConfig->readEntry('offset') . PHP_EOL;
                }
            } else { //New Value
                if (isset($formData[$scale . '_overwrite'])) {

                    $eleName = $scale . '_offset';
                    $calibLog->$eleName = $formData[$scale . '_proposed_offset'];
                    $lastTimeQquery = "update ac_settings set  last_updated  ='" . date('Y-m-d H:i:s') . "' where element_name ='" . $scale . "'";

                    Yii::app()->db->createCommand($lastTimeQquery)->query();

                    $logM .= "$scale=" . $formData[$scale . '_proposed_offset'] . ";";
                    $content .= "[$scale]" . PHP_EOL;
                    $content .= "type=" . 'output' . PHP_EOL;
                    $content .= "gain=1" . PHP_EOL;
                    $content .= "offset=" . $formData[$scale . '_proposed_offset'] . PHP_EOL;
                }
            }
        }

        //Save calibartation log
	$isWitableDefaultCfg = Yii::app()->params['writeCalib'];
        if ($isWitableDefaultCfg == 0) {
            Logger::calibLogger(Logger::WARNING, 'default.cfg not updated', "param value in main.php writeCalib is 0 or not present ");
            //we still want to save to analyze data
            $result = 1;
        } else{
            
        $fp = fopen($calibFile, 'w');
            $result = fwrite($fp, $content);
        }
        $calibLog->updated_by = Yii::app()->user->id;
      
        
        try {
             $calibLog->save(false); 
        } catch (Exception $ex) {
            
        }

        $configLog->c_table_name = 'offset';
        $configLog->c_var_name = $formData['offset-type'];
        $configLog->c_var_value = $formData['offset-type'];

        $configLog->c_var_desc = ' Offset values changed: ' . PHP_EOL . 'Before ' . $logBM . "After " . PHP_EOL . "$logM";
        $configLog->c_updated = date('Y-m-d H:i:s');
        $configLog->save();

        //	var_dump($logM);
        //var_dump($configLog);
        //return $this->render('submitPr');
    }

    public function actionCreate() {

        $scaleList = $_REQUEST['scaleList'];
        $element = $_REQUEST['element'];
        $offset = $_REQUEST['offset'];
        $gain = 1; //$_REQUEST['gain'];
        $type = 'output'; //$_REQUEST('type', 'output');


        if (isset($scaleList[$element])) {

            $this->sendSuccessResponse('Success');
        }
        $cfg = new ConfigFile();

        $calAdjust = CAL_ADJUST_FILE;

        $cfg->load($calAdjust);
        $cfg->setPath("/USRCAL");

        $calibFile = $cfg->readEntry("lib_file");

        $caliConfig = new ConfigFile();
        $calibLog = new CalibrationLog();

        $caliConfig->load($calibFile);

//        $content = implode(PHP_EOL,file($calibFile));

        $fp = fopen($calibFile, 'a');


        $offsetName = $element . '_offset';
        $newEnty = "";
        $newEnty .= '[' . $element . ']' . PHP_EOL;
        $newEnty .= 'type=' . $type . PHP_EOL;
        $newEnty .= 'offset=' . $offset . PHP_EOL;
        $newEnty .= 'gain=1'  . PHP_EOL;

        $isWitableDefaultCfg = Yii::app()->params['writeCalib'];
        if ($isWitableDefaultCfg == 0) {
            Logger::calibLogger(Logger::WARNING, 'default.cfg not updated', "param value in main.php writeCalib is 0 or not present ");
            //we still want to save to analyze data
            $result = 1;
        } else
            $result = fwrite($fp, $newEnty);

        if ($result) {


            $calibLog->$offsetName = $offset;
            $calibLog->updated_by = Yii::app()->user->id;
            $calibLog->save(false);
            //var_dump($scaleList);
        }
        fclose($fp);
        $this->sendSuccessResponse('Success');
    }

    public function actionGetScale() {

        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILE;

        $cfg->load($calAdjust);

        $cfg->setPath("/USRCAL");

        $calibFile = $cfg->readEntry("lib_file");


        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);

        $calibFactory = new ConfigFactory($calibFile);

        $existingScaleList = $calibFactory->getDirNames();
        $confList = array();
        foreach ($existingScaleList as $item) {

            $caliConfig->setPath("/" . $item);

            $confList[$item]['type'] = $caliConfig->readEntry("type");
            $confList[$item]['gain'] = 1;//$caliConfig->readEntry("gain");
            $confList[$item]['offset'] = $caliConfig->readEntry("offset");
        }

        $this->sendSuccessResponse($confList);
    }

    public function actionSaveEntry() {


        $scaleList = $_REQUEST['scaleList'];
        $offset = $_REQUEST['offset'];
        $gain = $_REQUEST['gain'];

        $cfg = new ConfigFile();
        $calibLog = new CalibrationLog();
        $calibLog->run_type = 'M';
        $calAdjust = CAL_ADJUST_FILE;

        $cfg->load($calAdjust);

        $cfg->setPath("/USRCAL");
        $calibFile = $cfg->readEntry("lib_file");


        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);
        $calibFile = $cfg->readEntry("lib_file");

        $caliConfig = new ConfigFile();

        $caliConfig->load($calibFile);

        $updatingEle = $_REQUEST['eleValue'];

        $lastTimeQquery = "update ac_settings set  last_updated  ='" . date('Y-m-d H:i:s') . "' where element_name ='" . $updatingEle . "'";
        Yii::app()->db->createCommand($lastTimeQquery)->query();

        $content = '';
        foreach ($scaleList as $key => $scale) {



            $eleName = $key . '_offset';
            $calibLog->$eleName = $scale['offset'];
            $content .= "[$key]" . PHP_EOL;
            $content .= "type=" . $scale['type'] . PHP_EOL;
            $content .= "gain=1" . PHP_EOL;
            $content .= "offset=" . $scale['offset'] . PHP_EOL;
        }

        $calibLog->updated_by = Yii::app()->user->id;
        $calibLog->save(false);

        $isWitableDefaultCfg = Yii::app()->params['writeCalib'];
        if ($isWitableDefaultCfg == 0) {
            Logger::calibLogger(Logger::WARNING, 'default.cfg not updated', "param value in main.php writeCalib is 0 or not present ");
            //we still want to save to analyze data
            $result = 1;
        } else {

            $fp = fopen($calibFile, 'w');
            $result = fwrite($fp, $content);
            fclose($fp);
        }
    }

    public function actionSaveAllEntry() {


        $element = $_REQUEST('element');
        $offset = $_REQUEST('offset');
        $gain = $_REQUEST('gain');

        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILE;



        $cfg->load($calAdjust);

        $cfg->setPath("/USRCAL");
        $calibFile = $cfg->readEntry("lib_file");


        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);



        $caliConfig->setPath("/" . $element);

//        $caliConfig->writeEntry("type");
        $caliConfig->writeEntry("gain", 1);
        $caliConfig->writeEntry("offset", $offset);
        $caliConfig->flush();
    }

    function doMiscItems($alertMainObj) {

        $this->testMultipleSets($alertMainObj);
    }

//call this function for test

    function doMiscItemsMain() {

        $log = new DummyLogger();
        $alertMainObj = new DummyAlertMain($log);

        //test 10 or 15 samples sets
        //  $result = $this->testMultipleSets($alertMainObj);
        //sample call
        $scaleParamsObj = new ScaleParams($alertMainObj);

        //convert from associative array to normal array as required
        // 10 elements
        $arAnalyzer = array(3.79, 3.87, 3.68, 3.86, 3.82, 3.91, 3.95, 3.93, 4.24, 3.92);
        $arLab = array(4.75, 4.69, 4.69, 4.71, 4.62, 4.36, 4.29, 4.34, 4.38, 4.12);

        //$result = $scaleParamsObj->getGainOffsetUsingMeanMethod($alertMainObj->log, $asArAnalyzer, $asArLab);
        $result = $scaleParamsObj->getGainOffsetUsingPerm($arAnalyzer, $arLab, 0.5);

        if ($result["found"]) {
            // $alertMainObj->log->fatal("R2 = " . $result["r2"] . ", gain = " . $result["gain"] . ", offset " . $result["offset"]);
        } else
        //$alertMainObj->log->fatal("Gain Offset not Found");    
            return $result;
    }

//index
}
