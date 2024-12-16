<?php

class DataBoundriesController extends BaseController
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
		$columnData = $this->request;

		$model=new DataBoundries;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->element_name = $columnData['element_name'];
		$model->min = $columnData['min'];
		$model->max = $columnData['max'];
		$model->valid_datapoint_range = $columnData['valid_datapoint_range'];
		$model->diff = $columnData['diff'];
		$model->min_real = $columnData['min_real'];
		$model->max_real = $columnData['max_real'];
		$model->acceptable_deviation = $columnData['acceptable_deviation'];
		$model->actionable_current_deviation = $columnData['actionable_current_deviation'];
		$model->big_deviation = $columnData['big_deviation'];
		$model->max_offset_change = $columnData['max_offset_change'];
		$model->correction_pct = $columnData['correction_pct'];
		$model->correction_percentage_till_cron = $columnData['correction_percentage_till_cron'];

		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Created Succesfully'));
		}else{
			$this->sendFailedResponse(array('message'=>$model->errors));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$columnData = $this->request;
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->element_name = $columnData['element_name'];
		$model->min = $columnData['min'];
		$model->max = $columnData['max'];
		$model->valid_datapoint_range = $columnData['valid_datapoint_range'];
		$model->diff = $columnData['diff'];
		$model->min_real = $columnData['min_real'];
		$model->max_real = $columnData['max_real'];
		$model->acceptable_deviation = $columnData['acceptable_deviation'];
		$model->actionable_current_deviation = $columnData['actionable_current_deviation'];
		$model->big_deviation = $columnData['big_deviation'];
		$model->max_offset_change = $columnData['max_offset_change'];
		$model->correction_pct = $columnData['correction_pct'];
		$model->correction_percentage_till_cron = $columnData['correction_percentage_till_cron'];
		
		
		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Updated Succesfully'));
		}else{
		
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
		->from('data_boundries')
		->queryAll();
		$this->sendSuccessResponse(array('data'=>$result));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DataBoundries('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DataBoundries']))
			$model->attributes=$_GET['DataBoundries'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DataBoundries the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DataBoundries::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DataBoundries $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='data-boundries-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
