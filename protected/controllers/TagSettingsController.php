<?php

class TagSettingsController extends BaseController {

//    	public $layout='//layouts/column';

    public function init() {
        $pathinfo = pathinfo(Yii::app()->request->scriptFile);
        $uploaddir = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR;
        require($uploaddir . 'defines.php');
    }

    public function actionIndex() {

//        echo "swamy";
        $this->render('tag-dash');
    }

    public function actionDisplayMerge() {


        $tagListString = $_REQUEST['taglist'];
        $tagList = explode(",", $tagListString);
        $tagGroup = $_REQUEST['tagGroup'];



//        $tagList = array();
        $data = [];
        foreach ($tagList as $tagId) {

            $tagModel = TagGroup::getTagById($tagId);

            if (!$tagModel) {
                continue;
            }
            $data[] = $tagModel;
        }

        $this->renderPartial('tag-merge-view', ['tagList' => $data]);
    }

    public function actionMerge() {

        $hasQueued = $_REQUEST['hasQueuedTag'];

        $tagListString = $_REQUEST['taglist'];
        $tagList = explode(",", $tagListString);
        $tagGroup = $_REQUEST['tagGroup'];
        $newTagName = $_REQUEST['newTagName'];

        if ("true" == $hasQueued) {
            $newTag = new TagQueued();
            $newTag->status = 'queued';
        } else {
            $newTag = new TagCompleted();
            $newTag->status = 'completed';
        }
//        $newTag = new TagQueued();
        $newTag->tagName = $newTagName;
        $newTag->LocalendTime = date("Y-m-d H:i:s");
        $newTag->LocalstartTime = date("Y-m-d H:i:s");
        $newTag->goodDataSecondsWeight = 1;
        $newTag->massflowWeight = 1;
        $newTag->rtaMasterID = 15;
        $newTag->tagGroupID = $tagGroup;

        if ($newTag->save()) {
            echo "New Tag is created added";
        } else {
//            echo "error";
            var_dump($newTag->errors);
        }
        $timeSeries = [];
        foreach ($tagList as $tagId) {

            $tagModel = TagGroup::getTagById($tagId);

            if (!$tagModel) {
                continue;
            }

            $timeSeries[] = strtotime($tagModel->LocalendTime);
            $timeSeries[] = strtotime($tagModel->LocalstartTime);
            $subTag = new SubTag();
            $subTag->tagID = $newTag->tagID;
            $subTag->tagName = $tagModel->tagName;
            $subTag->LocalendTime = $tagModel->LocalendTime;
            $subTag->LocalstartTime = $tagModel->LocalstartTime;
            $subTag->status = $tagModel->status;
            $subTag->rtaMasterID = $tagModel->rtaMasterID;
            $subTag->tagGroupID = $tagModel->tagGroupID;

            if ($subTag->save()) {
                echo "Added Sub tag";
                $tagModel->delete();
            } else {
//            echo "error";
                var_dump($subTag->errors);
            }
        }

        $minTime = min($timeSeries);
        $maxTime = max($timeSeries);

        $newTag->LocalstartTime = date("Y-m-d H:i:s", $minTime);
        $newTag->LocalendTime = date("Y-m-d H:i:s", $maxTime);

        if ('false' == $hasQueued) {

            echo "Finding average";
            $average = TagHelper::getTagAvg($newTag);
            echo "Calculated average";
            var_dump($average);
            foreach ($average as $key => $value) {

                try {
                    $newTag->$key = $value;
                } catch (Exception $ex) {

                    echo "Some exception while handling =>" . $key;
                }
            }
        }

        $newTag->save();
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

            $startDate = $_POST['TagQueued_startDate'];
            $endDate = $_POST['TagQueued_endDate'];
            $startTime = $_POST['TagQueued_startTime'];
            $endTime = $_POST['TagQueued_endTime'];

            $localendTime = date('Y-m-d H:i:s', strtotime($endDate . " " . $endTime));
            $localstartTime = date('Y-m-d H:i:s', strtotime($startDate . " " . $startTime));

            $model->LocalendTime = $localendTime;
            $model->LocalstartTime = $localstartTime;
            $model->status = 'queued';

            $model->rtaMasterID = 15; // By default 15  i.e analysis_A1_A2_Blend 
            if ($model->save()) {

                $this->redirect(array('index'));
            }//save
        } else {

            $model->rtaMasterID = 15; // By default 15  i.e analysis_A1_A2_Blend 
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionSubTagViews() {

        $tagId = $_REQUEST['tagID'];

        $query = "select * from rta_tag_index_sub_tag where tagID='" . $tagId . "'";

        $result = Yii::app()->db->createCommand($query)->queryAll();
//        $subTags = SubTag::model()->findAll(['tagID' => $tagId]);
//        var_dump($result);

        $this->renderPartial('tag-sub-view', ['tagList' => $result]);
    }

    public function actionSearch() {


        $defaultTagGroupID = TagGroup::getDefaultTagGroupID();

        if (isset($_POST)) {

            $tagGroupId = isset($_POST['tagGroup_tagGroupID']) ? $_POST['tagGroup_tagGroupID'] : $defaultTagGroupID;

            $tagGroupObject = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $tagGroupId));
        } else {
            $tagGroupObject = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $defaultTagGroupID));
        }

        $this->render('tag-dash', array('tagGroupObject' => $tagGroupObject));
    }

    public function actionTagView($id, $type) {


        $tagObject = $this->getObject($id, $type);
        $this->render('tag-view', array('tagObject' => $tagObject));
    }

    public function actionTagGroupView($tgID) {


        $tagGroupObject = TagGroup::model()->find('tagGroupID = :tgID', array(':tgID' => $tgID));
        $this->render('tag-group', array('tagGroupObject' => $tagGroupObject));
    }

    /**
     * 
     * @param int $id
     * @param String $tagType
     * @return TagCompleted | TagQueued
     * 
     */
    public function getObject($id, $tagType) {



        if ($tagType == 'completed') {

            $tagObject = TagCompleted::model()->find('tagID  = :tagID', array(':tagID' => $id));
        } else {

            $tagObject = TagQueued::model()->find('tagID  = :tagID', array(':tagID' => $id));
        }


        return $tagObject;
    }

    public function actionDeleteTagCompleted($id) {

        TagCompleted::model()->deleteByPk($id);

        $this->redirect(array(index));
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
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->tagID));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionDeleteTagQueued($id) {

        TagQueued::model()->deleteByPk($id);

        $this->redirect(array(index));
    }

    public function actionDeleteTagGroup($id) {

        TagGroup::model()->deleteByPk($id);

        $this->redirect(array(index));
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

}
