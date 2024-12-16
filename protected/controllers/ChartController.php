<?php

class ChartController extends Controller
{
	public function actionIndex()
	{
		$this->render('Index');
	}
  
  
  public function actionChartView()
  { 
   if(isset($_GET['json']) && $_GET['json'] == 1)
   {
    $model = new ChartData;
    
    $solo = ChartData::model()->findByPk(1);
    $solo->data = 3;
    $solo->save();
    
    $dataProvider = $model->retrieveAll();
    
    /*
    $count = ChartData::model()->count();              //By default, I think count pulls all records from db table..
    for($i=1; $i<=$count; $i++){
     $data = ChartData::model()->findByPk($i);
     $data->data += rand(-10,10);
     $data->save();
    }
    */
    
    echo CJSON::encode($dataProvider->getData());  //Returning the json back to the success handler of activehighstock ajax call..
    
   }
   elseif(isset($_GET['json']) && $_GET['json'] == 5)
   {
    /*
    *  Development Test variables:    
    *   @return  Limits the retrieve to only 4/5 records in the table..
    */        
    //$start_time = "1333674685";         
    //$end_time   = "1333274685";
    
    /*
    *  Production variables:
    *   @start_time  timestamp  Current time..
    *   @end_time    timestamp  Already occurred time..        
    *   @return  Retrieves all records (if exists) in a 5min time span: 
    *      - From now THROUGH 5min ago.
    *      - (NOT from now to 5min in the future..)          
    */ 
    $model = new ChartData;   
    $start_time = $_SERVER['REQUEST_TIME'];   
    $end_time = $start_time - 300;           
    
    $dataProvider = $model->m_fiveMinLogic($start_time, $end_time);
    
    echo CJSON::encode($dataProvider->getData());  //Returning the json back to the success handler of activehighstock ajax call..
    
   }
   elseif(isset($_GET['json']) && $_GET['json'] == 10)
   {
    /*
    *  Development Test variables:    
    *   @return  Limits the retrieve to only 4/5 records in the table..
    */        
    //$start_time = "1333674685";         
    //$end_time   = "1333274685";
    
    /*
    *  Production variables:
    *   @start_time  timestamp  Current time..
    *   @end_time    timestamp  Already occurred time..        
    *   @return  Retrieves all records (if exists) in a 5min time span: 
    *      - From now THROUGH 5min ago.
    *      - (NOT from now to 5min in the future..)          
    */ 
    $model = new ChartData;   
    $start_time = $_SERVER['REQUEST_TIME'];   
    $end_time = $start_time - 600;           
    
    $dataProvider = $model->m_tenMinLogic($start_time, $end_time);
    
    echo CJSON::encode($dataProvider->getData());  //Returning the json back to the success handler of activehighstock ajax call..
    
   }
   elseif(isset($_GET['json']) && $_GET['json'] == 'auto')
   {
    $model = new ChartData;
    
    $solo = $model->findByPk(1);
    $solo->data += rand(-10,10);
    $solo->save();
    
    $dataProvider = $model->retrieveAll();
    echo CJSON::encode($dataProvider->getData());
   }
   elseif( !isset($_GET['json']) )
   {
    $model = new ChartData;
    $dataProvider = $model->retrieveAll();       //Only retrieveAll() records upon page load..
   
    $this->render(
             'ChartView',
              array(
               'dataProvider' => $dataProvider,
              )
    );
   }
    
  }//end actionChartView()..
  
  
  public function actionChartHandler()
  {
   if( isset($_POST['package_5min']) )
   {
    $pb = explode( "&", $_POST['package_5min'] );
    $rp = array();
    
    for($i=0; $i<count($pb); ++$i){
     preg_match('/(.*)=/', $pb[$i], $key_arr);
     preg_match('/=(.*)/', $pb[$i], $val_arr);
     $rp[ $key_arr[1] ] = $val_arr[1];
    }
    
    //Nov12th..
    $model        = new ChartData;
    $current      = date_create();                   ////
    $dataProvider = $model->retrieve5min($current);
    
    //echo CJSON::encode($dataProvider->getData());
    echo CJSON::encode( array(
                         'dataProvider' => $dataProvider,
                        )
                      );
    
    //echo CHtml::encode( $rp['status_B'] );
   }
   
  }//end actionChartHandler()..
  
  
  public function actionServerTouch()
  {
   if($_POST['stop_poll'] == "yes")
   {
    echo CJSON::encode("stop_please");
   }
   return true;
  }//end actionPullTest()..


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