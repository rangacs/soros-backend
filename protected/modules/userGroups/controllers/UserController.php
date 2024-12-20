<?php

class UserController extends Controller {

    /**
     * @var mixed tooltip for the permission menagement
     */
    public static $_d_permissionControl = array('read' => true, 'write' => true, 'admin' => true);
    public static $_permissionControl = array(
        'write' => 'can invite other users.',
        'admin' => 'can edit other users, ban them and approve their registrations.',
    );

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'userGroupsAccessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // just guest can perform 'activate', 'login' and 'passRequest' actions
                'actions' => array('activate', 'login', 'passRequest'),
                'ajax' => false,
                'users' => array('?'),
            ),
            array('allow', // captchas can be loaded just by guests
                'actions' => array('captcha'),
                'expression' => 'UserGroupsConfiguration::findRule("registration")',
                'users' => array('?'),
            ),
            array('allow', // just guest can perform registration actions and just if it is enabled
                'actions' => array('register'),
                'ajax' => false,
//				'expression'=>'UserGroupsConfiguration::findRule("registration")',
                'users' => array('*'),
            ),
            array('allow', // actions users can access while in recovery mode
                'actions' => array('recovery', 'logout'),
                'users' => array('#'),
            ),
            array('allow', // allow authenticated user to perform 'logout' actions
                'actions' => array('logout'),
                'users' => array('@'),
            ),
            array('allow', // allow logged user to access the userlist page if the have admin rights on users or if the list is public
                'actions' => array('index'),
                'expression' => 'UserGroupsConfiguration::findRule("public_user_list") || Yii::app()->user->pbac("userGroups.user.admin")',
                'users' => array('@'),
            ),
            array('allow', // allow guest to view users profiles according to the configuration
                'actions' => array('view'),
                'expression' => 'UserGroupsConfiguration::findRule("public_profiles")',
                'users' => array('?', '@'),
            ),
            array('allow', // allow logged user to view other users profiles according to the configuration and always their own
                'actions' => array('view'),
                'expression' => 'UserGroupsConfiguration::findRule("profile_privacy") || strtolower(Yii::app()->user->name) === (isset($_GET["u"]) ? strtolower($_GET["u"]) : strtolower(Yii::app()->user->name))',
                'users' => array('@'),
            ),
            array('allow', // allow user with user admin permission to view every profile, approve, ban and invite users
                'actions' => array('invite'),
                'pbac' => array('write'),
            ),
            array('allow', // allow user with user admin permission to view every profile, approve, ban and invite users
                'actions' => array('view', 'approve', 'ban', 'invite'),
                'pbac' => array('admin', 'admin.admin'),
            ),
            array('allow', // allow a user tu open an update view just on their own accounts
                'actions' => array('update'),
                'expression' => '$_GET["id"] == Yii::app()->user->id',
                'ajax' => false,
            ),
            array('allow', // allow user with admin permission to perform any action
                'pbac' => array('admin', 'admin.admin'),
                'ajax' => true,
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * override of the actions method to implement captcha
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    /**
     * Lists all users.
     */
    public function actionIndex() {
        $model = new UserGroupsUser('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['UserGroupsUser']))
            $model->attributes = $_GET['UserGroupsUser'];

        if (Yii::app()->request->isAjaxRequest)
            $this->renderPartial('index', array('model' => $model,), false, true);
        else
            $this->render('index', array('model' => $model,), false, true);
    }

    /**
     * render a user profile
     */
    public function actionView() {
        // load the user profile according to the request
        if (isset($_GET['u'])) {
            // look for the right user criteria to use according to the viewer permissions
            if (Yii::app()->user->pbac(array('user.admin', 'admin.admin')))
                $criteria = array('username' => $_GET['u']);
            else
                $criteria = array('username' => $_GET['u'], 'status' => UserGroupsUser::ACTIVE);
            // load the profile
            $model = UserGroupsUser::model()->findByAttributes($criteria);
            if ($model === null || ($model->relUserGroupsGroup->level > Yii::app()->user->level && !UserGroupsConfiguration::findRule('public_profiles')))
                throw new CHttpException(404, Yii::t('userGroupsModule.general', 'The requested page does not exist.'));
        } else
            $model = $this->loadModel(Yii::app()->user->id);

        // load the profile extensions
        $profiles = array();
        $profile_list = Yii::app()->controller->module->profile;


        foreach ($profile_list as $p) {
            // check if the profile data exist on the current user, otherwise
            // create an instance of the profile extension
            $relation = "rel$p";

            if (!$model->$relation instanceof CActiveRecord)
                $p_instance = new $p;
            else
                $p_instance = $model->$relation;

            // check if the profile extension is supporting profile views
            $views = $p_instance->profileViews();
            if (isset($views[UserGroupsUser::VIEW])) {
                $profiles[] = array('view' => $views[UserGroupsUser::VIEW], 'model' => $p_instance);
            }
        }


        if (Yii::app()->request->isAjaxRequest || isset($_GET['_isAjax']))
            $this->renderPartial('view', array('model' => $model, 'profiles' => $profiles), false, true);
        else
            $this->render('view', array('model' => $model, 'profiles' => $profiles));
    }

    /**
     * user registration
     */
    public function actionRegister() {
        $model = new UserGroupsUser('');
        if (isset($_POST['UserGroupsUser'])) {

//            var_dump($_POST['UserGroupsUser']);
//            die();
            $model->attributes = $_POST['UserGroupsUser'];

            if ($model->validate() && !isset($error)) {
                if ($model->save()) {
                    // save the related profile extensions
                    foreach ($profiles as $p) {
                        $p['model']->ug_id = $model->id;
                        $p['model']->save();
                    }
                    $this->redirect(Yii::app()->baseUrl . '/userGroups');
                }
            }
        }

        $this->render('register', array(
            'model' => $model,
            'profiles' => $profiles,
        ));
    }

    /**
     * user invite form
     */
    public function actionInvite() {
        $model = new UserGroupsUser('invitation');

        $this->performAjaxValidation($model);

        if (isset($_POST['UserGroupsUser'])) {
            $model->attributes = $_POST['UserGroupsUser'];
            if ($model->validate()) {
                if ($model->save()) {
                    $mail = new UGMail($model, UGMail::INVITATION);
                    $mail->send();
                } else
                    Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
                $this->redirect(Yii::app()->baseUrl . '/userGroups');
            }
        }

        if (Yii::app()->request->isAjaxRequest)
            $this->renderPartial('invite', array('model' => $model,), false, true);
        else
            $this->render('invite', array('model' => $model,));
    }

    /**
     * Updates a user data
     * if the update is successfull the user profile will be reloaded
     * You can change password or mail indipendently
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $miscModel = $this->loadModel($id, 'changeMisc');
        $passModel = clone $miscModel;
        $passModel->setScenario('changePassword');
        $passModel->password = NULL;

        // pass the models inside the array for ajax validation
        $ajax_validation = array($miscModel, $passModel);

        // load additional profile models
        $profile_models = array();
        $profiles = $this->module->profile;
        foreach ($profiles as $p) {
            $external_profile = new $p;
            // check if the loaded profile has an update view
            $external_profile_views = $external_profile->profileViews();
            if (array_key_exists(UserGroupsUser::EDIT, $external_profile_views)) {
                // load the model data
                $loaded_data = $external_profile->findByAttributes(array('ug_id' => $id));
                $external_profile = $loaded_data ? $loaded_data : $external_profile;
                // set the scenario
                $external_profile->setScenario('updateProfile');
                // load the models inside both the ajax validation array and the profile models
                // array to pass it to the view
                $profile_models[$p] = $external_profile;
                $ajax_validation[] = $external_profile;
            }
        }

        // perform ajax validation
        $this->performAjaxValidation($ajax_validation);

        // check if an additional profile model form was sent
        if ($form = array_intersect_key($_POST, array_flip($profiles))) {
            $model_name = key($form);
            $form_values = reset($form);
            // load the form values into the model
            $profile_models[$model_name]->attributes = $form_values;
            $profile_models[$model_name]->ug_id = $id;

            // save the model
            if ($profile_models[$model_name]->save()) {
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'Data Updated Successfully'));
                $this->redirect(Yii::app()->baseUrl . '/userGroups?_isAjax=1&u=' . $passModel->username);
            } else
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
        }

        if (isset($_POST['UserGroupsUser']) && isset($_POST['formID'])) {
            // pass the right model according to the sended form and load the permitted values
            if ($_POST['formID'] === 'user-groups-password-form')
                $model = $passModel;
            else if ($_POST['formID'] === 'user-groups-misc-form')
                $model = $miscModel;

            $model->attributes = $_POST['UserGroupsUser'];

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'Data Updated Successfully'));
                    //$this->redirect(Yii::app()->baseUrl . '/userGroups?_isAjax=1&u='.$model->username);
                } else
                    Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
            }
        }

        $_POST["passChanged"] = "success";
        if (Yii::app()->request->isAjaxRequest)
            $this->renderPartial('update', array('miscModel' => $miscModel, 'passModel' => $passModel, 'profiles' => $profile_models), false, true);
        else
            $this->render('update', array('miscModel' => $miscModel, 'passModel' => $passModel, 'profiles' => $profile_models), false, true);
    }

    /**
     * user activation view
     */
    public function actionActivate() {
        $activeModel = new UserGroupsUser('activate');
        $requestModel = new UserGroupsUser('mailRequest');

        if (isset($_POST['UserGroupsUser']) || isset($_GET['UserGroupsUser'])) {
            if (isset($_GET['UserGroupsUser']) || $_POST['id'] === 'user-groups-activate-form')
                $model = $activeModel;
            else if (($_POST['id'] === 'user-groups-request-form'))
                $model = $requestModel;

            if (isset($_POST['UserGroupsUser']))
                $model->attributes = $_POST['UserGroupsUser'];
            else
                $model->attributes = $_GET['UserGroupsUser'];


            if ($model->validate()) {
                if (isset($_GET['UserGroupsUser']) || $_POST['id'] === 'user-groups-activate-form') {
                    $model->login('recovery');
                    $this->redirect(Yii::app()->baseUrl . '/userGroups/user/recovery');
                } else {
                    $userModel = UserGroupsUser::model()->findByAttributes(array('email' => $model->email));
                    $mail = new UGMail($userModel, UGMail::ACTIVATION);
                    $mail->send();
                    $this->redirect(Yii::app()->baseUrl . '/userGroups/user/activate');
                }
            }
        }



        $this->render('activate', array(
            'activeModel' => $activeModel,
            'requestModel' => $requestModel
        ));
    }

    /**
     * approve the user account
     */
    public function actionApprove() {
        if (isset($_POST['UserGroupsApprove'])) {
            $model = $this->loadModel((int) $_POST['UserGroupsApprove']['id']);
            $model->status = UserGroupsUser::ACTIVE;
            if ($model->save())
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.admin', '{username}\'s account is now active.', array('{username}' => $model->username)));
            else
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
            $this->redirect(Yii::app()->baseUrl . '/userGroups?u=' . $model->username);
        }
    }

    /**
     * form for new pass request
     */
    public function actionPassRequest() {
        $model = new UserGroupsUser('passRequest');

        if (isset($_POST['UserGroupsUser'])) {
            $model->attributes = $_POST['UserGroupsUser'];
            if ($model->validate()) {
                $model = UserGroupsUser::model()->findByAttributes(array('username' => $_POST['UserGroupsUser']['username']));
                $model->scenario = 'passRequest';
                if ($model->save()) {
                    $mail = new UGMail($model, UGMail::PASS_RESET);
                    $mail->send();
                } else
                    Yii::app()->user->setFlash('success', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
                $this->redirect(Yii::app()->baseUrl . '/userGroups');
            }
        }

        $this->render('passRequest', array('model' => $model));
    }

    /**
     * ban user from the system
     */
    public function actionBan() {
        // load the user data
        $model = $this->loadModel((int) $_POST['UserGroupsBan']['id'], 'ban');
        // check if you are trying to ban a user with an higher level
        if ($model->relUserGroupsGroup->level > Yii::app()->user->level)
            Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.admin', 'You cannot ban a user with a level higher then yours.'));
        else {
            $model->ban = date('Y-m-d H:i:s', time() + ($_POST['UserGroupsBan']['period'] * 86400));
            $model->ban_reason = $_POST['UserGroupsBan']['reason'];
            $model->status = UserGroupsUser::BANNED;
            if ($model->save())
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.admin', '{username}\'s account is banned untill {day}.', array('{username}' => $model->username, '{day}' => $model->ban)));
            else
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
        }
        $this->redirect(Yii::app()->baseUrl . '/userGroups?u=' . $model->username);
    }

    /*
     *  For Firebug debugging:
     *  
     *   i.) Inside the 'if conditional':  if($model->validate() && $model->login()) 
     *    Comment out: ' $this->redirect(Yii::app()->user->returnUrl); '        
     *
     */

    public function actionLogin() {

        echo Controller::fb('hello wikkens');
        $model = new UserGroupsUser('login');

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['UserGroupsUser'])) {
            //echo Controller::fb('inside handling POST');
            //$this->fb( $_POST['UserGroupsUser'] );

            $model->attributes = $_POST['UserGroupsUser'];

            if ($model->validate()) {                       ///////////
                $this->fb("successfully validated");
            }

            // validate user input and redirect to the previous page if valid

            if ($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->user->returnUrl);           //Redirect to ' /helios_official/dash '         
            }
        }

        // display the login form
        if (Yii::app()->request->isAjaxRequest || isset($_GET['_isAjax']))
            $this->renderPartial('/user/login', array('model' => $model));
        else
            $this->render('/user/login', array('model' => $model));
    }

    /**
     * login in recovery mode
     */
    public function actionRecovery() {
        $model = $this->loadModel(Yii::app()->user->id, 'recovery');

        // if user and password are already setted and so question and answer no form will be prompted
        if (strpos($model->username, '_user') !== 0 && $model->password && $model->salt && $model->question && $model->answer) {
            $model->scenario = 'swift_recovery';
            if (!$model->save())
                Yii::app()->user->setFlash('success', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
            $this->redirect(Yii::app()->baseUrl . '/userGroups/user/logout');
        }

        // empty the password field
        $model->password = NULL;

        $this->performAjaxValidation($model);

        if (isset($_POST['UserGroupsUser'])) {
            $model->attributes = $_POST['UserGroupsUser'];
            if ($model->validate()) {
                if (!$model->save())
                    Yii::app()->user->setFlash('success', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
                $this->redirect(Yii::app()->baseUrl . '/userGroups/user/logout');
            }
        }

        $this->render('recovery', array(
            'model' => $model,
        ));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        // keep the flash messages flowing
        if (Yii::app()->user->hasFlash('success')) {
            $message = Yii::app()->user->getFlash('success');
            Yii::app()->request->cookies['success'] = new CHttpCookie('success', $message);
        }
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->baseUrl . '/userGroups');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * Optionally sets a scenario
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @param string the scenario to apply to the model
     */
    public function loadModel($id, $scenario = false) {
        $model = UserGroupsUser::model()->findByPk((int) $id);
        if ($model === null || ($model->relUserGroupsGroup->level > Yii::app()->user->level && !UserGroupsConfiguration::findRule('public_profiles')))
            throw new CHttpException(404, Yii::t('userGroupsModule.general', 'The requested page does not exist.'));
        if ($scenario)
            $model->setScenario($scenario);
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**     * returns an array that contains the views name to be loaded * @return array */
    public function profileViews() {
        return array(UserGroupsUser::VIEW => 'index', UserGroupsUser::EDIT => 'update', UserGroupsUser::REGISTRATION => 'registration',);
    }

    public function filterUserGroupsAccessControl($filterChain) {
        Yii::import('userGroups.models.UserGroupsUser');
        Yii::import('userGroups.models.UserGroupsConfiguration');
        Yii::import('userGroups.components.UserGroupsAccessControl');
        $filter = new UserGroupsAccessControl;
        $filter->setRules($this->accessRules());
        $filter->filter($filterChain);
    }

}
