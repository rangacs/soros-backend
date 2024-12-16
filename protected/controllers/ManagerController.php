<?php

class ManagerController extends Controller
{

    public function init()
    {
        parent::init();
		
        if(!empty($_GET['layout'])) $this->layout = $_GET['layout'];


		$cSettings = UISettings::getConfig(Yii::app()->user->getId());
		if(isset($cSettings['screen_resolution']))
		{
			Yii::app()->params['screen_resolution'] = $cSettings['screen_resolution'];
		}
		if(isset($cSettings['uicolor']))
		{
			Yii::app()->params['uicolor'] = $cSettings['uicolor'];
		}
		if(isset($cSettings['bodyClass']))
		{			
			Yii::app()->params['bodyClass'] = $cSettings['bodyClass'];
		}
 
		if(isset($_GET['langId']) && in_array($_GET['langId'],Yii::app()->params['langArray'])){
			Yii::app()->language = $_GET['langId'];
		}
		else if(isset($cSettings['language']))
		{
			Yii::app()->language = $cSettings['language'];
		} 
		else
		{
			Yii::app()->language = 'en';
		}

    }
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
				'users'=>array('@','root','abhi'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('root','abhi'),
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
		$model=new Layouts;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Layouts']))
		{
			$model->attributes=$_POST['Layouts'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->lay_id));
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
		//$model=$this->loadModel($id);
		$uiid = Yii::app()->user->getId();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($id))
		{
			
	        $select_current_layout = Yii::app()->db->createCommand("SELECT 
	                                                             lay_id
	                                                             FROM gadlay_layouts
	                                                            WHERE user_id = $uiid 
	                                                             AND default_layout = 1 
	                                                           ")->queryRow();

		     if(isset($select_current_layout["lay_id"]))
		     {
		     	$n_layoutid = $select_current_layout["lay_id"];
		        $update_query = "UPDATE gadlay_layouts set default_layout=0 WHERE lay_id=$n_layoutid";
		        $command = Yii::app()->db->createCommand( $update_query);
		        $command->execute(); 
		     }

		     {
		        $update_query = "UPDATE gadlay_layouts set default_layout=1 WHERE lay_id=$id";
		        $command = Yii::app()->db->createCommand( $update_query);
		        $command->execute(); 
		     }

		}
		else
			echo "ERR";

		/*
		if(isset($_POST['Layouts']))
		{
			$model->attributes=$_POST['Layouts'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->lay_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
		*/
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$uiid = Yii::app()->user->getId();
		//if(Yii::app()->request->isPostRequest)
		if(isset($id))
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
			
	        $select_current_layout = Yii::app()->db->createCommand("SELECT 
	                                                             lay_id
	                                                             FROM gadlay_layouts
	                                                            WHERE user_id = $uiid 
	                                                             AND default_layout = 1 
	                                                           ")->queryRow();
			
		     if(!isset($select_current_layout["lay_id"]))
		     {
				$n_layout = Yii::app()->db->createCommand("SELECT 
	                                                             lay_id
	                                                             FROM gadlay_layouts
	                                                             ORDER BY last_updated DESC LIMIT 1 
	                                                           ")->queryRow();

		     	$n_layoutid=$n_layout["lay_id"];
		        $update_query = "UPDATE gadlay_layouts set default_layout=1 WHERE lay_id=$n_layoutid";
		        $command = Yii::app()->db->createCommand( $update_query);
		        $command->execute(); 
		     }

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$uiid = Yii::app()->user->getId();
		$dataProvider=new CActiveDataProvider('Layouts', array(
									                    'criteria' => array(
								                        'condition' => "user_id={$uiid}")));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Layouts('search');
		$model->unsetAttributes();  // clear any default values
		$uiid = Yii::app()->user->getId();
		$dataProvider=new CActiveDataProvider('Layouts', array(
									                    'criteria' => array(
								                        'condition' => "user_id={$uiid}")));		
		
		if(isset($_GET['Layouts']))
			$model->attributes=$_GET['Layouts'];

		$this->render('admin',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Layouts::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='layouts-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
