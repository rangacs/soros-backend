<?php

class MonitorController extends BaseController {

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'ajaxUp', 'dash', 'setinterval', 'SubmitRollingForm', 'SubmitCumulativeForm', 'SubmitIntervalForm'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array("@"),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {


        
        $intervalHelper   = new IntervalHelper();
        $cumulativeHelper = new CumulativeHelper();
        $rollingHelper    = new RoolingHelper();

        
//        $array = array('time' => array('key' => ),'tons' => array());

        $intervalHelper->init();
        $cumulativeHelper->init();
        $rollingHelper->init();

        $this->render('monitor', array('intervalHelper' => $intervalHelper,
            'cumulativeHelper' => $cumulativeHelper,
            'rollingHelper' => $rollingHelper));
    }

    public function actionDash() {

        
        $sytemArray = $this->getMonitorInterval();
        
        foreach($sytemArray as $intervalIndex => $interval){
            
            $interval['unit'] = isset($_SESSION[$intervalIndex]) ? $_SESSION[$intervalIndex] : $interval['unit'];
            $intervalArray[$intervalIndex] = $interval;
            
        }
       
        $elementArray =  HeliosUtility::getDisplayElements();; 
        $monitorObject = new AnalysisDataProvider($intervalArray);

        $monitorObject->setElements($elementArray);


        $this->render('monitor-dash', array('monitorObject' => $monitorObject, 'intervalArray' => $sytemArray));
    }
    
    public function getMonitorInterval(){
        
        
 //       $jsonString = '{"interval-0":{"unit":60,"type":"time"},"interval-1":{"unit":120,"type":"time"},"interval-2":{"unit":180,"type":"time"},"interval-3":{"unit":240,"type":"time"},"interval-4":{"unit":300,"type":"time"},"interval-5":{"unit":360,"type":"time"},"interval-6":{"unit":420,"type":"time"},"interval-7":{"unit":480,"type":"time"},"interval-8":{"unit":960,"type":"time"},"interval-9":{"unit":1440,"type":"time"},"interval-10":{"unit":4320,"type":"time"},"interval-11":{"unit":10000,"type":"tons"},"interval-12":{"unit":20000,"type":"tons"},"interval-13":{"unit":30000,"type":"tons"},"interval-14":{"unit":40000,"type":"tons"}}';
   $jsonString = '{"interval-0":{"unit":30,"type":"time"},"interval-1":{"unit":60,"type":"time"},
		"interval-2":{"unit":120,"type":"time"},"interval-3":{"unit":180,"type":"time"},
		"interval-4":{"unit":240,"type":"time"},"interval-5":{"unit":480,"type":"time"},
		"interval-6":{"unit":960,"type":"time"},"interval-7":{"unit":1440,"type":"time"},
		"interval-8":{"unit":10000,"type":"tons"},"interval-9":{"unit":20000,"type":"tons"}}';     
       // $intervalStringResult = Yii::app()->db->createCommand("select varValue from rm_settings where varKey ='MONITOR_INTERVAL_RANGE'")->queryScalar();
        //jsonString = $intervalStringResult !=false ? $intervalStringResult : $intervalStringCONST;
        
        $intervalArray = json_decode($jsonString,true);
        return $intervalArray;
    }
    

    public function actionSetInterval($name, $value) {


        $_SESSION[$name] = $value;
    }

    public function actionSubmitRollingForm() {

        $_SESSION['rolling_minute'] = $_REQUEST['rollingMin'];
    }

    public function actionSubmitCumulativeForm() {


        $dateString = $_REQUEST['cumulative_start_time'];
        $_SESSION['cumulative_start_time'] = date('Y-m-d H:i:s', strtotime($dateString));
    }

    public function actionSubmitIntervalForm() {

       // session_start();
        $startTime = $_REQUEST['interval_start_time'];
        $endTime = $_REQUEST['interval_end_time'];
        $startTimeTick = strtotime($startTime);
        $endTimeTick = strtotime($endTime);
        $_SESSION['interval_end_time'] = date('Y-m-d H:i:s',$endTimeTick );
        
        $_SESSION['interval_start_time'] = date('Y-m-d H:i:s', $startTimeTick);
        
        $tmp1 =  $_SESSION['interval_end_time'];
        $tmp2 =  $_SESSION['interval_start_time'];
        
    }

    // Uncomment the following methods and override them if needed
    /*
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
