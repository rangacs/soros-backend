<?php

class TagGroupController extends BaseController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'ajaxUp'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'search'),
                'users' => array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin', 'root'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {


            $tagGroupObject = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $id));


        $this->render('view', array('tagGroupObject' => $tagGroupObject));
    }

    public function actionSearch() {


        $defaultTagGroupID = TagGroup::getDefaultTagGroupID();

        if (isset($_POST)) {

            $tagGroupId = isset($_POST['tagGroup_tagGroupID']) ? $_POST['tagGroup_tagGroupID'] : $defaultTagGroupID;

            $tagGroupObject = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $tagGroupId));
        } else {
            $tagGroupObject = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $defaultTagGroupID));
        }

        $this->render('view', array('tagGroupObject' => $tagGroupObject));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new TagGroup();


        if (isset($_REQUEST['TagGroup'])) {
//            var_dump($_REQUEST);
            $model->tagGroupName = $_REQUEST['TagGroup']["tagGroupName"];
            $model->massflowWeight = 1;
            $model->goodDataSecondsWeight = 1;
            $model->rtaMasterID = 15;

//            var_dump($model->attributes);
//            die();
            if (true) {

                Yii::app()->db->createCommand("INSERT INTO `tag_group` ( `tagGroupName`, `rtaMasterID`, `massflowWeight`, `goodDataSecondsWeight`)VALUES ( '" . $model->tagGroupName . "', '15', 1, 1)")->execute();
			$this->redirect(array('index', 'id' => $model->tagGroupID));
		}
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

        if (isset($_POST['TagGroup'])) {
            $model->attributes = $_POST['TagGroup'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->tagGroupID));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionAjaxUp() {

        if (isset($_POST['hidTagGroup'])) {

            $tagGrName = $_POST['tagGrName'];
            $dSTag = $_POST['DSTag'];
            $cTime = $_POST['cTime'];

            $tcommand = Yii::app()->db->createCommand("INSERT INTO tag_group " .
                    "(tagGroupID,tagGroupName,rtaMasterID,massflowWeight,goodDataSecondsWeight,timeStamp)" .
                    " VALUES (NULL,'{$tagGrName}',{$dSTag},'1','1','{$cTime}');");
            $tcommand->execute();

            echo "SUCCESS";
        }

        if (isset($_POST['hidTagOnly'])) {
            $tagName = $_POST['tagName'];
            $dSTag = $_POST['sDsrc'];
            $sTime = $_POST['tstartD'];
            $eTime = $_POST['tendD'];

            $tqc = Yii::app()->db->createCommand("SELECT rtaMasterID FROM `tag_group` WHERE" .
                            " tagGroupID = $dSTag LIMIT 1")->queryRow();

            $rtaMstrId = $tqc['rtaMasterID'];

            $tcommand = Yii::app()->db->createCommand("INSERT INTO rta_tag_index_queued " .
                    "(tagID,rtaMasterID,status,tagName,tagGroupID,LocalstartTime,LocalendTime)" .
                    " VALUES " .
                    "(NULL,'{$rtaMstrId}','queued','{$tagName}',{$dSTag},'{$sTime}','{$eTime}');");
            $tcommand->execute();

            echo "SUCCESS";
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {

        $sql = " DELETE FROM `tag_group` WHERE `tagGroupID` = '$id' LIMIT 1;";
        
        Yii::app()->db->createCommand($sql)->execute();
        
        $this->redirect(array('index'));
    }

    /**
     * Lists all models.
     */
    public function actionAdmin() {
        $dataProvider = new CActiveDataProvider('TagGroup');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $model = new TagGroup('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['TagGroup']))
            $model->attributes = $_GET['TagGroup'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = TagGroup::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tag-group-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
