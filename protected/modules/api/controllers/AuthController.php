<?php

class AuthController extends BaseController {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionLogin1() {
//		$this->render('index');

        $user = array('role' => 'superadmin',
            'isEmailVerified' => false,
            'email' => 'admin@sabiainc.com',
            'name' => 'Admin',
            'companyId' => '61935e6d09c8b105708d356d',
            'client_assigned' => ''
        );
        $token = array('access' => array("token" => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MTlkZmM0OGY3ZThjNjRlMzA4ZjI3NzciLCJpYXQiOjE2NTE4MTc1MjIsImV4cCI6MTY1MTgxOTMyMiwidHlwZSI6ImFjY2VzcyJ9.3tDfXnyf2QawmcCywmEsFNld33BGNhDC3SZh6YSR8Yg","expires":"2022-05-06T06:42:02.161Z"},"refresh":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MTlkZmM0OGY3ZThjNjRlMzA4ZjI3NzciLCJpYXQiOjE2NTE4MTc1MjIsImV4cCI6MTY1NDQwOTUyMiwidHlwZSI6InJlZnJlc2gifQ.B5rH9YIaGcwSmm3o6k1zn_6woNmI22_AsY82dH_oRak","expires":"2022-06-05T06:12:02.162Z'));
        $accessInfo = array("user" => $user, 'tokens' => $token);



        $this->sendSuccessResponse($accessInfo);
//                echo '{"user":{"role":"superadmin","isEmailVerified":false,"email":"admin@pierian.com","name":"Super Admin","companyId":"61935e6d09c8b105708d356d",'
//                . '"client_assigned":[],"id":"619dfc48f7e8c64e308f2777"},"tokens":{"access":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MTlkZmM0OGY3ZThjNjRlMzA4ZjI3NzciLCJpYXQiOjE2NTE4MTc1MjIsImV4cCI6MTY1MTgxOTMyMiwidHlwZSI6ImFjY2VzcyJ9.3tDfXnyf2QawmcCywmEsFNld33BGNhDC3SZh6YSR8Yg","expires":"2022-05-06T06:42:02.161Z"},"refresh":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MTlkZmM0OGY3ZThjNjRlMzA4ZjI3NzciLCJpYXQiOjE2NTE4MTc1MjIsImV4cCI6MTY1NDQwOTUyMiwidHlwZSI6InJlZnJlc2gifQ.B5rH9YIaGcwSmm3o6k1zn_6woNmI22_AsY82dH_oRak","expires":"2022-06-05T06:12:02.162Z"}}}';
    }

    public function actionLogin2() {



//        $this->sendSuccessResponse(array('data' => $this->request));
        $model = new LoginForm;
        $user = array('role' => 'superadmin',
            'isEmailVerified' => false,
            'email' => 'admin@sabiainc.com',
            'name' => 'Admin',
            'companyId' => '61935e6d09c8b105708d356d',
            'client_assigned' => ''
        );
        $token = array('access' => array("token" => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MTlkZmM0OGY3ZThjNjRlMzA4ZjI3NzciLCJpYXQiOjE2NTE4MTc1MjIsImV4cCI6MTY1MTgxOTMyMiwidHlwZSI6ImFjY2VzcyJ9.3tDfXnyf2QawmcCywmEsFNld33BGNhDC3SZh6YSR8Yg","expires":"2022-05-06T06:42:02.161Z"},"refresh":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MTlkZmM0OGY3ZThjNjRlMzA4ZjI3NzciLCJpYXQiOjE2NTE4MTc1MjIsImV4cCI6MTY1NDQwOTUyMiwidHlwZSI6InJlZnJlc2gifQ.B5rH9YIaGcwSmm3o6k1zn_6woNmI22_AsY82dH_oRak","expires":"2022-06-05T06:12:02.162Z'));
        $accessInfo = array("user" => $user, 'tokens' => $token);


        // if it is ajax validation request

        $_POST['LoginForm']['username'] = 'admin';
        $_POST['LoginForm']['password'] = 'database';

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login() || true) {

                $this->sendSuccessResponse($accessInfo);
            }
        }
        // display the login form
        $this->sendFailedResponse('login', array('model' => $model));
    }

    public function actionLogin() {

        $userLoginCredential = $this->request;
        
        $manager = array('role' => 'manager',
            'isEmailVerified' => false,
            'email' => 'manager.com',
            'name' => 'Manager',
            'companyId' => '61935e6d09c8b105708d356d',
            'client_assigned' => ''
        );
        $admin = array('role' => 'admin',
            'isEmailVerified' => false,
            'email' => 'admin@sabiainc.com',
            'name' => 'Admin',
            'companyId' => '61935e6d09c8b105708d356d',
            'client_assigned' => ''
        );
        $inst = array('role' => 'manager',
            'isEmailVerified' => false,
            'email' => 'inst.com',
            'name' => 'Inst',
            'companyId' => '61935e6d09c8b105708d356d',
            'client_assigned' => ''
        );

        $userList = array(
            'manager' => array('username' => 'manager', 'password' => 'manager123', 'data' => $manager),
            'admin' => array('username' => 'admin', 'password' => 'admin123', 'data' => $admin),
            'inst' => array('username' => 'inst', 'password' => 'inst@123', 'data' => $inst)
        );

        $user = array('role' => 'superadmin',
            'isEmailVerified' => false,
            'email' => 'admin@sabiainc.com',
            'name' => 'Admin',
            'companyId' => '61935e6d09c8b105708d356d',
            'client_assigned' => ''
        );
        $token = array('access' => array("token" => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MTlkZmM0OGY3ZThjNjRlMzA4ZjI3NzciLCJpYXQiOjE2NTE4MTc1MjIsImV4cCI6MTY1MTgxOTMyMiwidHlwZSI6ImFjY2VzcyJ9.3tDfXnyf2QawmcCywmEsFNld33BGNhDC3SZh6YSR8Yg","expires":"2022-05-06T06:42:02.161Z"},"refresh":{"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2MTlkZmM0OGY3ZThjNjRlMzA4ZjI3NzciLCJpYXQiOjE2NTE4MTc1MjIsImV4cCI6MTY1NDQwOTUyMiwidHlwZSI6InJlZnJlc2gifQ.B5rH9YIaGcwSmm3o6k1zn_6woNmI22_AsY82dH_oRak","expires":"2022-06-05T06:12:02.162Z'));
        $accessInfo = array("user" => $user, 'tokens' => $token);

        $userName = $userLoginCredential['email'];
        $result = Yii::app()->db->createCommand()
                ->select()
                ->from('usergroups_user')
                ->where("username = '$userName'")
                ->queryRow();
               
        if (!$result) {
            $this->sendFailedResponse(array('message' => 'No User Found', 'sql' => Yii::app()->db->createCommand()
                ->select()
                ->from('usergroups_user')
                ->where("username = '$userName'")->text));
        }
        if (CPasswordHelper::verifyPassword($userLoginCredential['password'], $result['password'])) {
            $userIdentity = $result;

            $accessInfo['user'] = $userIdentity;
            $this->sendSuccessResponse($accessInfo);
        }else{
//            var_dump(CPasswordHelper::hashPassword($userLoginCredential['password']));
//            die();
            $this->sendFailedResponse(array('message' => "Invalid Username or Password"));
        }
       
        // display the login form
    }

    // collect user input data
}

// Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */

