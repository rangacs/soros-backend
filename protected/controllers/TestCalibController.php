<?php

class TestCalibController extends BaseController {

    public function init() {

        $pathinfo = pathinfo(Yii::app()->request->scriptFile);
        $uploaddir = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;
        require($uploaddir . 'defines.php');
    }

    public $layout = 'metronic-column2';

    public function actionIndex() {


        $offsetObj = new OffsetObject('Al2O3');

        $result = $offsetObj->getValidLabRecords();

        $newCalibSettings = $offsetObj->calculateScale();
    }

    public function actionAn() {



        $elemetns = array('Al2O3', 'Fe2O3', 'SiO2', 'CaO');

        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILE;
        $cfg->load($calAdjust);
        $cfg->setPath("/USRCAL");

        $calibFile = $cfg->readEntry("lib_file");
        $calibConfig = new ConfigFile();
        $calibConfig->load($calibFile);

        $content = '';

        $writeCalibrationFile = 0;
        Logger::calibLogger(Logger::INFO, 'Commented for test 2', 'runtime: ' . $content);


        foreach ($elemetns as $ele) {


            $calibConfig->setPath("/" . $ele);

            $cOffset = $calibConfig->readEntry('offset', 0.0);

            //record old offset
            $oldOffset[$ele] = $cOffset;
            $oldCalibration .= $ele . ':' . $oldOffset;
            $writeOffset[$ele] = $cOffset;

            echo "----------- Start $ele calibration offset $cOffset--------------" . PHP_EOL;
            $offsetObj = new OffsetObject($ele);

            Logger::calibLogger(Logger::INFO, "***" . $ele . ' run started', 'Finding Valid Records');

            $result = $offsetObj->getValidLabRecords();


            if ($result) {

                //Calculate Offset
                Logger::calibLogger(Logger::INFO, 'Starting Offset Calculator', '');

                $newCalibSettings = $offsetObj->calculateScale();
                //Logger::calibLog(Logger::INFO,'Old Calibration',$oldCalibration);

                if ($newCalibSettings['found']) {
                    //need to write the file
                    $writeCalibrationFile = 1;
                    //record the change
                    $writeOffset[$ele] = $newCalibSettings['offset'];
                    //update db 
                    $finalOffset .= $ele . ":" . $newCalibSettings['offset'];
                    $lastTimeQquery = "update offset_range_settings set  last_updated  ='" . date('Y-m-d H:i:s') . "' where element_name ='" . $ele . "'";

                    Yii::app()->db->createCommand($lastTimeQquery)->query();
                    $newCalibration .= $ele . ':' . $writeOffset[$ele];
                    Logger::calibLogger(Logger::INFO, "***" . $ele . ' run successful offset found', 'offset :' . $writeOffset[$ele]);
                } else {
                    //keeping old calibration
                    $writeOffset[$ele] = $cOffset;
                    Logger::calibLogger(Logger::INFO, "***" . $ele . ' run successful but offset not found', 'offset calculator returned old offset');
                }
            } else {
                Logger::calibLogger(Logger::INFO, "***" . $ele . ' run unsuccessful', 'Nothing to be done, no valid records found');
            }
            //update the buffer for file writing
            $content .= "[$ele]" . PHP_EOL;
            $content .= "type=output" . PHP_EOL;
            $content .= "gain=1" . PHP_EOL;
            $content .= "offset=" . $writeOffset[$ele] . PHP_EOL;

            Logger::calibLogger(Logger::INFO, "*** for complete " . $ele, '');

            echo "----------- End $ele calibration --------------<br>";
        }//for ends

        Logger::calibLogger(Logger::INFO, 'Writing Calibration file', 'runtime: ' . $content);

        if ($writeCalibrationFile) {

            Logger::calibLog(Logger::INFO, 'Old Calibration', $oldCalibration);
            Logger::calibLog(Logger::INFO, 'New Calibration', $newCalibration);
            var_dump('Old Calibration', $oldCalibration);
            var_dump('New Calibration', $newCalibration);

            $calibLog->updated_by = Yii::app()->user->id ? Yii::app()->user->id : 1;

            $fp = fopen($calibFile, 'w');

            $result = fwrite($fp, $content);

            $calibLog->save(false);
        }

        Logger::calibLogger(Logger::INFO, 'Ending Autocalibration', 'runtime: ' . $current_time);
    }

    // Uncomment the following methods and override them if needed
    /*

      public function init(){

      $pathinfo = pathinfo(Yii::app()->request->scriptFile);
      $uploaddir = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;
      require($uploaddir . 'defines.php');
      }
      public function actionAuto()
      {
      $autoCalibObject = new AutoCalibration();

      echo 'Debug';

      die();
      //$autoCalibObject->getValidLabRecords();


      if(count($autoCalibObject->labRecords) < 2)
      {

      Logger::calibLogger(Logger::INFO,'Not enough lab records found exiting auto calculation ', 'Only '.count($autoCalibObject->labRecords). ' Records Found');
      //$this->sendFailedResponse('Not enough record found');
      exit();

      }else{

      Logger::calibLogger(Logger::INFO,'Lab records found', count($autoCalibObject->labRecords). '  Lab Records Found');


      }
      $autoCalibObject->getAnalysisData();

      $autoCalibObject->calculateScale();

      $autoCalibObject->saveAutoCalib();

      }

      public function  actionOffset(){

      //$elements = array('SiO2','Al2O3','Fe2O3','CaO');

      echo 8;
      die()

      //$offsetObj = new OffsetObject($ele);

      var_dump($elements);
      //$offsetObj->getValidLabRecords();
      //$offsetObj->calculateScale();
      //$offsetObj->saveAutoCalib();


      foreach($elements as $ele){


      $offsetObj = new OffsetObject($ele);
      $offsetObj->getValidLabRecords();
      $offsetObj->calculateScale();
      $offsetObj->saveAutoCalib();

      }

      }
      public function actionWrite(){



      $autoCalibObject =  new AutoCalibration();
      $autoCalibObject->saveAutoCalib();


      }
      public function actionLog(){



      $messageLog =   CalibrationLogMessages::model()->find();

      $this->sendSuccessResponse($messageLog);

      }

      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
