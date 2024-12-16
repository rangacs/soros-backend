<?php

class RfidCalTolSettingsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new RfidCalTolSettings;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RfidCalTolSettings']))
		{
			$model->attributes=$_POST['RfidCalTolSettings'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->wcl_cal_tol_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
            $model = $this->loadByItemCode($id);
           // $model=$this->loadModel($id);
                

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RfidCalTolSettings']))
		{
                        $data = $model->attributes;
                        
                        $data1 = $_POST['RfidCalTolSettings'];
                        

                        $result = array_diff($data,$data1);
                        if(!empty($result)){
                            
                            
                            $changedItems = array_keys($result);
                            
                            $calMap = RfidCalMap::model()->find("wcl_item_code='$id'");
                            $profileName = $calMap->wcl_item_namev." (".$calMap->wcl_sabia_cal_name.") ";
                            
                            $message = " Tolerance values changed for profile ".$profileName;
                            foreach($changedItems as $key => $value){
                                
                                if($value == "wcl_cal_tol_id")
                                    continue;   
                                $message.= $value."  ".$data[$value]."=>".$data1[$value]."  ;";
                                
                                
                            }
                            //
                            
                            $wclLogModal=new WclRfidLogMessages();
                            $wclLogModal->long_descrip = $message;
                            $wclLogModal->message_type = "Log";
                            
                            $wclLogModal->timestamp = date("Y-m-d H:i:s");
                            $wclLogModal->short_descrip = " Tollerence update";
                            $wclLogModal->vehNo = "-";
                            $wclLogModal->unloaderID = "-";
                            $wclLogModal->trip_id = "-";
                            $wclLogModal->flag = 0;
                            
                            $wclLogModal->save(false);
                            $error =  $wclLogModal->errors;

                        }
			$model->attributes=$_POST['RfidCalTolSettings'];
		        $model->updated_on = date("Y-m-d H:i:s");						
			if($model->save()){
                            $absolute = Yii::app()->baseUrl;
                            $this->redirect($absolute."/rfidCalMap?id=$id");
                        }
                                
		}
                $this->renderPartial('_form', array('model'=>$model)); 
		
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('RfidCalTolSettings');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new RfidCalTolSettings('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RfidCalTolSettings']))
			$model->attributes=$_GET['RfidCalTolSettings'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return RfidCalTolSettings the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=RfidCalTolSettings::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        public function loadByItemCode($id){
            
            $model=RfidCalTolSettings::model()->find("wcl_cal_tol_item_code='$id'");
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
        }

	/**
	 * Performs the AJAX validation.
	 * @param RfidCalTolSettings $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='rfid-cal-tol-settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

