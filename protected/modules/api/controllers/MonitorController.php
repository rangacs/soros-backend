<?php

class MonitorController extends BaseController {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $result = $this->loadModel($id);
        $this->sendSuccessResponse(array($result));
    }

    public function actionTagSummery() {

        $limit = 5;
        $moniterTag = array();
        $queuedTag = Yii::app()->db->createCommand()
                ->select()
                ->from('rta_tag_index_queued')
                ->queryRow();
				
	    $allRows = array();			
        if ($queuedTag) {
            $limit = 4;
            $queuedStartTime = $queuedTag['LocalstartTime'];
            $queuedEndTime = $queuedTag['LocalendTime'];
            $elementArray = HeliosUtility::getDisplayElements();
            $newTag = new TagQueued();
            $newTag->LocalstartTime = $queuedStartTime;
            $newTag->LocalendTime = $queuedEndTime;
            $newTag->tagID = $queuedTag['tagID'];
			
			
		
		$tagModel = RtaTagIndexQueued::model()->findByPk($queuedTag['tagID']); 
		
		$analysisDataproider =  	new AnalysisDataProvider();
	    $average = $analysisDataproider->getTagAvg($tagModel);
		

		//$average = AnalysisDataProvider::queryAvg($elementArray, date("Y-m-d H:i:s", strtotime($queuedStartTime)), date("Y-m-d H:i:s", strtotime($queuedEndTime)));
		
		$rows = AnalysisDataProvider::getDataByInterval(array('LSF'),$queuedStartTime,$queuedEndTime);
		
		$allRows[] = $rows;

        
        
        $result = $average;
        $result['tagName'] = $queuedTag['tagName']." (Q)";
        $result['status'] = $queuedTag['status'];
		
        }
        if (!$queuedTag) {
            $elementArray = HeliosUtility::getDisplayElements();
        }

        $completedTag = Yii::app()->db->createCommand("select *  from  rta_tag_index_completed where 1 order by LocalendTime DESC limit $limit")->queryAll();

        // $completedTag = array_slice($completedTagR, 0, $limit);
        foreach ($completedTag as $tag) {
			
			$tag['totalTons'] = $tag["totalTons"] - $tag['totalTonsRemoved'];
			
            $moniterTag[] = $tag;
			$rows = AnalysisDataProvider::getDataByInterval(array('LSF'),$tag['LocalstartTime'],$tag['LocalendTime']);
			$allRows[] = $rows;
        }
        if ($queuedTag) {
            $moniterTag[] = $result;
        }
        foreach ($moniterTag as $key => $value) {
			
            foreach ($value as $key1 => $value1) {
                if ($value1 == NULL) {
                    $moniterTag[$key][$key1] = "0.00";
                }
            }
        }
        
        /**
         * 
         * Calculate standard deviation 
         */
        $subQury = array();
        $stdSelect = array();
        foreach ($elementArray as $ele) {

            $col = $ele;
            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $col = "( " . $formula->formula . " )";
            }
            $stdSelect[] = " round( STDDEV( $col ) , 3)  as $ele";
        }
        foreach ($moniterTag as $tag) {

            $subQury[] = " (LocalendTime > '" . $tag['LocalstartTime'] . "' AND LocalendTime < '" . $tag['LocalendTime'] . "' )";
        }
        if (empty($subQury)) {
            $whereSql = " ";
        } else {
            $whereSql = " where " . implode(' or ', $subQury);
        }
        $selectsql = " select " . implode(',', $stdSelect);


        $standardDeviation = Yii::app()->db->createCommand($selectsql . " from analysis_A1_A2_Blend " . $whereSql)->queryRow();
		
		$dairy = array();
		
		foreach($allRows as $data){
			
			$dairy = array_merge($dairy,$data);
		}
		
		$lsf =  LabUtility::getColumn($dairy,'LSF');
		$tons =  LabUtility::getColumn($dairy,'totalTons');
		
		$totaltons = array_sum($tons);

        $std =  LabUtility::stdDeviation($lsf);
		

        $standardDeviation['tagName'] = "Standard Deviation";
		$standardDeviation['totalTons'] = $totaltons;
        $standardDeviation['status'] = "-";
        $standardDeviation['LocalstartTime'] = "-";
        $standardDeviation['LocalendTime'] = "-";
        $standardDeviation['LSF_STD'] = $std;
		
        if (!empty($moniterTag)) {
            $moniterTag[] = $standardDeviation;
        }
		
        $this->sendSuccessResponse(array('data' => array_reverse($moniterTag)));
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {

        $columnData = $this->request;

        $model = new MonitorColumns;

        $model->type = $columnData['type'];
        $model->value = $columnData['value'];

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
    public function actionUpdate($id) {
        $columnData = $this->request;
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model->type = $columnData['type'];
        $model->value = $columnData['value'];

        if ($model->save()) {
            $this->sendSuccessResponse(array('message' => 'Updated Succesfully'));
        } else {

            $this->sendFailedResponse(array('message' => 'Not Updated'));
        }
    }
	
	 public function actionUpdateCol() {
		 
        $columnData = $this->request['col'];
		$val = $this->request['value'];
		
		
        $model = $this->loadModel($columnData['id']);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        if ($model->type == "custom_interval" ) {
			$model->start_time  = $val;
			$model->save();
			
            $this->sendFailedResponse(array('message' => 'Not Updated'));
        }else if ($model->type == "interval_range" ) {
			$model->start_time  = $val['start'];
			$model->end_time  = $val['end'];
			$model->save();
			
            $this->sendFailedResponse(array('message' => 'Not Updated'));
        } else {

			
			$model->value = $val;
		$model->display_name = $val." M";
		
			$model->save();
            $this->sendSuccessResponse(array('message' => 'Updated Succesfully'));
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
    public function actionColumns() {

        $result = Yii::app()->db->createCommand()
                ->select()
                ->from('monitor_columns')
                ->order('position')
                ->queryAll();
        $this->sendSuccessResponse(array('data' => $result));
    }

    public function actionDropdowns() {

        $result = Yii::app()->db->createCommand()
                ->select()
                ->from('monitor_columns_dropdown')
                ->queryAll();

        $queuedTags = TagQueued::model()->findAll();
        foreach ($queuedTags as $key => $tag) {
            $result[] = array('id' => $key + 100, "type" => 'queued', 'dropdown_values' => $tag->tagID, 'display_name' => $tag->tagName);
        }
        $this->sendSuccessResponse(array('data' => $result));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new MonitorColumns('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MonitorColumns']))
            $model->attributes = $_GET['MonitorColumns'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return MonitorColumns the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = MonitorColumns::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param MonitorColumns $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'moniter-columns-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
