<?php

class TruckInfoController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
        public $layout = '//layouts/column1';

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
				'actions'=>array('create','update','unloading','tolerance','UpdateChlQty'
                                    . ''),
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
		$model=new TruckInfo;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TruckInfo']))
		{
			/*
			$_POST['TruckInfo']["w_timestamp"] = $_POST['TruckInfo']["w_dateVal"] . " " . $_POST['TruckInfo']["w_timestamp"] . ":00";
			unset($_POST['TruckInfo']["w_dateVal"]);
			$_POST['TruckInfo']["w_timestamp"] = date("Y-m-d H:i:s",strtotime($_POST['TruckInfo']["w_timestamp"]));		
			*/
			$model->attributes=$_POST['TruckInfo'];
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->w_tripID));
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TruckInfo']))
		{
			$model->attributes=$_POST['TruckInfo'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->w_tripID));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

        /**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUnloading()
	{
		$this->render('unloading');
	}
        
        public function actionTolerance()
	{
		$this->render('tolerance');
	}
        public function actionUpdateChlQty()
	{
            
//            die();
            $challenQty =  $_REQUEST['ChaQty'];
            $tripId     =  $_REQUEST['tripId'];
            $model =  $this->loadModel($tripId);
            $model->w_chQty = $challenQty;
            
            if($model->save()){
                return "Updated";
            }else{
                return "Error";
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
		$dataProvider=new CActiveDataProvider('TruckInfo');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
               
                $sort = new CSort();
                $sort->defaultOrder=array('w_timestamp' => CSort::SORT_DESC);
                $dataProvider=new CActiveDataProvider('TruckInfo',array( 'sort'=>$sort,));

		$model=new TruckInfo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TruckInfo']))
			$model->attributes=$_GET['TruckInfo'];

		$this->render('admin',array(
			'model'=>$model,
                         'dataProvider' => $dataProvider
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TruckInfo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TruckInfo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TruckInfo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='truck-info-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
