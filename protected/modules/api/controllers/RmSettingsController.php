<?php

class RmSettingsController extends BaseController
{
	
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
		$model=new RmSettings;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$columnData = $this->request;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->varName = $columnData['varName'];
		$model->varKey = $columnData['varKey'];
		$model->varValue = $columnData['varValue'];
		$model->category = $columnData['category'];
		
		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Created Succesfully'));
		}else{
			
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

		$model->varName = $columnData['varName'];
		$model->varKey = $columnData['varKey'];
		$model->varValue = $columnData['varValue'];
		$model->category = $columnData['category'];
		
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
		->from('rm_settings')
		->queryAll();
		$this->sendSuccessResponse(array('data'=>$result));
	}
	public function actionSearch()
	{
		$searchEle = Yii::app()->request->getParam('search');
		$sql = 'select * from rm_settings where category LIKE \'%'.$searchEle.'%\' OR varName LIKE \'%'.$searchEle.'%\' OR varKey LIKE \'%'.$searchEle.'%\' OR varValue LIKE \'%'.$searchEle.'%\'';
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		$this->sendSuccessResponse(array('data'=>$result));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new RmSettings('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RmSettings']))
			$model->attributes=$_GET['RmSettings'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return RmSettings the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=RmSettings::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param RmSettings $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='rm-settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
