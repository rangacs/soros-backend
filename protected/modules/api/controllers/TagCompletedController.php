<?php

class TagCompletedController extends BaseController {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
		
		
		$anDataprovider = new AnalysisDataProvider();
                $tagModel = $this->loadModel($id);
		
		
		$tagModel->totalTons = $tagModel->totalTons - $tagModel->totalTonsRemoved;
		
		$std = $anDataprovider->getTagStd($tagModel);
		
		$std['tagName'] =  "Standard Deviation";
		$std["LocalstartTime"] = "Standard Deviation";
		$std["LocalendTime"] = "-";
		
		$std['totalTons'] =  "0";
		
                $this->sendSuccessResponse(array('data' => array('average'=>$tagModel , 'std' => $std)));
    }
    
    public function actionGetData($id) {
		
		
		$anDataprovider = new AnalysisDataProvider();
        $tagModel = $this->loadModel($id);
		
		$page = ((int) (Yii::app()->request->getParam('page', 0)));

        $pageSize = ((int) (Yii::app()->request->getParam('pageSize', 50)));
		
        $interval = ((int) (Yii::app()->request->getParam('interval', 1)));
		
		$result = $anDataprovider->getTagData($tagModel,$page, $pageSize,$interval);
		
		
        $this->sendSuccessResponse(array('data' => $result));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $columnData = $this->request;
        $model = new RtaTagIndexCompleted;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model->rtaMasterID = $columnData["rtaMasterID"];
        $model->status = $columnData["status"];
        $model->tagName = $columnData["tagName"];
        $model->tagGroupID = $columnData["tagGroupID"];
        $model->LocalstartTime = $columnData["LocalstartTime"];
        $model->LocalendTime = $columnData["LocalendTime"];
        $model->goodDataSecondsWeight = $columnData["goodDataSecondsWeight"];
        $model->massflowWeight = $columnData["massflowWeight"];
        $model->validTag = $columnData["validTag"];
        $model->endTic = $columnData["endTic"];
        $model->startTic = $columnData["startTic"];
        $model->goodDataSecs = $columnData["goodDataSecs"];
        $model->avgMassFlowTph = $columnData["avgMassFlowTph"];
        $model->totalTons = $columnData["totalTons"];
        $model->Ash = $columnData["Ash"];
        $model->Sulfur = $columnData["Sulfur"];
        $model->Moisture = $columnData["Moisture"];
        $model->BTU = $columnData["BTU"];
        $model->Na2O = $columnData["Na2O"];
        $model->SO2 = $columnData["SO2"];
        $model->TPH = $columnData["TPH"];
        $model->SiO2 = $columnData["SiO2"];
        $model->Al2O3 = $columnData["Al2O3"];
        $model->Fe2O3 = $columnData["Fe2O3"];
        $model->TEST = $columnData["TEST"];
        $model->CAL_ID = $columnData["CAL_ID"];
        $model->MAFBTU = $columnData["MAFBTU"];
        $model->CaO = $columnData["CaO"];
        $model->MgO = $columnData["MgO"];
        $model->K2O = $columnData["K2O"];
        $model->TiO2 = $columnData["TiO2"];
        $model->Mn2O3 = $columnData["Mn2O3"];
        $model->P2O5 = $columnData["P2O5"];
        $model->SO3 = $columnData["SO3"];
        $model->Cl = $columnData["Cl"];
        $model->LOI = $columnData["LOI"];
        $model->LSF = $columnData["LSF"];
        $model->SM = $columnData["SM"];
        $model->AM = $columnData["AM"];
        $model->IM = $columnData["IM"];
        $model->C4AF = $columnData["C4AF"];
        $model->NAEQ = $columnData["NAEQ"];
        $model->C3S = $columnData["C3S"];
        $model->C3A = $columnData["C3A"];
        $model->SourceDeployed = $columnData["SourceDeployed"];
        $model->SourceStored = $columnData["SourceStored"];
        $model->CPS = $columnData["CPS"];
        $model->K = $columnData["K"];
        $model->V2O5 = $columnData["V2O5"];
        $model->CdO = $columnData["CdO"];
        $model->GCV = $columnData["GCV"];
        $model->CPS_det1 = $columnData["CPS_det1"];
        $model->CPS_Det2 = $columnData["CPS_Det2"];

        if ($model->save()) {
            $this->sendSuccessResponse(array('message' => 'Created Succesfully'));
        } else {

            $this->sendFailedResponse(array('message' => 'Not Created'));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
	 
	 public function actionIsUniqueName(){
		 
		 
		$tagName =  $_REQUEST['tagName'];
		$sql =  "select * from rta_tag_index_completed where tagName = '".$tagName."' " ;
		 
        $completedresult = Yii::app()->db->createCommand($sql)->queryAll();
            
		 
		$sql =  "select * from rta_tag_index_queued where tagName = '".$tagName."' " ;
		 
        $queuedresult = Yii::app()->db->createCommand($sql)->queryAll();
        
		$nameExits = 'no';
		
		//var_dump(array($completedresult,$queuedresult));
		if(count($queuedresult) > 0 || count($completedresult) > 0){
			
			$nameExits = 'yes';	
		}
		$this->sendSuccessResponse(array('nameExits' => $nameExits));
		 
		 
		 }
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        $columnData = $this->request;


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model->rtaMasterID = $columnData["rtaMasterID"];
        $model->status = $columnData["status"];
        $model->tagName = $columnData["tagName"];
        $model->tagGroupID = $columnData["tagGroupID"];
        $model->LocalstartTime = $columnData["LocalstartTime"];
        $model->LocalendTime = $columnData["LocalendTime"];
        $model->goodDataSecondsWeight = $columnData["goodDataSecondsWeight"];
        $model->massflowWeight = $columnData["massflowWeight"];
        $model->validTag = $columnData["validTag"];
        $model->endTic = $columnData["endTic"];
        $model->startTic = $columnData["startTic"];
        $model->goodDataSecs = $columnData["goodDataSecs"];
        $model->avgMassFlowTph = $columnData["avgMassFlowTph"];
        $model->totalTons = $columnData["totalTons"];
        $model->Ash = $columnData["Ash"];
        $model->Sulfur = $columnData["Sulfur"];
        $model->Moisture = $columnData["Moisture"];
        $model->BTU = $columnData["BTU"];
        $model->Na2O = $columnData["Na2O"];
        $model->SO2 = $columnData["SO2"];
        $model->TPH = $columnData["TPH"];
        $model->SiO2 = $columnData["SiO2"];
        $model->Al2O3 = $columnData["Al2O3"];
        $model->Fe2O3 = $columnData["Fe2O3"];
        $model->TEST = $columnData["TEST"];
        $model->CAL_ID = $columnData["CAL_ID"];
        $model->MAFBTU = $columnData["MAFBTU"];
        $model->CaO = $columnData["CaO"];
        $model->MgO = $columnData["MgO"];
        $model->K2O = $columnData["K2O"];
        $model->TiO2 = $columnData["TiO2"];
        $model->Mn2O3 = $columnData["Mn2O3"];
        $model->P2O5 = $columnData["P2O5"];
        $model->SO3 = $columnData["SO3"];
        $model->Cl = $columnData["Cl"];
        $model->LOI = $columnData["LOI"];
        $model->LSF = $columnData["LSF"];
        $model->SM = $columnData["SM"];
        $model->AM = $columnData["AM"];
        $model->IM = $columnData["IM"];
        $model->C4AF = $columnData["C4AF"];
        $model->NAEQ = $columnData["NAEQ"];
        $model->C3S = $columnData["C3S"];
        $model->C3A = $columnData["C3A"];
        $model->SourceDeployed = $columnData["SourceDeployed"];
        $model->SourceStored = $columnData["SourceStored"];
        $model->CPS = $columnData["CPS"];
        $model->K = $columnData["K"];
        $model->V2O5 = $columnData["V2O5"];
        $model->CdO = $columnData["CdO"];
        $model->GCV = $columnData["GCV"];
        $model->CPS_det1 = $columnData["CPS_det1"];
        $model->CPS_Det2 = $columnData["CPS_Det2"];

        if ($model->save()) {
            $this->sendSuccessResponse(array('message' => 'Updated Succesfully'));
        } else {

            $this->sendFailedResponse(array('message' => 'Not Updated'));
        }
    }

public function actionUpdateTons($id) {
        
		
		$model = $this->loadModel($id);

        $columnData = $this->request;


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
		$updateSql = "update rta_tag_index_completed  set totalTonsRemoved = '".$columnData['totalTons']."' where tagID = '$id'";
		
		$result = Yii::app()->db->createCommand($updateSql)->query();
        
        
        if ($result) {
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

        $this->sendSuccessResponse(array('message' => 'Deleted successfully'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $query = Yii::app()->db->createCommand()
                ->select()
                ->from('rta_tag_index_completed');
        if (isset($_REQUEST['tagGroupID']))
            $query = $query->where('tagGroupID = ' . $_REQUEST['tagGroupID']);
        $result = $query->queryAll();
        foreach($result as  $key => $value){
            $rows = AnalysisDataProvider::getDataByInterval(array('LSF'),$value['LocalstartTime'],$value['LocalendTime']);
           
            $lsf =  LabUtility::getColumn($rows,'LSF');
    
            $std =  LabUtility::stdDeviation($lsf);

            $result[$key]['LSF_STD']= $std;

            foreach($value as  $key1=> $value1){
                if($value1 == NULL){
                    $result[$key][$key1]="0.00";
                }
            }
        }
        $this->sendSuccessResponse(array('data' => $result));
    }

    public function actionQuery() {


        $page = ((int) (Yii::app()->request->getParam('page', 0)));

        $pageSize = ((int) (Yii::app()->request->getParam('pageSize', 10)));

        $query = Yii::app()->db->createCommand()
                ->select()
                ->from('rta_tag_index_completed');

        if (isset($_REQUEST['tagGroupID']))
            $query = $query->where('tagGroupID = ' . $_REQUEST['tagGroupID']);
        $countresult = $query->queryAll();
        
        $count = count($countresult);
        
        $result = Yii::app()->db->createCommand()
                ->select()->where('tagGroupID = ' . $_REQUEST['tagGroupID'])
				->order('LocalendTime DESC')
                ->from('rta_tag_index_completed')->limit($pageSize)
            ->offset(($page -1) * $pageSize)
        ->queryAll();
		
		$fResult = array();
		foreach($result as $tag){
			
			$removeTons = isset($tag["totalTonsRemoved"]) ? $tag["totalTonsRemoved"] : 0;
			$tag["totalTons"] =  $tag["totalTons"] - $removeTons;
			
			
			$fResult[] = $tag;
			
		}

        $this->sendSuccessResponse(array('data' => $fResult , 'count' => $count , 'page' =>$page , 'pageSize' => $pageSize));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new RtaTagIndexCompleted('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RtaTagIndexCompleted']))
            $model->attributes = $_GET['RtaTagIndexCompleted'];

        $this->render('admin', array(
            'model' => $model,
        ));
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
//            echo "New Tag is created added";
        } else {
            echo "error";
            $this->sendFailedResponse($newTag->errors);
        }
        $timeSeries = array();
//        var_dump($tagList);
//        die();
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
//                echo "Added Sub tag and deleted orginal tag";
                $tagModel->delete();
            } else {
//            echo "error";
//                var_dump($subTag->errors);
            }
        }

        $minTime = min($timeSeries);
        $maxTime = max($timeSeries);

        $newTag->LocalstartTime = date("Y-m-d H:i:s", $minTime);
        $newTag->LocalendTime = date("Y-m-d H:i:s", $maxTime);

        if ('1' != $hasQueued) {

//            echo "Finding average";
            $average = TagHelper::getTagAvg($newTag);
//            echo "Calculated average";
            foreach ($average as $key => $value) {

                try {
                    $newTag->$key = $value;
                } catch (Exception $ex) {

//                    echo "Some exception while handling =>" . $key;
                }
            }
        }

        $newTag->save();

        $this->sendSuccessResponse(array('data' => $newTag));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return RtaTagIndexCompleted the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = RtaTagIndexCompleted::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param RtaTagIndexCompleted $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'rta-tag-index-completed-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
