<?php

class TagGroupController extends BaseController {

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
        $result = $this->loadModel($id);
        $this->sendSuccessResponse(array('data' => $result));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $columnData = $this->request;
        $model = new TagGroup();
        $model->tagGroupName = $columnData["tagGroupName"];
        $model->rtaMasterID = $columnData["rtaMasterID"];
        $model->massflowWeight = $columnData["massflowWeight"];
        $model->goodDataSecondsWeight = $columnData["goodDataSecondsWeight"];
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if ($model->validate() && $model->save()) {
            $this->sendSuccessResponse(array('message' => 'Created Succesfully'));
        } else {

            $this->sendFailedResponse(array('message' => $model->errors));
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
        $model->tagGroupName = $columnData["tagGroupName"];
        $model->rtaMasterID = $columnData["rtaMasterID"];
        $model->massflowWeight = $columnData["massflowWeight"];
        $model->goodDataSecondsWeight = $columnData["goodDataSecondsWeight"];

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

        //$this->loadModel($id)->delete();
	$tagCompletedSql = Yii::app()->db->createCommand()
                ->delete('tag_group', 'tagGroupID =' . $id);
        $tagCompletedSql = Yii::app()->db->createCommand()
                ->delete('rta_tag_index_completed', 'tagGroupID =' . $id);
        $tagQueuedSql = Yii::app()->db->createCommand()
                ->delete('rta_tag_index_queued', 'tagGroupID =' . $id);

        $this->sendSuccessResponse(array('message' => 'Deleted successfully'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $page = ((int) (Yii::app()->request->getParam('page', 0)));

        $pageSize = ((int) (Yii::app()->request->getParam('page_size', 1000)));

        $query = Yii::app()->db->createCommand()
                ->select()
                ->from('tag_group');

        $result = $query
                ->limit($pageSize, 1000)
                ->offset($page * $pageSize, 0)
                ->queryAll();
        

        $this->sendSuccessResponse(array('data' => array_reverse($result)));
    }

    public function actionTagGroupAverage($id) {
        $elementArray = HeliosUtility::getDisplayElements();
        $avgCol = array();
        foreach ($elementArray as $key => $ele) {
            if ($ele == "totalTons") {
                $avgCol[] = "round(sum($ele) , 3) as $ele";
            } else if ($ele == "LocalendTime") {
                continue;
            } else {
                $avgCol[] = "round(avg($ele) , 2) as $ele";
            }
        }
        $colQuery = implode(' , ', $avgCol);
        $model = $this->loadModel($id);
        $res = Yii::app()->db->createCommand()
                ->select($colQuery, 'min(LocalendTime) as LocalstartTime ,max(LocalendTime) as LocalendTime,')
                ->from('rta_tag_index_completed')
                ->where('tagGroupID=:id', array(':id' => $id))
                ->queryAll();
        $res[0]['tagGroupName'] = $model->tagGroupName;
        $this->sendSuccessResponse(array('data' => $res));
    }

    public function actionTagGroup() {

        $result = Yii::app()->db->createCommand()
                ->select()
                ->from('tag_group')
                ->queryAll();
        $resArray = array();
        foreach ($result as $key => $value) {

            $tagGroupID = $result[$key]['tagGroupID'];
            $tagGroupName = $result[$key]['tagGroupName'];

            $res = Yii::app()->db->createCommand()
                    ->select('max(LocalstartTime) as LocalstartTime,max(LocalendTime) as LocalendTime,count(tagGroupID) as tagGroupIDCount')
                    ->from('rta_tag_index_completed')
                    ->where('tagGroupID=:id', array(':id' => $tagGroupID))
                    ->queryAll();

            $resQueued = Yii::app()->db->createCommand()
                    ->select('max(LocalstartTime) as LocalstartTime,max(LocalendTime) as LocalendTime,count(tagGroupID) as tagGroupIDCount')
                    ->from('rta_tag_index_queued')
                    ->where('tagGroupID=:id', array(':id' => $tagGroupID))
                    ->queryAll();
	 
	    //var_dump($resQueued[0]['tagGroupIDCount']);	

            if ($resQueued[0]['tagGroupIDCount'] == '1') {
                $res[0]['tagGroupIDCount'] = $res[0]['tagGroupIDCount'] + 1;
            }
            if (strtotime($res[0]['LocalstartTime']) < strtotime($resQueued[0]['LocalstartTime'])) {
                $res[0]['LocalstartTime'] = $resQueued[0]['LocalstartTime'];
            }
            if (strtotime($res[0]['LocalendTime']) < strtotime($resQueued[0]['LocalendTime'])) {
                $res[0]['LocalendTime'] = $resQueued[0]['LocalendTime'];
            }

            $res[0]['tagGroupID'] = $tagGroupID;
            $res[0]['tagGroupName'] = $tagGroupName;

            $resArray[] = $res;
        }
        $this->sendSuccessResponse(array('data' => array_reverse($resArray)));
    }

    public function actionGetSubTags($id) {

        $subTagList = SubTag::model()->findAll('tagID =' . $id);
        $element = HeliosUtility::getDisplayElements();
        $results = array();
        foreach ($subTagList as $subTag) {
            $avg = AnalysisDataProvider::queryAvg($element, $subTag->LocalstartTime, $subTag->LocalendTime);
            $avg["Sub Tag Name"] = $subTag->tagName;
            $avg["tagID"] = $subTag->tagID;

            $results[] = $avg;
        }


        $this->sendSuccessResponse(array('data' => $results));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
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
     * @param integer $id the ID of the model to be loaded
     * @return TagGroup the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = TagGroup::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param TagGroup $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'tag-group-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
