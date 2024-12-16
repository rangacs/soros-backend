<?php

class LabdataController extends BaseController {

    public $layout = 'metronic-column2';
    public $mysqlTool;
    public $lookUp;
    public $scaleParamsArray = array("Al2O3", "SiO2", "Fe2O3", "CaO");

    public function init() {

        $cSettings = UISettings::getConfig(Yii::app()->user->getId());
        if (isset($_GET['langId']) && in_array($_GET['langId'], Yii::app()->params['langArray'])) {
            Yii::app()->language = $_GET['langId'];
        } else if (isset($cSettings['language'])) {
            Yii::app()->language = $cSettings['language'];
        } else {
            Yii::app()->language = 'en';
        }

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
            'SodiumEquivalent' => 'NaEQ',
            'N' => 'SM',
            'P' => 'IM'
        );

        $pathinfo = pathinfo(Yii::app()->request->scriptFile);

        $uploaddir = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;

        require($uploaddir . 'defines.php');
    }

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('deny',
                'actions' => array('index', 'view', 'historyCompare', 'LogMessages', 'DeleteRecord','SaveLabValues'),
                'users' => array('?'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('admin'),
            ),
            array('deny',
                'actions' => array('delete'),
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {

        $sql = "select * from lab_data_history where  uploaded_by = " .
                Yii::app()->user->id . " order by upload_time DESC";
        $historyCommand = Yii::app()->db->createCommand($sql);
        $historyRecord = $historyCommand->queryAll();


        $activeTemplate = Yii::app()->db->createCommand("select * from lab_template where status = 1")->queryRow();

        $this->render('index', array('activeTemplate' => $activeTemplate, 'historyRecord' => $historyRecord, 'templateExits' => 1));
        /* $this->checkTempleteExits() */
    }

    public function actionClearlog() {


        Yii::app()->db->createCommand('truncate table calibration_log;truncate table calibration_log_messages')->query();

        $this->sendSuccessResponse('Calibration logs cleared');
    }

    public function actionHistoryCompare() {

        if (isset($_REQUEST['lab_hist_id'])) {

            $currentTimeTick = time();
            $lab_hist_id = $_REQUEST['lab_hist_id'];

            $samplingTimeQuery = "select * from rm_settings where varName = 'LAB_HISTORY_SAMPLING_TIME'";
            $samplingCommnad = Yii::app()->db->createCommand($samplingTimeQuery);
            $samplingResult = $samplingCommnad->queryRow();
            // $tmp1 = explode(':', $samplingResult['varValue']);
            $samplingTime = ($samplingResult['varValue'] * 60);

            $fallBackTimeQuery = "select * from rm_settings where varName = 'LAB_HISTORY_FALLBACK_TIME'";
            $fallBackCommnad = Yii::app()->db->createCommand($fallBackTimeQuery);
            $fallBackResult = $fallBackCommnad->queryRow();
            // $tmp2 = explode(':', $fallBackResult['varValue']);
            $processingTime = ($fallBackResult['varValue'] * 60);


            $historyQuery = "SELECT * FROM lab_data_history where lab_hist_id = '" . $_REQUEST['lab_hist_id'] . "' order by start_time limit 1";

            $oDbConnection = Yii::app()->db;
            $oCommand = $oDbConnection->createCommand($historyQuery);
            $labHistoryEntry = $oCommand->queryRow(); // Run query and get all results in a CDbDataReader
            $historyData = json_decode($labHistoryEntry['data']);
            $templateModel = LabTemplate::model()->find('template_id = :template_id', array(':template_id' => $labHistoryEntry['template_id']));
            $columnReader = array();

            foreach ($historyData->elements as $colI) {

                $columnReader[] = $this->normalise($colI);
            }
            $oCDbDataReader = $historyData->ele_data;

            $query = "select * from rm_settings where varKey = 'profile' ";
            $result = Yii::app()->db->createCommand($query)->queryRow();
            $profile = $result['varValue'];


            //create temp table
            $tData = str_getcsv($templateModel['content'], "\n");
            $tempInfo = $this->parseCsvfile($tData);

            if (empty($tempInfo['col'])) {
                $this->sendFailedResponse('Invalid file format');
            }
            $query = $this->buildCreateTableQuery($tempInfo, 'lab_history_data');

            $result = Yii::app()->db->createCommand($query)->execute();

            $tableSchema = Yii::app()->db->schema->getTable('lab_history_data');
            $lData = str_getcsv($labHistoryEntry['raw_data'], "\n");
            $tableInfo = $this->parseCsvDataFile($lData, $templateModel);
            $labDataquery = $this->buildInsertDataQuery($tableInfo, 'lab_history_data');
            try {
                $result = Yii::app()->db->createCommand($labDataquery)->execute();
            } catch (Exception $ex) {
                $this->sendFailedResponse('Invalid data');
            }

            /*             * ************* Record filtering *************** */

            $maxValidTime = $currentTimeTick - (6 * 3600);

            $offsetSql = 'select * from  calibration_log  where updated > "' . date('Y-m-d H:i:s', $maxValidTime) . '" order by updated DESC limit 1';

            $offsetLog = Yii::app()->db->createCommand($offsetSql)->query()->read();

            if (!empty($offsetLog)) {


                $timeTick = strtotime($offsetLog['updated']);

                $validTIme = $timeTick + ($samplingTime + $processingTime );

                $validTimeString = date('Y-m-d H:i:s', $validTIme);

                // Logger::calibLogger(Logger::INFO, '[actionHistoryCompare][INFO] | Last calibration offset found!');
            } else {

                $validTimeString = date('Y-m-d H:i:s', $maxValidTime);
                //   Logger::calibLogger(Logger::INFO, 'No calib log found', '[actionHistoryCompare][[WARN] calibration offset NOT  found!');
            }

            $labSql = 'select *,lab_data_id AS id from lab_history_data  order by EndTime DESC';
            //  Logger::calibLogger(Logger::INFO, 'Query ', array($labSql));

            $labHistorData = Yii::app()->db->createCommand($labSql)->queryAll();
            //Logger::calibLogger(Logger::INFO, 'Total number of valid records found ', array('Number' => count($labHistorData)));
            //	var_dump(date('Y-m-d H:i:s'),date('Y-m-d H:i:s',$maxvalidDate),$validTimeString,$samplingTime , $processingTime , $offsetSql );




            $rawData = array();
            foreach ($labHistorData as $key => $row) {

                foreach ($row as $j => $value) {
                    $rawData[$key][$j] = $value;
                }
            }




            $dataProvider = new CArrayDataProvider($rawData, array(
                'pagination' => array(
                    'pageSize' => 50,
                ),
            ));
        }


        $this->render('lab-history', array("columnReader" => $columnReader,
            'dataProvider' => $dataProvider,
            'labHistoryEntry' => $labHistoryEntry,
            'profile' => $profile,
            'validTimeString' => $validTimeString,
            'lookup' => $historyData->lookup)
        );
    }

    public function actionImportTemplate() {


        $files = array();

        $templateModal = new LabTemplate();

        //remove previously imported file


        $pathinfo = pathinfo(Yii::app()->request->scriptFile);


        $uploaddir = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "uploads";



        //Remove existing template
        if (file_exists($uploaddir . DIRECTORY_SEPARATOR . 'template.csv'))
            unlink($uploaddir . DIRECTORY_SEPARATOR . 'template.csv');

        $fileResponse = $this->checkForValidFile();

        if ($fileResponse['status'] == 1) {

            foreach ($_FILES as $file) {

                $outputFile = $uploaddir . DIRECTORY_SEPARATOR . 'template.csv';

                if (move_uploaded_file($file['tmp_name'], $outputFile)) {
                    $files[] = $uploaddir . $file['name'];
                } else {
                    
                }
            }

            $tempCsvData = file($outputFile);

            //Lab template queery
            $tableInfo = $this->parseCsvfile($tempCsvData);

            if (empty($tableInfo['col'])) {
                $this->sendFailedResponse('Invalid file format');
            }
            $query = $this->buildCreateTableQuery($tableInfo, 'lab_data');

            $result = Yii::app()->db->createCommand($query)->execute();
            $tableSchema = Yii::app()->db->schema->getTable('lab_data');

            //To check wheather table created
            if ($tableSchema != NULL) {

                //change active template

                $sql = "UPDATE lab_template SET status = 0 WHERE 1";
                Yii::app()->db->createCommand($sql)->execute();


                $templateModal->uploaded_by = isset(Yii::app()->user->id) ? Yii::app()->user->id : 0;
                $templateModal->uploaded_at = date('Y-m-d H:i:s');
                $templateModal->file_name = $_REQUEST['user_file_name'];
                $templateModal->content = file_get_contents($outputFile);
                $templateModal->status = 1;
                $templateModal->skip_col = $tableInfo['skipCol'];
                $templateModal->skip_row = $tableInfo['skipRow'];
                $templateModal->columns = implode(',', $tableInfo['col']);
                $templateModal->save();


                $errors = $templateModal->errors;


                if (!$errors) {


                    $this->sendSuccessResponse(array('Template Uploaded Successfully'));
                } else {


                    $this->sendFailedResponse($errors);
                }
            } else {
                $this->sendFailedResponse('Invalid Template');
            }
        } else {

            $this->sendFailedResponse($fileResponse['error']);
        }
    }

    public function actionView($lab_hist_id) {

        $labHistoryModel = LabDataHistory::model()->find('lab_hist_id = :lab_hist_id', array(':lab_hist_id' => $lab_hist_id));

        $templateModel = LabTemplate::model()->find('template_id =:template_id', array(':template_id' => $labHistoryModel->template_id));


        if (isset($_REQUEST['lab_hist_id'])) {
// Run query and get all results in a CDbDataReader
            $historyData = json_decode($labHistoryModel->data);

            $columnReader = array();

            foreach ($historyData->elements as $colI) {

                $columnReader[] = $this->normalise($colI);
            }
            $oCDbDataReader = $historyData->ele_data;


            $profile = 'cement'; //$mysqlTool->getSetting('rm_settings', 'profile');
            //create temp table
            $tData = str_getcsv($templateModel['content'], "\n");
            $tempInfo = $this->parseCsvfile($tData);



            if (empty($tempInfo['col'])) {
                $this->sendFailedResponse('Invalid file format');
            }
            $query = $this->buildCreateTableQuery($tempInfo, 'lab_history_data');


            $result = Yii::app()->db->createCommand($query)->execute();

            $tableSchema = Yii::app()->db->schema->getTable('lab_history_data');



            $lData = str_getcsv($labHistoryModel->raw_data, "\n");

            $tableInfo = $this->parseCsvDataFile($lData, $templateModel);

            $labDataquery = $this->buildInsertDataQuery($tableInfo, 'lab_history_data');



            try {
                $result = Yii::app()->db->createCommand($labDataquery)->execute();
            } catch (Exception $ex) {
                Yii::app()->api->sendFailedResponse('Invalid data');
            }


            $labHistoryData = Yii::app()->db->createCommand('select * , lab_data_id as id from lab_history_data order by EndTime DESC')->queryAll();


            $rawData = array();

            foreach ($labHistoryData as $key => $row) {

                foreach ($row as $j => $value) {
                    $rawData[$key][$j] = $value;
                }
            }


            $dataProvider = new CArrayDataProvider($rawData, array(
                'keyField' => 'lab_data_id',
                'pagination' => array(
                    'pageSize' => 50,
                ),
            ));
        }
        return $this->render('lab-view', array('labHistoryModel' => $labHistoryModel, 'templateModel' => $templateModel, 'dataProvider' => $dataProvider));
    }

    public function actionViewTemplate() {


        //Toto check for existing template 
        //error handling
        $columnsQuery = 'SHOW columns FROM lab_data';

        $result = Yii::app()->db->createCommand($columnsQuery)->query()->readAll();

        $columns = array();

        $table = '<table class="table table-advanced"';
        $colRow = '';
        $typeRow = '';
        $tableContent = '';
        foreach ($result as $value) {

            $col = $value['Field'];
            $type = $value['Type'];
            $colRow .= "<td>{$col}</td>";
            $typeRow .= "<td>{$type}</td>";
        }

        $tableContent .= '<tr> <th>column</th> ' . $colRow . '</tr>';
        $tableContent .= '<tr><th> type</th>' . $typeRow . '</tr>';

        $table .= $tableContent;

        $table .= '</table>';

        echo $table;
    }

    public function actionDataEntry() {


        $this->render('data-entry');
    }

    public function actionLogMessages() {

        //$this->sendSuccessResponse(array());

        echo 90;
    }

    public function actionSaveLabValues() {


        $autoCalFlag = Yii::app()->db->createCommand("select * from rm_settings where varKey ='AUTO_CALIB'")->queryRow();

        //********** Save Lab Record**************
        $formData = $_REQUEST['formData'];
        $formArray = array();
        parse_str($formData, $formArray);
        $this->addLabEntry($formArray['LabValues']);

        $current_time = date('Y-m-d H:i');
        if ($autoCalFlag['varValue'] == 0) {
            Logger::calibLogger(Logger::WARNING, 'Autocalibration flag is off', '');
            $this->sendSuccessResponse(CalibrationLogMessages::model()->findAll());
        } else {
            Logger::calibLogger(Logger::INFO, '#### Autocalibration Starts', 'runtime: ' . $current_time);
        }

        $elements = array('Al2O3', 'Fe2O3', 'SiO2', 'CaO');

        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILE;
        $cfg->load($calAdjust);
        $cfg->setPath("/USRCAL");

        $calibFile = $cfg->readEntry("lib_file");
        $calibConfig = new ConfigFile();
        $calibConfig->load($calibFile);

        //calib
        $calibLog = new CalibrationLog();

        $content = '';

        $oldCalibration = '';
        $finalOffset = '';
        $writeCalibrationFile = 0;
        $newCalibration = '';
       

        foreach ($elements as $ele) {


            $calibConfig->setPath("/" . $ele);

            $cOffset = $calibConfig->readEntry('offset', 0.0);

            //record old offset
            $oldOffset[$ele] = $cOffset;
            $oldCalibration .= $ele . ':' . $cOffset . ", ";
            $writeOffset[$ele] = $cOffset;

            echo "----------- Start $ele calibration offset $cOffset--------------" . PHP_EOL;
            $offsetObj = new OffsetObject($ele);
//            EndTime
            $offsetObj->setLabEntryTime($formArray['LabValues']['EndTime']);

            Logger::calibLogger(Logger::INFO, "**" . $ele . ' run started', '...');

            $result = $offsetObj->getValidLabRecords();


            if ($result) {

                //Calculate Offset
                Logger::calibLogger(Logger::INFO, 'Starting Offset Calculator', 'runtime: ' . $current_time);

                $newCalibSettings = $offsetObj->calculateScale();

                if ($newCalibSettings['found']) {
                    //need to write the file
                    $writeCalibrationFile = 1;
                    //record the change
                    $writeOffset[$ele] = $newCalibSettings['offset'];
                    //update db 
                    $finalOffset .= $ele . ":" . $newCalibSettings['offset'];
                    $lastTimeQquery = "update ac_settings set  last_updated  ='" . date('Y-m-d H:i:s') . "' where element_name ='" . $ele . "'";

                    Yii::app()->db->createCommand($lastTimeQquery)->query();
                    $newCalibration .= $ele . ':' . $writeOffset[$ele] . ", ";
                    Logger::calibLogger(Logger::INFO, "***" . $ele . ' run successful offset found', 'offset :' . $writeOffset[$ele]);
                } else {
                    //keeping old calibration
                    $writeOffset[$ele] = $cOffset;
                    $newCalibration .= $ele . ':' . $writeOffset[$ele] . ", ";
                    Logger::calibLogger(Logger::INFO, "**" . $ele . ' run successful but offset not found', 'offset calculator returned old offset : ' . $writeOffset[$ele]);
                }
            } else {
                Logger::calibLogger(Logger::INFO, "**" . $ele . ' run unsuccessful', 'Nothing to be done, no valid records found');
            }
            //update the buffer for file writing
            $content .= "[$ele]" . PHP_EOL;
            $content .= "type=output" . PHP_EOL;
            $content .= "gain=1" . PHP_EOL;
            $content .= "offset=" . $writeOffset[$ele] . PHP_EOL;
            $eleName = $ele . '_offset';
            $calibLog->$eleName = $writeOffset[$ele];


            echo "----------- End $ele calibration --------------<br>";
        }//for ends

        if ($writeCalibrationFile) {

            //var_dump('Old Calibration', $oldCalibration);
            //var_dump('New Calibration', $newCalibration);

            $calibLog->run_type = 'A';
            $calibLog->updated_by = Yii::app()->user->id ? Yii::app()->user->id : 1;

            $fp = fopen($calibFile, 'w');

			$isWitableDefaultCfg = Yii::app()->params['writeCalib'];
			if ($isWitableDefaultCfg==0)
				Logger::calibLogger(Logger::WARNING, 'default.cfg not updated',"param value in main.php writeCalib is 0 or not present ");
			else
				$result = fwrite($fp, $content);

            $calibLog->save(false);

            // Logger::calibLogger(Logger::INFO, 'Calib Error', $calibLog->errors);
            Logger::calibLogger(Logger::INFO, 'Old Calibration', $oldCalibration);
            Logger::calibLogger(Logger::INFO, 'New Calibration', $newCalibration);
        } else {
            Logger::calibLogger(Logger::INFO, 'No Offsets changed', 'Not updating user calibration file default.cfg');
        }


        Logger::calibLogger(Logger::INFO, '#### Autocalibration Ends', 'runtime: ' . $current_time);
    }

    public function saveAutoCalib($scaleList) {


        // No scaling data don't take any action
        if (empty($scaleList))
            return;

        $configLog = new ConfigLog();
        $scaleList = array_filter($scaleList);

        $cfg = new ConfigFile();
        $calibLog = new CalibrationLog();
        $calAdjust = CAL_ADJUST_FILE;

        $cfg->load($calAdjust);

        $cfg->setPath("/USRCAL");
        $calibFile = $cfg->readEntry("lib_file");
        $logBM = '';
        $logM = '';


        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);

        $calibFactory = new ConfigFactory($calibFile);

//        $scaleList = $calibFactory->getDirNames();
        $existSacaleList = $calibFactory->getDirNames();

        $fp = fopen($calibFile, 'w');
        $content = '';

        foreach ($scaleList as $scale => $formData) {



            if (isset($formData)) {


                $eleName = $scale . '_offset';

                $calibLog->$eleName = $formData['offset'];
                $logM .= "$scale=" . $scale . ";";
                $content .= "[$scale]" . PHP_EOL;
                $content .= "type=" . 'output' . PHP_EOL;
                $content .= "gain=1" . PHP_EOL;
                $content .= "offset=" . $formData['offset'] . PHP_EOL;
            } else {

                if (in_array($scale, $existSacaleList)) {


                    $caliConfig->setPath("/" . $scale);
                    $content .= "[$scale]" . PHP_EOL;
                    $content .= "type=" . $caliConfig->readEntry('type', 'output') . PHP_EOL;
                    $content .= "gain=" . $caliConfig->readEntry('gain') . PHP_EOL;
                    $content .= "offset=" . $caliConfig->readEntry('offset') . PHP_EOL;
                }
            }
        }



        //die();
        //Save calibartation log

        $calibLog->updated_by = Yii::app()->user->id;
        $calibLog->save(false);

        //	Logger::calibLogger(Logger::INFO,'Calib file being saved file', $content);
        //$configLog->c_table_name = 'offset';
        //$configLog->c_var_name = $formData['offset-type'];
        //$configLog->c_var_value = $formData['offset-type'];
        //$configLog->c_var_desc = ' Offset values changed: '.PHP_EOL.'Before '.$logBM."After ".PHP_EOL."$logM";
        //$configLog->c_updated = date('Y-m-d H:i:s');
        //$configLog->save();
        //var_dump($content);
		$isWitableDefaultCfg = Yii::app()->params['writeCalib'];
		if ($isWitableDefaultCfg==0)
			Logger::calibLogger(Logger::WARNING, 'default.cfg not updated',"param value in main.php writeCalib is 0 or not present");
		else
			$result = fwrite($fp, $content);
        //var_dump($configLog);
        //die($configLog);
        //return $this->render('submitPr');
    }

    //End of calib auto save

    public function buildTempHistoryTable() {

        $historyQuery = "SELECT * FROM lab_data_history where temp_name ='lab_manual_entries' order by start_time limit 1";

        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand($historyQuery);
        $labHistoryEntry = $oCommand->queryRow(); // Run query and get all results in a CDbDataReader
        $historyData = json_decode($labHistoryEntry['data']);
        $templateModel = LabTemplate::model()->find('template_id = :template_id', array(':template_id' => $labHistoryEntry['template_id']));
        $columnReader = array();

        foreach ($historyData->elements as $colI) {

            $columnReader[] = $this->normalise($colI);
        }
        $oCDbDataReader = $historyData->ele_data;

        $query = "select * from rm_settings where varKey = 'profile' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        $profile = $result['varValue'];


        //create temp table
        $tData = str_getcsv($templateModel['content'], "\n");
        $tempInfo = $this->parseCsvfile($tData);

        if (empty($tempInfo['col'])) {
            // $this->sendFailedResponse('Invalid file format');
            Logger::calibLogger(Logger::WARNING, 'unable to create temp data table', 'Unable to create temp data table');
        }
        $query = $this->buildCreateTableQuery($tempInfo, 'lab_history_data');

        $result = Yii::app()->db->createCommand($query)->execute();

        $tableSchema = Yii::app()->db->schema->getTable('lab_history_data');
        $lData = str_getcsv($labHistoryEntry['raw_data'], "\n");
        $tableInfo = $this->parseCsvDataFile($lData, $templateModel);
        $labDataquery = $this->buildInsertDataQuery($tableInfo, 'lab_history_data');
        try {
            $result = Yii::app()->db->createCommand($labDataquery)->execute();
        } catch (Exception $ex) {
            // $this->sendFailedResponse('Invalid data');
            Logger::calibLogger(Logger::WARNING, 'unable to create temp data table', 'Unable to create temp data table');
        }
    }

    public function populateFromLabFiles($labHistIds) {

        $schemaCreated = false;

        foreach ($labHistIds as $labHistId) {


            $historyQuery = "SELECT * FROM lab_data_history where lab_hist_id ={$labHistId} ";

            $oDbConnection = Yii::app()->db;
            $oCommand = $oDbConnection->createCommand($historyQuery);
            $labHistoryEntry = $oCommand->queryRow(); // Run query and get all results in a CDbDataReader
            $historyData = json_decode($labHistoryEntry['data']);
            $templateModel = LabTemplate::model()->find('template_id = :template_id', array(':template_id' => $labHistoryEntry['template_id']));
            $columnReader = array();

            foreach ($historyData->elements as $colI) {

                $columnReader[] = $this->normalise($colI);
            }
            $oCDbDataReader = $historyData->ele_data;

            $query = "select * from rm_settings where varKey = 'profile' ";
            $result = Yii::app()->db->createCommand($query)->queryRow();
            $profile = $result['varValue'];


            //create temp table
            $tData = str_getcsv($templateModel['content'], "\n");
            $tempInfo = $this->parseCsvfile($tData);

            if (empty($tempInfo['col'])) {
                // $this->sendFailedResponse('Invalid file format');
                Logger::calibLogger(Logger::WARNING, 'unable to create temp data table', 'Unable to create temp data table');
            }
            $tempTableCreatequery = $this->buildCreateTableQuery($tempInfo, 'lab_history_data');

            if (!$schemaCreated) {
                $result = Yii::app()->db->createCommand($tempTableCreatequery)->execute();

                $schemaCreated = true;
            }

            $tableSchema = Yii::app()->db->schema->getTable('lab_history_data');
            $lData = str_getcsv($labHistoryEntry['raw_data'], "\n");
            $tableInfo = $this->parseCsvDataFile($lData, $templateModel);
            $labDataquery = $this->buildInsertDataQuery($tableInfo, 'lab_history_data');
            try {
                $result = Yii::app()->db->createCommand($labDataquery)->execute();
            } catch (Exception $ex) {
                // $this->sendFailedResponse('Invalid data');
                Logger::calibLogger(Logger::WARNING, 'unable to create temp data table', 'Unable to create temp data table');
            }
        }//end of foreach 
    }

    public function actionDeleteRecord() {


        $timeStrig      = $_REQUEST['EndTime'];
        $labDataName    = $_REQUEST['lab_hist_id'];

        $fileExits = LabDataHistory::model()->exists('lab_hist_id = :lab_hist_id', array(':lab_hist_id' => $labDataName));

         if ($fileExits) {


            $labdata = LabDataHistory::model()->find('lab_hist_id = :lab_hist_id', array(':lab_hist_id' => $labDataName));
            $data = $labdata->data;
            $jsonDecode = json_decode($data);
            $eleData = $jsonDecode->ele_data;
            $recordExits = false;
            $index = 0;


            foreach ($eleData as $key => $values) {

                if ($this->isDuplicateEntry($values, $timeStrig)) {


                    $index = $key;
                    $recordExits = true;
                    continue;
                }
            }


            //2890101005496

            if ($recordExits) {

                unset($eleData[$index]);

                //If last record deleted
                
                if(count($eleData) == 0){
                    
                    $labdata->delete();
                    $this->sendSuccessResponse(array('file_deleted' => 1));

                }
                
                $str = '';
                $tmp = array();
                foreach ($eleData as $item) {

                    $tmp[] = $item;

                }

                $str = implode(PHP_EOL, $eleData);

                $labdata->raw_data = $str; //fputcsv($eleData) ;

                $jsonDecode->ele_data = $tmp;
                $labdata->data = json_encode($jsonDecode);


                if ($labdata->save(false)) {

                    $this->sendSuccessResponse(array('found', $eleData, $str));


                } else {

                    $this->sendSuccessResponse(array('found', $eleData, $str));



                    //$this->sendFailedResponse(false);
                }
            } else {

                $this->sendSuccessResponse(false);
            }
        } else {

            $this->sendSuccessResponse(true);
        }
    }

    public function actionDataEntryExits() {


        $timeStrig = $_REQUEST['EndTime'];
        $userid    = Yii::app()->user->id;
        $dateTimeString = date('Y-m-d H:i:s', strtotime($timeStrig));

        $todayDate = date('Y_m_d', strtotime($timeStrig));

        $labDataName = 'lab_data_' . $todayDate;

        $fileExits = LabDataHistory::model()->exists('temp_name = :temp_name and uploaded_by = :userid', 
                array(':temp_name' => $labDataName,':userid' => $userid));


        //File exits
        if ($fileExits) {

            $labdata = LabDataHistory::model()->find('temp_name = :temp_name', array(':temp_name' => $labDataName));


            $data = $labdata->data;
            $jsonDecode = json_decode($data);

            $eleData = $jsonDecode->ele_data;


            $recordExits = false;
            $index = 0;
            foreach ($eleData as $key => $values) {

                if ($this->isDuplicateEntry($values, $dateTimeString)) {
                    $index = $key;
                    $recordExits = true;
                    continue;
                }
            }

            if ($recordExits) {
                $this->sendFailedResponse(array($dateTimeString, $index));
            } else {

                $this->sendSuccessResponse(true);
            }
        } else {

            $this->sendSuccessResponse(true);
        }
    }

    public function addLabEntry($newData) {


        $timeStrig = $newData['DateOnly'] .' '. $newData['EndTime'];
        $userid    = Yii::app()->user->id;

        $dateTimeString = date('Y-m-d H:i:s', strtotime($timeStrig));

        $todayDate = date('Y_m_d', strtotime($timeStrig));

        $labDataName = 'lab_data_' . $todayDate;

        $fileExits = LabDataHistory::model()->exists('temp_name = :temp_name and uploaded_by = :userid', array(':temp_name' => $labDataName,':userid' => $userid));

        if ($fileExits) {

            $labdata = LabDataHistory::model()->find('temp_name = :temp_name and uploaded_by = :userid', array(':temp_name' => $labDataName,':userid' => $userid));

            $currentData = $labdata->raw_data;

            $data = $labdata->data;
            $jsonDecode = json_decode($data);

            $eleData = $jsonDecode->ele_data;

            $recordExits = false;
            $index = 0;
            foreach ($eleData as $key => $values) {

                if ($this->isDuplicateEntry($values, $dateTimeString)) {
                    $index = $key;
                    $recordExits = true;
                    continue;
                }
            }



            //File exits    record does not exits
            if (!$recordExits) {

                $elements = $jsonDecode->elements;

                $tmpData = array();
                foreach ($elements as $ele) {

                    $tmpData[] = isset($newData[$ele]) ? $newData[$ele] : 0;
                }

                $tmpData[0] = $dateTimeString;
                $jsonDecode->ele_data[] = implode(',', $tmpData);

                $csvData = implode(',', $tmpData);
                $labdata->raw_data = $currentData . PHP_EOL . $csvData;
                $labdata->data = json_encode($jsonDecode);

                // $labdata->save();

                if ($labdata->save(false)) {

                    $inserttime = date('Y-m-d H:i:s');
                    $startTime = date('Y-m-d H:i:s', strtotime($dateTimeString) - 3600);
                    $endTime = $dateTimeString;
                    Logger::calibLogger(Logger::INFO, '$$$$ Manual Lab Record Entry', 'Only Lab Record inserted at: ' . $inserttime . ' for duration ' . $startTime . ':' . $endTime . ' File Exists, New Lab Record Inserted');
                }
            } else {
                //File exits record does not exits
                $elements = $jsonDecode->elements;
                $tmpData = array();
                foreach ($elements as $ele) {

                    $tmpData[] = isset($newData[$ele]) ? $newData[$ele] : 0;
                }

                $tmpData[0] = $dateTimeString;

                $jsonDecode->ele_data[$index] = implode(',', $tmpData);

                $csvData = implode(',', $tmpData);

                $csv = explode(PHP_EOL, $currentData);

                $csv[$index] = $csvData; // Overwrite existing data

                $labdata->raw_data = implode(PHP_EOL, $csv);
                $labdata->data = json_encode($jsonDecode);

                if ($labdata->save(false)) {

                    $inserttime = date('Y-m-d H:i:s');
                    $startTime = date('Y-m-d H:i:s', strtotime($dateTimeString) - 3600);
                    $endTime = $dateTimeString;
                    Logger::calibLogger(Logger::INFO, '$$$$ Manual Lab Record Entry', 'Overwriting at time: ' . $inserttime . ' for duration ' . $startTime . '#to#' . $endTime . ' File and Record Present');
                }
            }
        } else {

            
            $activeTemplate = LabTemplate::model()->find('status = :status', array(':status' => 1));

            $labDataModel = new LabDataHistory();
            
            $jsonData = '{"elements":["Date\/Hour of Sample","SiO2","Al2O3","Fe2O3","CaO","KH","N","P"],"lookup":{"SiliceOxideContent":"SiO2","AluminumOxideContent":"Al2O3","IronOxideContent":"Fe2O3","CalciumOxideContent":"CaO","SulfurOxideContent":"SO3","MagnesiumOxideContent":"MgO","PotassiumOxideContent":"P2O5","SodiumOxideContent":"SO2","ChlorideContent":"Cl","AluminaModulus":"AM","IronModulus":"IM","TricalciumSilicateContentASTM":"C3S","TricalciumAluminateContentASTM":"C3A","LimeSaturationFactor":"LSF","SilicaModulus":"SM","DicalciumSilicateContentASTM":"C2S","TetracaliumAluminoferriteContentASTM":"C4AF","SodiumEquivalent":"NaEQ","N":"SM","P":"AM"},"ele_data":[]}';
            
            $jsonEncode =  json_decode($jsonData);
            
            $jsonEncode->elements = explode(',', $activeTemplate->columns);
            
            foreach ($jsonEncode->elements as $ele) {

                $tmpData[] = isset($newData[$ele]) ? $newData[$ele] : 0;
            }


            $tmpData[0] = $dateTimeString;

            $jsonEncode->ele_data[] = implode(',', $tmpData);


            $csvData = implode(',', $tmpData);
            $labDataModel->raw_data = $csvData;
            $labDataModel->data = json_encode($jsonEncode);

            $labDataModel->temp_name = $labDataName;

            $labDataModel->sample_type = 'lab-data';

            $labDataModel->upload_time = date('Y-m-d H:i:s');

            $labDataModel->uploaded_by = Yii::app()->user->id;

            $activeTemplate = LabTemplate::model()->find('status = :status', array(':status' => 1));

            $labDataModel->template_id = $activeTemplate->template_id;
            $labDataModel->save(false);

            if ($labDataModel->save(false)) {

                $inserttime = date('Y-m-d H:i:s');
                $startTime = date('Y-m-d H:i:s', strtotime($dateTimeString) - 3600);
                $endTime = $dateTimeString;
                Logger::calibLogger(Logger::INFO, '$$$$ Manual Lab Record Entry', 'New File & Record inserted at time: ' . $inserttime . ' for duration ' . $startTime . ':' . $endTime);
            }
        }
        //		$this->sendSuccessResponse(array('index',$recordExits ,$index));
    }

    // End of addLabEntry

    public function actionUploadLabData() {


        // You need to add server side validation and better error handling here
        $labData = new LabData();
        $data = array();
        $error = false;
        $files = array();
        $labHistory = new LabDataHistory();
        $database = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();

        $colquery = "SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_schema = '" . $database . "'
        AND table_name = 'lab_data'";

        $colCount = Yii::app()->db->createCommand($colquery)->queryScalar();

        if (1) {

            $pathinfo = pathinfo(Yii::app()->request->scriptFile);

            $uploaddir = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "uploads";

            foreach ($_FILES as $file) {


                $outputFile = $uploaddir . DIRECTORY_SEPARATOR . $file['name'];

                //Remove existing template
                if (file_exists($outputFile))
                    unlink($outputFile);


                if (move_uploaded_file($file['tmp_name'], $outputFile)) {
                    $files[] = $uploaddir . DIRECTORY_SEPARATOR . $file['name'];
                } else {
                    $error = true;
                }
            }

            $activeTemplateModel = LabTemplate::model()->find('status=:status', array(':status' => 1));


            //to conver xlxs file to json

            $ldata = $this->loadXlData($outputFile); // $config is an optional




            $csvData = $this->convertXlsDataToCsvData($ldata, explode(';', $activeTemplateModel->skip_col), explode(';', $activeTemplateModel->skip_row));



//            $this->debuger($csvData);
            $tableInfo = $this->parseCsvDataFile($csvData, $activeTemplateModel, 'lab_data');




            if (isset($tableInfo['error'])) {
                $this->sendFailedResponse('Invalid lab-template file.');
            }

            $labDataquery = $this->buildInsertDataQuery($tableInfo, 'lab_data');

//            $this->debuger([$labDataquery]);


            try {

                //To clear previous data 

                $delSql = 'delete from lab_data where 1';
                Yii::app()->db->createCommand($delSql)->execute();


                $result = Yii::app()->db->createCommand($labDataquery)->execute();
            } catch (Exception $ex) {
                $this->sendFailedResponse('Invalid data');
            }


            if ($result) {



                $labHistory->uploaded_by = Yii::app()->user->id;
                $labHistory->template_id = $activeTemplateModel->template_id;
                $labHistory->upload_time = date('Y-m-d H:i:s');
                $labHistory->temp_name = $_REQUEST['user_file_name'];
                $labHistory->raw_data = implode(PHP_EOL, $csvData); //$csvData;//($outputFile);
                $labHistory->data = json_encode($tableInfo);
                $result = $labHistory->save();
                $error = $labHistory->errors;



                $this->sendSuccessResponse('File Uploaded Successfully'); //** Imported successfully**/
            } else {
                $this->sendFailedResponse('Invalid data');
            }
        } else {

            $this->sendFailedResponse($fileResponse['error']);
        }
    }

    public function actionGetScale() {


        $labHistId = $_REQUEST['lab_hist_id'];
        $labHistoryModal = LabDataHistory::model()->find('lab_hist_id = :lab_hist_id', array(':lab_hist_id' => $labHistId));
        $labHistId = $_REQUEST['lab_hist_id'];

        $labTimes = $_REQUEST['labtime'];


        $query = "select * from rm_settings where varName = 'LAB_COMPARE_TABLE' ";

        $result = Yii::app()->db->createCommand($query)->queryRow();


        $table = $result['varValue'];

        $analysisObject = new AnalysisHistoryFactory($labTimes, $table);
        $analysisObject->getRecords();        


        $ignoreArray = array("LocalendTime", 'LocalstartTime', 'lab_data_id', 'Moisture', 'EndTime', 'FinenessSieve50300microns', 'Fineness200Sieve75microns');



        $scaleData = $this->prepareScaleData($labHistId, $labTimes);

        // Logger::calibLogger(Logger::INFO,'Scale data ',$scaleData);

        $sList = array_keys($scaleData['lab'][0]);


        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILE;


        $cfg->load($calAdjust);

        $cfg->setPath("/USRCAL");

        $calibFile = $cfg->readEntry("lib_file");


        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);

        $scaleList = $this->calculateScale($sList, $caliConfig, $scaleData);

        // var_dump($scaleList);
        // die();
        $series = $this->scaleGraph($scaleData['labData'], $scaleData['anaData'], $scaleList);

        $this->render('submitPr', array('series' => $series, 'scaleList' => $scaleList, 'labHistoryModal' => $labHistoryModal, 'allignmentAccurecay' => 0.75));
    }

    public function actionAutoScale() {


        $labHistoryModal = LabDataHistory::model()->find('lab_hist_id = :lab_hist_id', array(':lab_hist_id' => $labHistId));
        $labHistId = $_REQUEST['lab_hist_id'];

        $labTimes = $_REQUEST['labtime'];

        Logger::calibLogger(Logger::INFO, 'Records tims', $labTimes);

        $query = "select * from rm_settings where varName = 'LAB_COMPARE_TABLE' ";

        $result = Yii::app()->db->createCommand($query)->queryRow();


        $table = $result['varValue'];

        $analysisObject = new AnalysisHistoryFactory($labTimes, $table);
        $analysisObject->getRecords();


        $ignoreArray = array("LocalendTime", 'LocalstartTime', 'lab_data_id', 'Moisture', 'EndTime', 'FinenessSieve50300microns', 'Fineness200Sieve75microns');



        $scaleData = $this->prepareScaleData($labHistId, $labTimes);
        $sList = array_keys($scaleData['lab'][0]);


        $cfg = new ConfigFile();

        $calAdjust = CAL_ADJUST_FILE;


        $cfg->load($calAdjust);

        $cfg->setPath("/USRCAL");

        $calibFile = $cfg->readEntry("lib_file");
        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);
        $scaleList = $this->calculateScale($sList, $caliConfig, $scaleData);
        $series = $this->scaleGraph($scaleData['labData'], $scaleData['anaData'], $scaleList);
        $this->render('submitPr', array('series' => $series, 'scaleList' => $scaleList, 'labHistoryModal' => $labHistoryModal, 'allignmentAccurecay' => 0.75));
    }

    public function calculateScale($sList, $caliConfig, $scaleData) {

        $scaleParamsObj = new ScaleParams();
        $spArrray = array('AM', 'SM', 'KH', 'LSF', 'C3S', 'IM');
        $scaleList = array();
        $sList = array('Al2O3', 'Fe2O3', 'SiO2', 'CaO');

        $offsetRangeSettings = LabUtility::getOffsetRangeSettings();
        foreach ($sList as $item) {

            //var_dump($item);


            if (!in_array($item, $this->scaleParamsArray))
                continue;

            $caliConfig->setPath("/" . $item);


            $settings = $offsetRangeSettings[$item];
            $settings['current_value'] = (float)$caliConfig->readEntry("offset", 0.0);
            $settings['useWeightedSum'] = 1;
            $settings['useSDtoRemoveOutlier'] = 0;            
            $settings['useAvgOffsetMeanToRemoveOutlier'] = 0; 
            $settings['from_ancompare'] = 1; 
			
			$Query = "select * from rm_settings where varName = 'AUTO_CALIB_REAL_VALUES'";
			$Commnad = Yii::app()->db->createCommand($Query);
			$Result = $Commnad->queryRow();
			$settings['useRealValues'] = $Result['varValue'];

            $arLab = array_slice(LabUtility::getColumn($scaleData['lab'], $item), 0, 15);

            if (in_array($item, $spArrray)) {

                $arAnalyzer = Labutility::getSpValue($scaleData['ana'], $item);
            } else {

                $arAnalyzer = $scaleData['ana'][$item];
            }

            $scaleList['current'][$item]['type'] = $caliConfig->readEntry("type");
            $scaleList['current'][$item]['gain'] = $caliConfig->readEntry("gain");
            $scaleList['current'][$item]['offset'] = $caliConfig->readEntry("offset");

            Logger::calibLogger(Logger::INFO, $item . " Settings ", $settings);
            Logger::calibLogger(Logger::INFO, $item . ' Sending Analyzer Records, Array is', $arAnalyzer);
            Logger::calibLogger(Logger::INFO, $item . ' Sending Lab Records, Array is', $arLab);

            //	Logger::calibLogger(Logger::INFO,'calculateScale Data',array('lab' =>$arLab , 'analyzer' => $arAnalyzer ));
            //var_dump(array($arLab, $arAnalyzer));
            $response = $scaleParamsObj->calculateOffset($arLab, $arAnalyzer, $settings);


            if ($response['found']) {


                $offset = $response['offset'];
            } else {

                $offset = 0;
            }


            //$offset = 1;


            Logger::calibLogger(Logger::INFO, 'calculateScale items => ' . $item, array($item => $offset));

            $oldOffset = $caliConfig->readEntry("offset");
            $validOffset = round($offset,2);// $this->agumentScale($item, $offset, $oldOffset);

            if ($response['found']) {
                $scaleList['proposed'][$item]['type'] = 'output';
                $scaleList['proposed'][$item]['gain'] = 1;
                $scaleList['proposed'][$item]['offset'] = $validOffset; //$offsets[$item]/*$caliConfig->readEntry("offset") */ ;
                $scaleList['proposed'][$item]['offset_raw'] = $offset; //$offsets[$item]/*$caliConfig->readEntry("offset") */ ;

                $scaleList['proposed'][$item]['alignmentPercentage'] = 100;
            } else {
               // $scaleList['proposed'][$item]['alignmentPercentage'] = $cal['r2'];
              //  $scaleList['proposed'][$item]['gain'] = 1;
               // $scaleList['proposed'][$item]['error'] = $cal['error'];
            }
        }


        return $scaleList;
    }

    public function agumentScale($ele, $offset, $oldOffset = null) {


        $diffKey = $ele . '_diff';
        $percentageKey = $ele . '_offset';

        $agumentedOffset = isset($oldOffset) ? $oldOffset + $offset : $offset + 0;

        $absValue = abs($agumentedOffset);

        $diffValue = Yii::app()->params[$diffKey];
        $percentageValue = Yii::app()->params[$percentageKey];



        if ($absValue > $diffValue) {


            $newOffset = round($agumentedOffset * $percentageValue, 2);
            Logger::calibLogger(Logger::INFO, '-- Offset Final Adjustment of ' . $ele, array('Given' => $oldOffset, 'Updated' => $newOffset));

            return $newOffset;
        } else {

            Logger::calibLogger(Logger::INFO, '-- Offset Final Adjustment of ' . $ele, array('Given' => $oldOffset, 'Updated' => $newOffset));

            return $newOffset;
        }
    }

    public function scaleGraph($lab, $analysis, $scaleList) {

        $series = array();

        $proposed = $scaleList['proposed'];
        $timeStamp = array_keys($lab); //Time stamp


        foreach ($timeStamp as $time) {
            $labRow = $lab[$time];
            $anRow = $analysis[$time];
            foreach ($proposed as $ele => $propAr) {

                $timeStamp = strtotime($time) * 1000;
                $labVal = (float) $labRow[$ele];

                $tAl2O3 = $anRow['Al2O3'];

                $tSiO2 = $anRow['SiO2'];
                

                $tFe2O3 = $anRow['Fe2O3'];

                $tCaO = $anRow['CaO'];


                //Calculate formulas
                if ($ele == 'KH') {
                    $anVal = (($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2));
                } else if ($ele == "IM") {
                    $anVal = round(( $tAl2O3 /
                            $tFe2O3), 3);
                } else if ($ele == "AM") {
                    $anVal = round(( $tAl2O3 /
                            $tFe2O3), 3);
                } else {

                    $anVal = (float) $anRow[$ele];
                }


                $offset = $propAr['offset'];

                $series[$ele]['an'][] = array($timeStamp, $anVal);
                $series[$ele]['lab'][] = array($timeStamp, $labVal);
                $series[$ele]['offset'][] = array($timeStamp, round($offset + $anVal, 2));
            }
        }

        return $series;
    }

    public function prepareScaleData($lab_hist_id, $labTimes) {


        $historyQuery = "SELECT * FROM lab_data_history where lab_hist_id = '" . $lab_hist_id . "' order by start_time limit 1";

        $oDbConnection = Yii::app()->db;
        $oCommand = $oDbConnection->createCommand($historyQuery);
        $labHistoryEntry = $oCommand->queryRow(); // Run query and get all results in a CDbDataReader
        $historyData = json_decode($labHistoryEntry['data']);

        $templateModel = LabTemplate::model()->find('template_id = :template_id', array(':template_id' => $labHistoryEntry['template_id']));


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


        $labHistorData = array();
        $temp1 = array();
        foreach ($labTimes as $time) {

            $val = Yii::app()->db->createCommand('select * from lab_history_data  where EndTime = "' . $time . '" ')->queryRow();
            $labHistoryData[] = $val;

            $temp1[$time] = $val;
        }

        $analysisObject = new AnalysisHistoryFactory($labTimes, 'analysis_A1_A2_Blend');
        $analysisObject->getRecords();


        $avgDataVector = array();


        foreach ($analysisObject->analysisAvg as $key => $row) {
            $count = 0;
            foreach ($row as $col => $colvalue) {

                $avgDataVector[$col][] = $colvalue;
            }
        }


        return array('lab' => $labHistoryData, 'ana' => $avgDataVector, 'labData' => $temp1, 'anaData' => $analysisObject->analysisAvg);
    }

    public function parseCsvDataFile($rawdata, $activeTemplateModel) {
        //pawan
        $lookUpdata = $this->lookUp;
        $rawData = array_map('str_getcsv', $rawdata);
        $table['elements'] = explode(',', $activeTemplateModel->columns);
        $table['lookup'] = $lookUpdata;
        $table['ele_data'] = $rawdata;


        return $table;
    }

    public function buildInsertDataQuery($data, $datatable) {



        $colType = Yii::app()->db->createCommand('describe ' . $datatable . ' ')->queryAll();

        foreach ($colType as $key => $value) {

            $cLookup [$value['Field']] = $value;
        }


        $labHistory = new LabDataHistory();
        $activeTemplateModel = LabTemplate::model()->find('status = :status', array(':status' => 1));
        $elements = $data['elements'];
        $values = $data['ele_data'];

        $valuesString = array();


        foreach ($values as $row) {

            $fData = array();
            $tmpData = explode(',', $row);


            foreach ($elements as $key => $col) {

                $index = $this->normalise($col);
                $cType = $cLookup[$index];

                $eleType = $cType['Type'];


                $pos = strpos($eleType, 'decimal');

                if ($pos === 0) {
                    $eleType = 'decimal';
                }

                if ($eleType == 'datetime' || $eleType == 'timestamp' || $eleType == 'EndTime') {

                    $dateString = str_replace('/', '-', $tmpData[$key]);
                    //echo $dateString;
                    //var_dump(strtotime($dateString));
                    $fData[] = date('Y-m-d H:i:s', strtotime($dateString));
                } else if ($eleType == 'decimal') {

                    $formatted = sprintf("%01.2f", $tmpData[$key]);
                    $fData[] = $formatted;
                } else {

                    $fData[] = $tmpData[$key];
                }
            }// End of foreach



            $dataEntry = $result = "'" . implode("', '", $fData) . "'";
            $tmpQuery = "(" . $dataEntry . ")";

            $valuesString[] = $tmpQuery;
        }// end of foreach


        $lookUpEle = array();
        foreach ($elements as $index => $col) {
            $lookUpEle[] = $this->normalise($col);
        }
        $eleSubQuery = "INSERT INTO   `$datatable` (" . implode(',', $lookUpEle) . ")";

        $valuesQuery = implode(", ", $valuesString);

        $insertQuery = $eleSubQuery . " VALUES " . $valuesQuery;




//        $this->debuger([$insertQuery]);
        return $insertQuery;
    }

    public function actionSetLabTime() {


        if (isset($_REQUEST['sampling_time']) && isset($_REQUEST['fallback_time'])) {

            $samplingTime = $_REQUEST['sampling_time'];
            $falBackTime = $_REQUEST['fallback_time'];
            //LAB_SAMPLING_TIME  LAB_FALLBACK_TIME
            $updateQuery = "update rm_settings set varValue   = '$samplingTime' WHERE varName ='LAB_SAMPLING_TIME'";
            $command = Yii::app()->db->createCommand($updateQuery);
            $sResult = $command->query();

            $updateQuery = "update rm_settings set varValue   = '$falBackTime' WHERE varName ='LAB_FALLBACK_TIME'";
            $command = Yii::app()->db->createCommand($updateQuery);
            $fResult = $command->query();
        }

        if (isset($_REQUEST['history_sampling_time']) && isset($_REQUEST['history_fallback_time'])) {

            $samplingTime = $_REQUEST['history_sampling_time'];
            $falBackTime = $_REQUEST['history_fallback_time'];
            //LAB_SAMPLING_TIME  LAB_FALLBACK_TIME
            $updateQuery = "update rm_settings set varValue   = '$samplingTime' WHERE varName ='LAB_HISTORY_SAMPLING_TIME'";
            $command = Yii::app()->db->createCommand($updateQuery);
            $sResult = $command->query();

            $updateQuery = "update rm_settings set varValue   = '$falBackTime' WHERE varName ='LAB_HISTORY_FALLBACK_TIME'";
            $command = Yii::app()->db->createCommand($updateQuery);
            $fResult = $command->query();
        }
    }

    public function checkForValidFile() {

        $response = array('status' => 1, 'error' => array());


        if (count($_FILES) > 0) {//check for empty file
            foreach ($_FILES as $file):
                if (0 && $file['type'] !== 'text/csv') {
                    $response['status'] = 0;
                    $response['error'] = 'Invalid File Type Please Upload .csv file';
                } else if ($file['size'] <= 1) {
                    $response['status'] = 0;
                    $response['error'] = 'Empty file, please upload correct data';
                }

            endforeach;
        } else {
            $response = array('status' => 0, 'error' => array('Empty file'));
        }

        return $response;
    }

    public function loadXlData($inputFileName) {


        // Turn off our amazing library autoload 
        spl_autoload_unregister(array('YiiBase', 'autoload'));


        Yii::import('ext.PHPExcel.Classes.PHPExcel', true);


        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);


        // Once we have finished using the library, give back the 
        // power to Yii... 
        spl_autoload_register(array('YiiBase', 'autoload'));

        return $sheetData;
    }

    public function checkTempleteExits() {


        $tableSchema = Yii::app()->db->schema->getTableSchema('lab_data');


        if ($tableSchema) {
            $templateFlag = true;
        } else {
            $templateFlag = false;
        }


        return $templateFlag;
    }

    public function convertXlsDataToCsvData($xlsData, $skipCol = array(), $skipRow = array()) {



        $csvArray = array();

        foreach ($xlsData as $key => $row) {

            //skip row 

            if (in_array($key, $skipRow))
                continue;

            //skip col

            foreach ($skipCol as $val) {

                unset($row[$val]);
            }

            //To filter empty string
            $emptyString = implode('', $row);


//            echo strlen($emptyString)."</br>";
            if (strlen($emptyString) <= 20) {
                continue;
            }


            $csvArray[] = implode(',', $row);
        }

//        die();
        return $csvArray;
//        $this->debuger($csvArray);
    }

    public function parseCsvfile($csvData) {


        $csvData = array_map('str_getcsv', $csvData);


        $skipRow = 2; // $csvData[0][1] ; // Skip row
        $skipCol = 1; //$csvData[1][1]; // Skip col


        $table = array();
        $column = array();
        $types = array();

        $table['skipRow'] = $csvData[0][1];
        $table['skipCol'] = $csvData[1][1];
        $table['skipCells'] = $csvData[2][1];
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

    public function buildCreateTableQuery($table, $tname) {

        $columns = array();
        //indexing 
        $columns[] = "`lab_data_id`  int NOT NULL  AUTO_INCREMENT PRIMARY KEY";

        foreach ($table['col'] as $key => $name) {

            $columnName = $this->normalise(trim($name));
            $type = $this->normalise($table['type'][$key], 'type');

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

            //pawan removed TYPE

            $sQuery = "`{$columnName}`  {$type}  ";
            $columns[] = $sQuery;
        }

        $columnQuery = implode(",", $columns);

        $tmpArray = "";

        $query = "DROP TABLE IF EXISTS `$tname`; CREATE TABLE `$tname` ({$columnQuery} $tmpArray );";

        return $query;
    }

    public function normalise($string, $type = false) {

        $tempStr = strtolower($string);


        if (strpos($tempStr, 'date') !== false && $type !== 'type') {

            $string = 'EndTime';
        }
        $variable = str_replace('', '_', $string); // Replaces all spaces with hyphens.
        $normString = preg_replace('/[^A-Za-z0-9\_]/', '', $variable); // Removes special chars.

        return $this->lookuptable($normString);
    }

    public function lookuptable($key) {

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
            'AluminaModulus' => 'AM',
            'IronModulus' => 'IM',
            'TricalciumSilicateContentASTM' => 'C3S',
            'TricalciumAluminateContentASTM' => 'C3A',
            'LimeSaturationFactor' => 'LSF',
            'SilicaModulus' => 'SM',
            'DicalciumSilicateContentASTM' => 'C2S',
            'TetracaliumAluminoferriteContentASTM' => 'C4AF',
            'SodiumEquivalent' => 'NaEQ',
            'N' => 'SM',
            'P' => 'IM'
        );
        if (array_key_exists($key, $lookUp)) {
            return $lookUp[$key];
        } else {

            return $key;
        }
    }

    public function calculateOffset($labData, $analysisData, $elements) {


        $offset = array();
        foreach ($labData as $row) {


            foreach ($elements as $ele) {

                if (isset($analysisData[$row['EndTime']][$ele])) {
                    $diff = $row[$ele] - $analysisData[$row['EndTime']][$ele];
                    $offset[$ele][] = round($diff, 2);
                }
            }
        }


        $offsetAvg = array();

        foreach ($offset as $key => $eleOffsets) {
            $avgOffset = array_sum($eleOffsets) / count($eleOffsets);
            $offsetAvg[$key] = round($avgOffset, 2);
        }
        return $offsetAvg;
    }
   
/**
 * 
 * @param string $values
 * @param string $timestamp
 * @return boolean
 */
    public function isDuplicateEntry($values, $timestamp) {


        $tmpArry = explode(',', $values);

        $dateString = date('Y-m-d H:i:s',strtotime($tmpArry[0]));

        if ( $dateString === $timestamp) {

            return true;
        } else {

            return false;
        }
    }

}
