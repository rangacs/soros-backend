<?php

class MonitorColumnController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';


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
		
		$model=new MonitorColumns;

		$model->type = $columnData['type'];
		$model->display_name = $columnData['display_name'];
		$model->value = $columnData['value'];
		$model->position = $columnData['position'];
		
		if($model->save()){
			$this->sendSuccessResponse(array('message'=>'Created Succesfully'));
		}else{
			var_dump($model->errors);
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

		$model->type = $columnData['type'];
		$model->display_name = $columnData['display_name'];
		$model->value = $columnData['value'];
		$model->position = $columnData['position'];
		
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

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		$this->sendSuccessResponse(array('message'=>'Deleted successfully'));

	}

	/**
	 * Lists all models.
	 */
	public function actionColumns()
	{
		
		$result = Yii::app()->db->createCommand()
		->select()
		->from('monitor_columns')
		->queryAll();
		$this->sendSuccessResponse(array('data'=>$result));
	}
        
        public function actionDropdowns()
	{
		
		$result = Yii::app()->db->createCommand()
		->select()
		->from('monitor_columns_dropdown')
		->queryAll();
                
		$this->sendSuccessResponse(array('data'=>$result));
	}

        
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new MonitorColumns('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['MonitorColumns']))
			$model->attributes=$_GET['MonitorColumns'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return MonitorColumns the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=MonitorColumns::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param MonitorColumns $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='moniter-columns-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
