<?php

class RtaPhysicalConfigController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$result = $this->loadModel($id);
		$this->sendSuccessResponse(array($result));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new RtaPhysicalConfig;

		$columnData = $this->request;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->rta_ID_physical = $columnData['rta_ID_physical'];
		$model->rtaMasterID = $columnData['rtaMasterID'];
		$model->IPaddress = $columnData['IPaddress'];
		$model->goodDataSecondsWeight_physicalCfg = $columnData['goodDataSecondsWeight_physicalCfg'];
		$model->massflowWeight_physicalCfg = $columnData['massflowWeight_physicalCfg'];
		$model->analysis_timespan = $columnData['analysis_timespan'];
		$model->averaging_subinterval_secs = $columnData['averaging_subinterval_secs'];
		$model->detectorID = $columnData['detectorID'];
		
		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Created Succesfully'));
		}else{
			var_dump($model->errors);
		die();
			$this->sendFailedResponse(array('message'=>'Not Created'));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		$columnData = $this->request;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->rta_ID_physical = $columnData['rta_ID_physical'];
		$model->rtaMasterID = $columnData['rtaMasterID'];
		$model->IPaddress = $columnData['IPaddress'];
		$model->goodDataSecondsWeight_physicalCfg = $columnData['goodDataSecondsWeight_physicalCfg'];
		$model->massflowWeight_physicalCfg = $columnData['massflowWeight_physicalCfg'];
		$model->analysis_timespan = $columnData['analysis_timespan'];
		$model->averaging_subinterval_secs = $columnData['averaging_subinterval_secs'];
		$model->detectorID = $columnData['detectorID'];
		
		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Updated Succesfully'));
		}else{
			var_dump($model->errors);
		die();
			$this->sendFailedResponse(array('message'=>'Not Updated'));
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->sendSuccessResponse(array('message'=>'Deleted successfully'));

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$result = Yii::app()->db->createCommand()
		->select()
		->from('rta_physical_config')
		->queryAll();
		$this->sendSuccessResponse(array('data'=>$result));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new RtaPhysicalConfig('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RtaPhysicalConfig']))
			$model->attributes=$_GET['RtaPhysicalConfig'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return RtaPhysicalConfig the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=RtaPhysicalConfig::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param RtaPhysicalConfig $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='rta-physical-config-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
