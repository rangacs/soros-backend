<?php

class TagQueuedController extends BaseController {

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        
		$anDataprovider = new AnalysisDataProvider();
        	$tagModel = $this->loadModel($id);
		
		$average = $anDataprovider->getTagAvg($tagModel);
		$average["LocalstartTime"] = $tagModel->LocalstartTime;
		$average["LocalendTime"] = $tagModel->LocalendTime;
		$average["tagName"] = $tagModel->tagName;
                $average["tagID"] = $tagModel->tagID;
		$std = $anDataprovider->getTagStd($tagModel);
		
		$std['tagName'] =  "Standard Deviation";
		$std["LocalstartTime"] = "Standard Deviation";
		$std["LocalendTime"] = "-";
		
		
		$std['totalTons'] =  "0";
		
       // $this->sendSuccessResponse(array('data' => $tagModel));;
		
        $this->sendSuccessResponse(array('data' => array('average'=>$average , 'std' => $std)));;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {


        $columnData = $this->request;

        $model = new TagQueued();

        $model->rtaMasterID = $columnData['rtaMasterID'];
        $model->status = 'queued';
        $model->tagName = $columnData['tagName'];
        $model->tagGroupID = $columnData['tagGroupID'];
        $model->LocalstartTime = $columnData['LocalstartTime'];
        $model->LocalendTime = $columnData['LocalendTime'];
		$model->createdOn = date("Y-m-d H:i:s");
		$model->createdBy = 3;
		$model->updatedOn = date("Y-m-d H:i:s");
		$model->updatedBy = 3;//date("Y-m-d H:i:s");
		

        // Stoping running tags
        $queuedTagList = TagQueued::model()->findAll();

        if ($queuedTagList) {

            foreach ($queuedTagList as $tag) {
               
                $result = $this->stopQueuedTag($tag->tagID);
            }
        }

        if ($model->save()) {
            
		$this->sendSuccessResponse(array('message' => 'Created Successfully'));

        } else {
  
        }
    }

