<?php

class SetPointsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
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
		$model=new SetPoints;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SetPoints']))
		{
			$model->attributes=$_POST['SetPoints'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->sp_id));
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
	public function actionUpdate()
	{
            
          //  spID : spID ,spTolerence:spTolerence , spName : spName
                
                $spID = $_REQUEST['spID'];
                
                
		$model=$this->loadModel($spID);
                
                

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($spID))
		{
                    $model->sp_value_num = $_REQUEST['spValue'];
                    $model->sp_tolerance_ulevel = $_REQUEST['spTolerence'];
			if($model->save()){
                            
                        }
				//$this->redirect(array('view','id'=>$model->sp_id));
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
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('SetPoints');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='set-points-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
