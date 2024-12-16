<?php

class UsergroupsController extends BaseController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $result = Yii::app()->db->createCommand()
                ->select()
                ->from('usergroups_user')
                ->where("id = '$id'")
                ->queryAll();
        $this->sendSuccessResponse(array('data' => $result));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $columnData = $this->request;


        $model = new UsergroupsUser;

        $model->username = $columnData['username'];
        $model->group_id = $columnData['group_id'];

        $hash = CPasswordHelper::hashPassword($columnData['password']);
        $model->password = $hash;
        $model->home = $columnData['home'];
        $model->status = $columnData['status'];
        $model->question = $columnData['question'];
        $model->role = $columnData['role'];
        $model->answer = $columnData['answer'];
        $model->creation_date = date("Y-m-d H:i:s", time());
        ;
        $model->last_login = date("Y-m-d H:i:s", time());
        $model->activation_code = $columnData['activation_code'];
        $model->activation_time = $columnData['activation_time'];
        $model->email = $columnData['email'];
        $model->ban_reason = $columnData['ban_reason'];
        $model->ban = $columnData['ban'];

        if ($model->save()) {
            $this->sendSuccessResponse(array('message' => 'Created Succesfully'));
        } else {
            var_dump($model->errors);
            $this->sendFailedResponse(array('message' => 'Not Created'));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $columnData = $this->request;


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $model->username = $columnData['username'];
        $model->group_id = $columnData['group_id'];

        $hash = CPasswordHelper::hashPassword($columnData['password']);
        $model->password = $hash;
        $model->home = $columnData['home'];
        $model->status = $columnData['status'];
        $model->question = $columnData['question'];
        $model->role = $columnData['role'];
        $model->answer = $columnData['answer'];
        $model->creation_date = date("Y-m-d H:i:s", time());
        ;
        $model->last_login = date("Y-m-d H:i:s", time());

        $model->activation_code = $columnData['activation_code'];
        $model->activation_time = $columnData['activation_time'];
        $model->email = $columnData['email'];
        $model->ban_reason = $columnData['ban_reason'];
        $model->ban = $columnData['ban'];

        if ($model->save()) {
            $this->sendSuccessResponse(array('message' => 'Updated Succesfully'));
        } else {
            $this->sendFailedResponse(array('message' => 'Not Updated'));
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        $this->sendSuccessResponse(array('message' => 'Deleted successfully'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $result = Yii::app()->db->createCommand()
                ->select('username,email,status,id,role,fullname')
                ->from('usergroups_user')
                ->queryAll();
        $this->sendSuccessResponse(array('data' => $result));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new UsergroupsUser('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UsergroupsUser']))
            $model->attributes = $_GET['UsergroupsUser'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return UsergroupsUser the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = UsergroupsUser::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param UsergroupsUser $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'usergroups-user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionChangePassword() {

        $passwords = $this->request;
        $id = $passwords['id'];
        if ($passwords['newPassword'] != $passwords['confirmPassword']) {
            $this->sendFailedResponse(array('message' => 'Confirm Password Failed !!!!!'));
        } else {
            //var_dump($hashpassword);
            $result = Yii::app()->db->createCommand()
                    ->select('password')
                    ->from('usergroups_user')
                    ->where("id = '$id'")
                    ->queryRow();
            if (!$result) {
                $this->sendFailedResponse(array('message' => 'No User Found'));
            }
            if (CPasswordHelper::verifyPassword($passwords['currentPassword'], $result['password'])) {
                $model = $this->loadModel($id);
                $hash = CPasswordHelper::hashPassword($passwords['newPassword']);
                $model->password = $hash;
                if ($model->save()) {
                    $this->sendSuccessResponse(array('message' => 'Updated Succesfully'));
                } else {
//                    var_dump($model->errors);
                    $this->sendFailedResponse(array('message' => $model->errors));
                }
            }
            // password is good
            else {
                $this->sendFailedResponse(array('message' => 'Password is Incorrect !!!!!'));
            }
            // password is bad
        }
    }

}
