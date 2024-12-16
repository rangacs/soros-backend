<?php

class MasterTemplateiiiController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('allow',  // allow authenticated users to perform 'index' and 'view' actions    ..Abhinandan..hahaha action, view..
				'actions'=>array('index','view'),
        'users'=>array('@'),       //Dec5th: added. Abhinandan.
				//'users'=>array('*'),    //Dec5th: commented out. Abhinandan.
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	 /*
	 * Displays a particular record.
	 *   
	 * @param integer $id the ID of the model to be displayed
	 * 
	 * NOTE: Capable of being over-ridden. Ie pass only non-array type parameters (from other methods)
	 *        in order to see firePHP debug messages.. 
	 *        
	 *  SEE 'C:\Users\anotherComputerUser\Desktop\PHP_notes\Yii\phaeton_logging\IMPORTANT_redirect_notes_Oct26th' 
	 *    for additional debug details..              
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
  
  /*
  public function actionViewchild($id)
  {
   $this->render('view',array(
			'model'=>$this->loadModel($id),
		));
  }
  */

  //ChildTemplateiii 
	
  
  /**
	 *  --MODIFIED-- Method 'actionCreate()'
	 *  Purpose: 
	 *   a.) Create a new master template record w/child record
	 *   
	 *  Tables Affected:
	 *   i.)  tbl_audit_trail_master_template_iii
	 *   ii.) tbl_audit_trail_child_template_iii  
	 *                     
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()       //actionCreate is NOT the 'create button', but rather the URL controller action..
	{
    $model       = new MasterTemplateiii;
    
    $this->render('create',array(                           
			'model'=>$model,
		));
    
     
    if( isset($_POST['MasterTemplateiii']) )
    {
     $key          = $_POST['MasterTemplateiii']['_key_'];
     $action       = $_POST['MasterTemplateiii']['action'];
     $controller   = $_POST['MasterTemplateiii']['controller'];
     $category     = $_POST['MasterTemplateiii']['category'];
     $priority     = $_POST['MasterTemplateiii']['priority'];
     $message_type = $_POST['MasterTemplateiii']['message_type'];
     
     
     /* 
      *  i.)   Serialize category, priority, message_type and re-assign to 'value' attribute of child array:
      *  ii.)  Re-fit child array and assign to $model_child->attributes..
      *  iii.) Unset POST array and assign contents to $model->attributes.. 
      *  iv.)  Verify if entry already exists in master Template table:
      *        a.) If TRUE(dup exists): invoke $model_child->save() only.
      *        b.) If FALSE(no dup)   : invoke both $model->save() and $model_child->save().                     
     */
   
      if( count($built_child_arr = $model::buildChildTemplate($key, $action, $controller, $category, $priority, $message_type) ) > 0 )
      {
       $model_child = new ChildTemplateiii;
       $model_child->attributes = $built_child_arr;
     
       unset( $_POST['MasterTemplateiii']['action'] );
       unset( $_POST['MasterTemplateiii']['controller'] );  
       unset( $_POST['MasterTemplateiii']['category'] );
       unset( $_POST['MasterTemplateiii']['priority'] );
       unset( $_POST['MasterTemplateiii']['message_type'] );
     
       $model->attributes = $_POST['MasterTemplateiii'];
     
       if( ($dup = $model::dupExists($key))===FALSE )       //No duplicate exists in master Template iii.. go ahead and create a new record.
       {
        if( $model->save() )
        {
         if( $model_child->save() )
         {
          $this->redirect( array('view', 'id' => $model->id) );      
         }
        }
       }//end if No duplicate exists...
       elseif( ($dup = $model::dupExists($key))!==FALSE )    //If there is a duplicate... then perform insert on child template table only...
       {
        if( $model_child->save() )
        {
        // $this->redirect( array('view', 'id' => $model->id) );    //Dec6th: Commented this out, due to Error: Headers already sent by CController, headers later sent by CHttpRequest was triggering this error.
         
        }
       }
      } 
         
    }//end if $_POST isset..
    
    

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
    
	} //end actionCreate()..
  
  

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

		if(isset($_POST['MasterTemplateiii']))
		{
			$model->attributes=$_POST['MasterTemplateiii'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
    $mwc = new MasterTemplateiii;    //Dec5th; invoke afterConstruct within 'LoggableBehavior.php'
    
		$dataProvider=new CActiveDataProvider('MasterTemplateiii');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new MasterTemplateiii('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['MasterTemplateiii']))
			$model->attributes=$_GET['MasterTemplateiii'];

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
		$model=MasterTemplateiii::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='master-templateiii-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
