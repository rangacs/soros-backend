<?php

class SiteController extends Controller {

    public $entire_page = "<!DOCTYPE html> <!-- The new doctype --><html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><body><h1>Hello WikkensZ</h1></body>";
    public $_dynamicOutput_child = 1;

    /**
     * Declares class-based actions.
     */
    public function filters() {
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
    /* 	public function accessRules()
      {
      return array(
      array('allow',  // allow all users to perform 'index' and 'view' actions
      'actions'=>array('view','login','logout'),
      'users'=>array('*'),
      ),
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
      'actions'=>array('index','create','update'),
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
     */

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /*
     *  CDbCache functionality here!!
     *  
     *  Method: filters()
     *  Purpose: To over-ride GrandFather 'CController' class method.  
     *
     */


    /* //OLD.. practice.    
      public function filters()
      {
      return array(
      array(
      'COutputCache + contact',
      'duration'=>3600,
      'varyByParam'=>array('id'),      //Used in fetching cached data..
      )
      );
      }
     */

    //LEFT OFF HERE!!! Dec12th 2012: Commenting out for now, just trying to get this thing working..
    /*
      public function filters()
      {
      return array(
      array(
      'COutputCache + contact',
      'requestTypes'=>array('GET'),
      'duration'=>3600,
      'varyByParam'=>array('id'),      //Used in fetching cached data..
      'dependency'=>array(
      'class' => 'CDbCacheDependency',
      'sql'   => 'SELECT value FROM yiicache WHERE expire > 0',
      ),
      )
      );
      }
     */



    /*
     *  Method:  actionIndex()
     *   @var  instanceof  CDbCacheSession
     *   
     *  Side-Note: Yii::app()->cache can be used; some methods
     *               like 'get()' are only available here, and
     *               not made available by 'CDbCache'.    
     *   
     */
    public function actionIndex() {
        /* //Mimics the functionality of 'CCache::generateUniqueKey'
          $id = "Yii.CUrlManager.rules";
          $this->fb( md5('143665f3'.$id) );
          $this->fb( Yii::app()->getId() );                 // 143665f3
         */

        /*
          $cs_A = new CDbCacheSession;
          $target_id = 'ffe8f6e6400610d1ceef32fb223b5ae7';
          $cached_rs = $cs_A->c_getValue($target_id);
          if( count($cached_rs) > 0 )
          {
          $temp_arr = array();
          foreach($cached_rs as $k1 => $v1){
          if( is_array($v1) )
          {
          $temp_arr[] = $v1;
          }
          }
          $this->fb( count($temp_arr) );
          $this->fb($temp_arr);
          }
          $this->fb('The value stored in Yii::app()->cache is ' .Yii::app()->cache->get($target_id) );
         */


        //LR070313 
        if (Yii::app()->user->isGuest)
            $this->forward('/userGroups/user/login');

        $this->redirect(Yii::app()->user->returnUrl);           //Redirect to ' /helios/dash '         
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     * 
     *  @cs_cache->set()  instanceof CCache  invokes set()      
     */
    public function actionContact() {
        //$this->fb( Yii::app()->getLanguage() );      //en
        //$this->fb( Yii::app()->sourceLanguage );     //en_us

        $cs_cache = Yii::app()->custom_cache;        //Abhinandan. NEED the full path here. Do NOT instantiate directly, else it will NOT work.
        $alias = 'fname';
        $controller = Yii::app()->controller->id;
        $action = ucfirst(Yii::app()->controller->action->id);
        $c_a = $controller . '_' . $action;
        $language = Yii::app()->getLanguage();

        //If get() returns FALSE(i think), THEN set() here..
        $cs_cache->set($alias, $this->entire_page, 3600);         //was 'wikkens' instead fo $this->entire_page..
        $cs_cache->cs_updateRecord($alias, $c_a, $language);


        //$db_cache = Yii::app()->cache;
        //$db_cache->set('lname', 'peabody', 3600);

        $cached_record = $cs_cache->get($alias);                         //Dec14th 2012:   LEFT OFF HERE!!!!

        if ($this->_dynamicOutput_child == 1) {
            $this->fb('SiteController, _dynamicOutput is 1');
            echo self::processDynamicOutput($cached_record);
            return;
        }



        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /*
     *  -- Abhinandan. --
     *  Purpose: Over-rides parent (CController) implementation.
     *   @Modified: $this->_dynamicOutput  TO  $this->_dynamicOutput_child
     *   
     *   @Method 'replaceDynamicOutput' calls parent implementation, naturally.      
     *
     */

    public function processDynamicOutput($output) {
        if ($this->_dynamicOutput_child == 1) {
            //Controller::fb('CController, processDynamicOutput()...processing taking place.');
            $output = preg_replace_callback('/<###dynamic-(\d+)###>/', array($this, 'replaceDynamicOutput'), $output);
        }
        return $output;
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->loginUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /*
     *   Legacy code (Abhi):
     *   Purpose: Used with 'CPhpMessageSource'   
     */
    /* Change Language on the Fly */

    public function actionLangChange() {
        if (isset($_GET['langId']) && in_array($_GET['langId'], Yii::app()->params['langArray'])) {
            Yii::app()->language = $_GET['langId'];

            if (!(Yii::app()->user->loginUrl))
                $this->render('index');
            else
                $this->redirect(Yii::app()->user->returnUrl . '?langId=' . Yii::app()->language);
        }
    }

}
