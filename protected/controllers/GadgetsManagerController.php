<?php

class GadgetsManagerController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
				'users'=>array('@'),
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
		$model=new GadgetsData;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GadgetsData']))
		{
			$model->attributes=$_POST['GadgetsData'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->gadget_data_id));
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

		if(isset($_POST['GadgetsData']))
		{
			$model->attributes=$_POST['GadgetsData'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->gadget_data_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$user_id = Yii::app()->user->getId();
		
		if(isset($id))
		{
			$lid = $_REQUEST['lid'];
			$gid = $_REQUEST['gid'];
			$plid = $_REQUEST['plid'];

			$model=Layouts::model()->findByPk($lid);

			/*
			Array
			(
			    [gadPlacement] => a:3:{s:12:"columnsCount";s:1:"0";s:10:"widgetsPos";a:1:{i:0;a:3:{i:0;s:6:"Charts";i:1;s:6:"Charts";i:2;s:6:"Charts";}}s:9:"widString";s:27:"Charts:6;Charts:6;Charts:6;";}
			)
			*/
		    $pref = Yii::app()->db->createCommand("SELECT 
		                                          gadPlacement
		                                          FROM gadlay_layouts
		                                          WHERE user_id = '$user_id'
                                             	  AND lay_id =$lid ")->queryRow();

		  
		  $idList = ( ($pref["gadPlacement"] === null) ? '' : unserialize( stripslashes($pref["gadPlacement"]) ) );
		  $idList_json_str     = CJSON::encode($idList);
		  $idList_json_obj     = json_decode($idList_json_str);
		  $temp_arr = array(); 
		  $perm_arr = array();
		  
		  if( is_object($idList_json_obj) )
		  {
		  
		   $i = 0;
		   foreach($idList_json_obj as $k1 => $v1)
		   {
			if($k1 == 'widgetsPos')
			{
			 foreach($v1[$i] as $k2 => $v2)
			 {
			  $perm_arr['widgetsPos'][$k2] = ( $v2 );     //Pre-gadget returns an array..
			 }//foreach
			}//if widgetpos
			if($k1 == 'widString')
			{
			 
			 $temp_arr['widString'] = substr($v1, 0, -1);
			 $widString_arr = explode(';', $temp_arr['widString']);
			 
			 //foreach parse I.
			 foreach($widString_arr as $k1 => $v1)
			 {
			  $perm_arr['widString'][$k1] = $v1;      // Abhinandan. June6th:  [] represents the widgetsPos..
			 }//foreach
			 
		 
			}//if widstring
			
		   }//foreach
		 }//if idList_json_obj                                            

		 $widposAr = array();
		 $widposAr = $perm_arr["widgetsPos"];
		 unset($widposAr[$plid]);
		 $widposAr=array_values($widposAr);
		 $widstrAr = array();
		 $widstrAr = $perm_arr["widString"];
		 unset($widstrAr[$plid]);
		 $widstrAr=array_values($widstrAr);
		 $widStringVal = "";
		 
		 $newArray = array();
		 $newArray["columnsCount"]='0';
		 $newArray["widgetsPos"][0]=$widposAr;
		 
		 foreach($widstrAr as $val) $widStringVal .=$val.";";
		 
		 $newArray["widString"]=$widStringVal ;
		 $newSerStr = serialize($newArray);
		 			
        	//Remove All Dependcies
	        $update_query = "DELETE FROM `gadlay_elements` WHERE `gadlay_elements`.`gadget_data_id`='{$gid}'";
	        $command = Yii::app()->db->createCommand( $update_query);
	        $command->execute(); 
		
			//gadlay_layouts;
			
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

	        $update_query = "UPDATE gadlay_layouts 
	                          SET `gadPlacement` = '".$newSerStr."' 
	                           WHERE user_id = '$user_id' AND lay_id =$lid ";
	        $command = Yii::app()->db->createCommand( $update_query);
	       
	        $command->execute(); 

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
		{	echo " Invalid request";
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('GadgetsData');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new GadgetsData('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GadgetsData']))
			$model->attributes=$_GET['GadgetsData'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=GadgetsData::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='gadgets-data-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