        public function actionGetData($id) {
		
		$anDataprovider = new AnalysisDataProvider();
        $tagModel = $this->loadModel($id);
		
		$page = ((int) (Yii::app()->request->getParam('page', 0)));

        $pageSize = ((int) (Yii::app()->request->getParam('pageSize', 50)));
        $interval = ((int) (Yii::app()->request->getParam('interval', 1)));

		$data = $anDataprovider->getTagData($tagModel,$page, $pageSize, $interval);
		
		
        $this->sendSuccessResponse(array('data' => $data));
    }
    /**
     * Creates a Resumed Tag.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionResume($id) {



        $columnData = $this->request;

        
        $completedModel = RtaTagIndexCompleted::model()->findByPk($id);
        
        if ($completedModel === null)
            throw new CHttpException(404, 'The requested page does not exist.');
		
		
		
		 $sql = "select * from rta_tag_index_sub_tag where tagID = '".$completedModel->tagID."'";
		 $subTagList = Yii::app()->db->createCommand($sql)->query()->readAll();	
        
        $model = new TagQueued();
        
        $model->rtaMasterID = $columnData['rtaMasterID'];
        $model->status = 'queued';
        $model->tagName = $columnData['tagName'];
        $model->tagGroupID = $columnData['tagGroupID'];
        $model->LocalstartTime = $completedModel->LocalstartTime;
        $model->LocalendTime = $columnData['LocalendTime'];
        $model->totalTonsRemoved = $completedModel->totalTonsRemoved ;

        // Stop running tags
        $queuedTagList = TagQueued::model()->findAll();

        if ($queuedTagList) {

            foreach ($queuedTagList as $tag) {
                $model->save();
                $this->stopQueuedTag($tag->tagID);
            }
        }
		
		//var_dump($subTagList);
		//die();
		// If tag has queued tag 
		if(count($subTagList) > 0){
			
			if ($model->save()) {
            
			
				    // Queued 
	        $subTag = new SubTag();
            $subTag->tagID = $model->tagID;
            $subTag->tagName = $model->tagName;
            $subTag->LocalendTime = $model->LocalendTime;

            $subTag->LocalstartTime = $columnData['LocalstartTime'];//date('Y-m-d H:i:s',time());

            $subTag->status = $model->status;
            $subTag->rtaMasterID = $model->rtaMasterID;
            $subTag->tagGroupID = $model->tagGroupID;

            if ($subTag->save()) {
               // $tagModel->delete(); //TODO
		        $completedModel->delete();

            } else {
                var_dump($subTag->errors);
            }
			
			
			foreach($subTagList as $subTag){
				
				$subTagQuery = "update rta_tag_index_sub_tag set tagID = '".$model->tagID."' where sub_tag_id = '".$subTag['sub_tag_id']."'";
				
				//echo $subTagQuery;
				
		       		$res = Yii::app()->db->createCommand($subTagQuery)->query();	
        
				
				
			}
			
			
			$this->sendSuccessResponse(array('message' => 'Created Succesfully'));
		}

			
		}else{
			
			if ($model->save()) {
            

	    // Completed
	        $subTag = new SubTag();
            $subTag->tagID = $model->tagID;
            $subTag->tagName = $completedModel ->tagName;
            $subTag->LocalendTime = $completedModel ->LocalendTime;
            $subTag->LocalstartTime = $completedModel ->LocalstartTime;
            $subTag->status = $completedModel ->status;
            $subTag->rtaMasterID = $completedModel ->rtaMasterID;
            $subTag->tagGroupID = $completedModel ->tagGroupID;
	        $subTag->save();
			
		// Queued 
	    $subTag = new SubTag();
            $subTag->tagID = $model->tagID;
            $subTag->tagName = $model->tagName;
            $subTag->LocalendTime = $model->LocalendTime;

            $subTag->LocalstartTime = $columnData['LocalstartTime'];//date('Y-m-d H:i:s',time());

            $subTag->status = $model->status;
            $subTag->rtaMasterID = $model->rtaMasterID;
            $subTag->tagGroupID = $model->tagGroupID;

            if ($subTag->save()) {
               // $tagModel->delete(); //TODO
		        $completedModel->delete();

            } else {
                var_dump($subTag->errors);
            }	


            $this->sendSuccessResponse(array('message' => 'Created Succesfully'));
        } else {
            $this->sendFailedResponse(array('message' => 'Not Created'));
        }
			
		}

        
    }
    public function actionResumeOld($id) {



        $columnData = $this->request;

        
        $completedModel = RtaTagIndexCompleted::model()->findByPk($id);
        
        if ($completedModel === null)
            throw new CHttpException(404, 'The requested page does not exist.');
		
		
		$subTags =  "select * from ";
        
        $model = new TagQueued();
        
        $model->rtaMasterID = $columnData['rtaMasterID'];
        $model->status = 'queued';
        $model->tagName = $columnData['tagName'];
        $model->tagGroupID = $columnData['tagGroupID'];
        $model->LocalstartTime = $completedModel->LocalstartTime;
        $model->LocalendTime = $columnData['LocalendTime'];

        // Stop running tags
        $queuedTagList = TagQueued::model()->findAll();

        if ($queuedTagList) {

            foreach ($queuedTagList as $tag) {
                $model->save();
                $this->stopQueuedTag($tag->tagID);
            }
        }

        if ($model->save()) {
            

	    // Completed
	    $subTag = new SubTag();
            $subTag->tagID = $model->tagID;
            $subTag->tagName = $completedModel ->tagName;
            $subTag->LocalendTime = $completedModel ->LocalendTime;
            $subTag->LocalstartTime = $completedModel ->LocalstartTime;
            $subTag->status = $completedModel ->status;
            $subTag->rtaMasterID = $completedModel ->rtaMasterID;
            $subTag->tagGroupID = $completedModel ->tagGroupID;
	       $subTag->save();

	    // Queued 
	    $subTag = new SubTag();
            $subTag->tagID = $model->tagID;
            $subTag->tagName = $model->tagName;
            $subTag->LocalendTime = $model->LocalendTime;

            $subTag->LocalstartTime = $columnData['LocalstartTime'];//date('Y-m-d H:i:s',time());

            $subTag->status = $model->status;
            $subTag->rtaMasterID = $model->rtaMasterID;
            $subTag->tagGroupID = $model->tagGroupID;

            if ($subTag->save()) {
               // $tagModel->delete(); //TODO
		        $completedModel->delete();

            } else {
                var_dump($subTag->errors);
            }


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
    public function actionUpdate($id) {
        $columnData = $this->request;
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model->rtaMasterID = $columnData['rtaMasterID'];
        $model->status = 'queued';
        $model->tagName = $columnData['tagName'];
        $model->tagGroupID = $columnData['tagGroupID'];
        $model->LocalstartTime = $columnData['LocalstartTime'];
        $model->LocalendTime = $columnData['LocalendTime'];


        if ($model->save()) {
            $this->sendSuccessResponse(array('message' => 'Updated Succesfully'));
        } else {

            $this->sendFailedResponse(array('message' => 'Not Updated'));
        }
    }

    public function actionTagProcessStop($id) {

        if($this->stopQueuedTag($id)){
            $this->sendSuccessResponse(array('message' => 'Tag processed successfully'));
        }else{
            $this->sendFailedResponse(array('message' => 'unable to process tag'));
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
		
		$queuedTags = array();
        $result = Yii::app()->db->createCommand()
                ->select()
                ->from('rta_tag_index_queued')
                ->queryAll();

        
		$analysisDataproider = new AnalysisDataProvider();
		$stdDeviation = array();
        foreach ($result as $tag) {
        //var_dump($tag);    
		//continue;	
	    $tagModel = $this->loadModel($tag['tagID']);		
	
	    $average = $analysisDataproider->getTagAvg($tagModel);
		
		$stdArray = $analysisDataproider->getTagStd($tagModel);

	    $stdDeviation[] = 	$stdArray;	
		$average['LSF_STD'] = $stdArray['LSF'];
		$average['totalTons'] = $average["totalTons"] - $tagModel->totalTonsRemoved;
		
	    
        $queuedTags[] = array_merge($average, $tag);
		
            
        }
      
        $this->sendSuccessResponse(array('data' => $queuedTags,'std'=> $stdDeviation));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new RtaTagIndexQueued('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RtaTagIndexQueued']))
            $model->attributes = $_GET['RtaTagIndexQueued'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return RtaTagIndexQueued the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = RtaTagIndexQueued::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionStopTags() {
        $tagId = $_REQUEST['id'];
        $this->stopQueuedTag($tagId);
    }

    private function stopQueuedTag($tagId) {

        $currentTime = time();
        $tagQueuedmodel = $this->loadModel($tagId);
        $tagStopTime = strtotime($tagQueuedmodel->LocalendTime);
        if($currentTime < $tagStopTime){
          $tagQueuedmodel->LocalendTime = date('Y-m-d H:i:s', time());

        }
          
//
		if (true) {	

			$anDataprovider = new AnalysisDataProvider();
			
            $columns = HeliosUtility::getColumnsForTable('analysis_A1_A2_Blend');
		
			$columnData = $anDataprovider->getTagAvg($tagQueuedmodel);
			
            $completedModel = new RtaTagIndexCompleted();
             $completedModel->totalTonsRemoved = $tagQueuedmodel->totalTonsRemoved ;
			
			$tagStd = new TagStd();
			
			$stdDeviation = $anDataprovider->getTagStd($tagQueuedmodel);
	
			$columns[] = "LSF_STD";
			$rt = array();
			
			//average
            foreach ($columns as $col) {
                if (!in_array($col, array('goodDataSecs', 'endTic', 'startTic', 'dataID'))) {
                    try {
                        $completedModel->$col = $columnData[$col];
						
                    } catch (Exception $ex) {
                       // echo $col;
                    }
                }
            }
			//stddeviation 
			foreach ($columns as $col) {
                if (!in_array($col, array('goodDataSecs', 'endTic', 'startTic', 'dataID'))) {
                    try {
                        $tagStd->$col = $stdDeviation[$col];
						
                    } catch (Exception $ex) {
                       // echo $col;
                    }
                }
            }
			
			 $sql = "select * from rta_tag_index_sub_tag where tagID = '".$tagQueuedmodel->tagID."'";
             		$res = Yii::app()->db->createCommand($sql)->query()->readAll();	
			 
			 $hasMerge = 'false';
			 //Have sub tag
			 if(is_array($res) && count($res) > 0){
				 
				 $hasMerge = 'true';
				 
			 }
			 
			
            $completedModel->tagName = $tagQueuedmodel->tagName; //$result["tagName"];
            $completedModel->hasMerge = $hasMerge;
            $completedModel->tagGroupID = $tagQueuedmodel->tagGroupID;
            $completedModel->LocalstartTime = $tagQueuedmodel->LocalstartTime;
            $completedModel->LocalendTime = $tagQueuedmodel->LocalendTime;
            $completedModel->rtaMasterID = '15';
            $completedModel->goodDataSecondsWeight = 1;
            $completedModel->massflowWeight = 1;
            $completedModel->status = 'completed';
			
            $cLSF_STD = "LSF_STD";
            $completedModel->$cLSF_STD = $stdDeviation['LSF'];
            
			
			
            $tagStd->tagName = $tagQueuedmodel->tagName; //$result["tagName"];
            $tagStd->hasMerge = $hasMerge;
            $tagStd->tagGroupID = $tagQueuedmodel->tagGroupID;
            $tagStd->LocalstartTime = $tagQueuedmodel->LocalstartTime;
            $tagStd->LocalendTime = $tagQueuedmodel->LocalendTime;
            $tagStd->rtaMasterID = '15';
            $tagStd->goodDataSecondsWeight = 1;
            $tagStd->massflowWeight = 1;
            $tagStd->status = 'completed';
            

            if ($completedModel->save()) {
				 if(is_array($res) && count($res) > 0){
				 
				 
				 $sql = "update rta_tag_index_sub_tag set status = 'completed' ,tagID = '".$completedModel->tagID."' where tagID = '".$tagQueuedmodel->tagID."'";
				 $res = Yii::app()->db->createCommand($sql)->query();	
				 
			 }
			
				$tagQueuedmodel->delete();
				$tagStd->tagID = $completedModel->tagID;
			    $tagStd->save();
                //$this->sendSuccessResponse(array('message' => $completedModel));
                return $completedModel;
            } else {

                $this->sendFailedResponse(array('message' => $completedModel->errors));
            }
        } else {
//           
            $this->sendFailedResponse(array('message' => $completedModel->errors));
        }
    }

    /**
     * Performs the AJAX validation.
     * @param RtaTagIndexQueued $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'rta-tag-index-queued-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
