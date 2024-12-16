<?php

class SetPointsController extends BaseController
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
		$model=new SetPoints;
		$columnData = $this->request;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->product_id = $columnData['product_id'];
		$model->sp_name = $columnData['sp_name'];
		$model->sp_value_num = $columnData['sp_value_num'];
		$model->sp_measured = $columnData['sp_measured'];
		$model->sp_value_den = $columnData['sp_value_den'];
		$model->sp_const_value_num = $columnData['sp_const_value_num'];
		$model->sp_const_value_den = $columnData['sp_const_value_den'];
		$model->sp_tolerance_ulevel = $columnData['sp_tolerance_ulevel'];
		$model->sp_tolerance_llevel = $columnData['sp_tolerance_llevel'];
		$model->sp_weight = $columnData['sp_weight'];
		$model->sp_status = $columnData['sp_status'];
		$model->sp_priority = $columnData['sp_priority'];		
		
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
		$columnData = $this->request;

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->product_id = $columnData['product_id'];
		$model->sp_name = $columnData['sp_name'];
		$model->sp_value_num = $columnData['sp_value_num'];
		$model->sp_measured = $columnData['sp_measured'];
		$model->sp_value_den = $columnData['sp_value_den'];
		$model->sp_const_value_num = $columnData['sp_const_value_num'];
		$model->sp_const_value_den = $columnData['sp_const_value_den'];
		$model->sp_tolerance_ulevel = $columnData['sp_tolerance_ulevel'];
		$model->sp_tolerance_llevel = $columnData['sp_tolerance_llevel'];
		$model->sp_weight = $columnData['sp_weight'];
		$model->sp_status = $columnData['sp_status'];
		$model->sp_priority = $columnData['sp_priority'];
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
		->from('rm_set_points')
		->queryAll();
		$this->sendSuccessResponse(array('data'=>$result));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SetPoints('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SetPoints']))
			$model->attributes=$_GET['SetPoints'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SetPoints the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SetPoints::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SetPoints $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='rm-set-points-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
