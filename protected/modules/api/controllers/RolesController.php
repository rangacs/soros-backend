<?php

class RolesController extends BaseController
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
		$model=new Roles;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$columnData = $this->request;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model->role = $columnData['role'];
		$model->status = 1;

		
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

		$model->role = $columnData['role'];
		$model->status = 1;

		
		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Created Succesfully'));
		}else{
//			var_dump($model->errors);
//		die();
			$this->sendFailedResponse(array('message'=>$model->errors));
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
		->from('roles')
		->queryAll();
		$this->sendSuccessResponse(array('data'=>$result));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Roles('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Roles']))
			$model->attributes=$_GET['Roles'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Roles the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Roles::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Roles $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='role-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}


