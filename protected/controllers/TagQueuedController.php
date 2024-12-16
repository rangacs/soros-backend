<?php

class TagQueuedController extends BaseController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new TagQueued;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['TagQueued'])) {
            $model->attributes = $_POST['TagQueued'];
            if ($model->save()) {
                
                $this->redirect(array('view', 'id' => $model->tagID));
            }//save
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['TagQueued'])) {
            $model->attributes = $_POST['TagQueued'];
            $startDate = $_POST['TagQueued_startDate'];
            $endDate = $_POST['TagQueued_endDate'];
            $startTime = $_POST['TagQueued_startTime'];
            $endTime = $_POST['TagQueued_endTime'];


            $localendTime = date('Y-m-d H:i:s', strtotime($endDate . " " . $endTime));
            $localstartTime = date('Y-m-d H:i:s', strtotime($startDate . " " . $startTime));

            $model->LocalendTime = $localendTime;
            $model->LocalstartTime = $localstartTime;
            if ($model->save())
                $this->redirect(array('/tagSettings'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('TagCompleted');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new TagQueued('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['TagQueued']))
            $model->attributes = $_GET['TagQueued'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return TagQueued the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = TagQueued::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param TagQueued $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tag-queued-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
