<?php

class RawmixController extends UIDashboardController {

    public $test_A = '0'; //An acceptable comment made by userA..
    //Expecting changes by userA above..-parent..
    //public $test_B = "Yii::app()->params['bullseye']";
    // uncomment the following to apply new layout for the controller view.
    //public $layout = '//layouts/column2';
    public $actionId = NULL;

    public function beforeAction($action) {
        parent::beforeAction($action);

        $usage_data1 = array(
            array(
                'id' => 0,
                'event' => 'Total',
                'channels' => rand(10, 100),
            ),
            array(
                'id' => 0,
                'event' => 'Dial In',
                'channels' => rand(10, 100),
            ),
            array(
                'id' => 0,
                'event' => 'Dial Out',
                'channels' => rand(10, 100),
            ),
            array(
                'id' => 0,
                'event' => 'Idle',
                'channels' => rand(10, 100),
            ),
        );


        //Abhinandan. Implements a dataProvider based on the $usage_data1 array..
        $usageDataProvider1 = new CArrayDataProvider($usage_data1, array(
            'pagination' => false
        ));


        //Controller::fb('DC, alpha!');
        //Controller::fb($action);        //CAction:_id => 'dash'
        //CAction:_controller => DashController

        $actionId = $action->id;        // 'dash'


        /*
         * Abhinandan.
         *    IMPORTANT: The 'content' index is reponsible for rendering the actual dashboard gadget..      
         *      (windows development); Css Files are being pulled from: 
         *   /helios_official_ug_logg_lang_dbcache_hs/themes/tutorialzine1'
         * 
         */
        if ($actionId == "dash" || $actionId == "index") {
            $this->setPortlets(
                    array(
                        array(
                            'id' => 'Live_Status',
                            'title' => 'Live Status',
                            'content' => $this->renderPartial('_dgrids/_d_liveStats', false, true)
                        //'content' => $this->renderPartial('_d_liveStats', array('test_A' => "wikkensHere"), true)
                        ),
                        array(
                            'id' => 'System_Messages',
                            'title' => 'Analysis Results',
                            'content' => $this->renderPartial('_dgrids/_d_grid_view', array('dataProvider' => $usageDataProvider1), true)
                        ),
                        array(
                            'id' => 'Meter_Gauge',
                            'title' => 'Meter Gauge',
                            'content' => $this->renderPartial('_dgrids/_d_meter', false, true)
                        ),
                        array(
                            'id' => 'Charts',
                            'title' => 'Charts',
                            'content' => $this->renderPartial('_dgrids/_d_chart', false, true)
                        ),
                        array(
                            'id' => 'Alerts',
                            'title' => 'Alerts',
                            'content' => $this->renderPartial('_dgrids/_d_alerts', false, true)
                        ),
                        array(
                            'id' => 'AlarmBar',
                            'title' => 'Alert Bar',
                            'content' => $this->renderPartial('_dgrids/_d_abar', false, true)
                        ),
                        array(
                            'id' => 'IdiotLights',
                            'title' => 'Status Indicators',
                            'content' => $this->renderPartial('_dgrids/_d_ilights', false, true)
                        ),
                    )//end Container array for $this->setPortlets..
            ); //end $this->setPortlets..
            //Abhinandan. Feb27th...Need to finish exacting what our error gadget should look like... '_d_liveStats' is our template..
            $this->setErrorPortlets(
                    array(
                        array(
                            'id' => 'Live_Status',
                            'title' => 'Live Status',
                            'content' => $this->renderPartial('_dgrids/_d_liveStats', false, true)
                        ),
                    )
            );

            Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/progress-slider.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/colors.css');
            Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/growl.css');
        }
        return true;
    }

//end beforeAction()..



    /*
     *  Init (DashController) 
     */

    public function init() {

        parent::init();

        /*
         *  DB Table Stuffing:
         *  i.)  table = 'dashboard_page'
         *  ii.) fields: user_id, title..
         */
        //$this->setTableParams('dashboard_page', 'user_id', 'title');   //Set params for 'UIDashboardController' class..

        /* Abhinandan. Feb8th: Dashboards page should be pulling from this location */
        $this->setTableParams('gadlay_layouts', 'user_id', 'gadPlacement', 'default_layout');   //Set params for 'UIDashboardController' class..

        $usage_data1 = array(
            array(
                'id' => 0,
                'event' => 'Total',
                'channels' => rand(10, 100),
            ),
            array(
                'id' => 0,
                'event' => 'Dial In',
                'channels' => rand(10, 100),
            ),
            array(
                'id' => 0,
                'event' => 'Dial Out',
                'channels' => rand(10, 100),
            ),
            array(
                'id' => 0,
                'event' => 'Idle',
                'channels' => rand(10, 100),
            ),
        );

        $usageDataProvider1 = new CArrayDataProvider($usage_data1, array(
            'pagination' => false
        ));



        $this->setPortlets(
                array(
                    array(
                        'id' => 'Live_Status',
                        'size' => array(
                            '0' => 4,
                            '4' => 4,
                            "6" => 6,
                        ),
                        'title' => 'Live Status',
                        'content' => $this->renderPartial('_cgrids/_c_liveStats', false, true)
                    ),
                    array(
                        'id' => 'System_Messages',
                        'size' => array(
                            '0' => 6,
                            '4' => 4,
                            "6" => 6
                        ),
                        'title' => 'Messages',
                        'content' => $this->renderPartial('_cgrids/_c_grid_view', array('dataProvider' => $usageDataProvider1), true)
                    ),
                    array(
                        'id' => 'Meter_Gauge',
                        'size' => array(
                            '0' => 2,
                            '2' => 2
                        ),
                        'title' => 'Meter',
                        'content' => $this->renderPartial('_cgrids/_c_meter', false, true)
                    ),
                    array(
                        'id' => 'Charts',
                        'size' => array(
                            '0' => 6,
                            '4' => 4,
                            "6" => 6
                        ),
                        'title' => 'Charts',
                        'content' => $this->renderPartial('_cgrids/_c_chart', false, true)
                    ),
                    array(
                        'id' => 'Alerts',
                        'size' => array(
                            '0' => 2,
                            '2' => 2
                        ),
                        'title' => 'Alert',
                        'content' => $this->renderPartial('_cgrids/_c_alerts', false, true)
                    ),
                    array(
                        'id' => 'AlarmBar',
                        'size' => array(
                            '0' => 6
                        ),
                        'title' => 'Alert Bar',
                        'content' => $this->renderPartial('_cgrids/_c_abar', false, true)
                    ),
                    array(
                        'id' => 'IdiotLights',
                        'size' => array(
                            '0' => 6
                        ),
                        'title' => 'Status Lights',
                        'content' => $this->renderPartial('_cgrids/_c_ilights', false, true)
                    ),
                    //Abhinandan. Jan13th 2013..
                    array(
                        'id' => 'Table',
                        'size' => array(
                            '0' => 6,
                            '4' => 4,
                            '6' => 6
                        ),
                        'title' => 'Table',
                        'content' => $this->renderPartial('_cgrids/_c_table', false, true)
                    ),
                )//end container array of $this->setPortlets..
        ); //end $this->setPortlets..
        //set content BEFORE dashboard
        $this->setContentBefore(
        //Pay attension: ExtController looking view in current dir!!!
        //$this->renderPartial('/../views/dash/before', null, true)
        );

        //set content AFTER dashboard
        $this->setContentAfter('');

        // uncomment the following to apply jQuery UI theme
        // from protected/components/assets/themes folder
        //$this->applyTheme('black-tie');
        // uncomment the following to change columns count
        $this->setColumns(2);

        // uncomment the following to enable autosave
        $this->setAutosave(true);                             //Abhinandan. auto saves when user moves the gadget around..
        // uncomment the following to disable dashboard header
        $this->setShowHeaders(true);

        // uncomment the following to enable context menu and add needed items
        $this->menu = array(
            array('label' => 'Index', 'url' => array('index')),
        );
    }

//end Init for DashController..

    public function actionSubmitPr() {
        $this->render('submitPr');
    }
    
	public function actionStatistics() {
		   
        $this->render('statistics');
    }

    public function actionSettings() {

        if (isset($_REQUEST['id'])) {

            $productModel = ProductProfile::model()->findByPk($_REQUEST['id']);
        } else {
            $productModel = new ProductProfile;
        }
        $productModel = $this->render('settings', array('productModel' => $productModel));
    }

    public function actionCreateProfile($name = '') {

        $model = new ProductProfile;

        $model->product_name = $name;

        $message = array();

        if ($model->save(false)) {
            $message['error'] = 0;
            $message['product_id'] = $model->product_id;

            $this->renderPartial('_product_form', array('model' => $model));
        } else {

            $message['error'] = 1;
            $message['detail'] = $model->errors;
        }

        //  echo json_encode($message);
    }

    public function actionUpdateProfile() {



        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model = ProductProfile::model()->findByPk($_POST['profile_id']);
        $params = array();

        parse_str($_POST['data'], $params);
        // $model->attributes = $params['ProductProfile'];


        if (isset($params['ProductProfile'])) {
            $model->attributes = $params['ProductProfile'];
            if ($model->save(false))
                echo 'success';
            //$this->redirect(array('view','id'=>$model->product_id));
        }
    }

    public function actionLoadSetPointForm() {
        $model = new SetPoints;

        $this->renderPartial('_setpoints_form', array('model' => $model));
    }

    public function actionLoadSourceForm() {
        $model = new Source;

        $this->renderPartial('_source_form', array('model' => $model));
    }

    public function actionElementCompostionForm() {
        $model = new ElementComposition;

        $this->renderPartial('_element_composition_form', array('model' => $model));
    }

    //
    public function actionElementCompostionList($sourceid) {



        $dataList = ElementComposition::model()->findAll('source_id = :sid', array(':sid' => $sourceid));

        if (!empty($dataList)) {

            $this->renderPartial('_element_composition_list', array('dataList' => $dataList));
        } else {

            $message = array();
            $message['message'] = 'No elements are present';
            $message['error'] = 1;

            echo json_encode($json);
        }
    }

    public function actionCreateElementCompostion() {

        $model = new ElementComposition();

        $params = array();

        parse_str($_POST['data'], $params);
	 $params['ElementComposition']['element_value'] = ($params['ElementComposition']['element_value'] > 0)? (($params['ElementComposition']['element_value']/100)) : 0;
        $model->attributes = $params['ElementComposition'];

        if (!empty($model->source_id)) {
            $model->save(false);


            $dataList = ElementComposition::model()->findAll('source_id = :sid', array(':sid' => $model->source_id));

            $this->renderPartial('_element_composition_list', array('dataList' => $dataList));
        } else {

            $meessage['error'] = 1;
            $meessage['detail'] = 'Please select source';
            if (empty($model->source_id)) {

                $meessage['detail'] .= 'And source Id';
            }

            echo json_encode($meessage);
        }
    }

    public function actiondeleteElementCompostion($elementid) {


        if (!empty($elementid)) {
            $model = ElementComposition::model()->findByPk($elementid);
            $model->delete();

            $dataList = ElementComposition::model()->findAll('source_id = :sid', array(':sid' => $model->source_id));

            $this->renderPartial('_element_composition_list', array('dataList' => $dataList));
        } else {

            $message = array();

            $message['error'] = 1;
            $message['message'] = 'Element id is empty';
            echo json_encode($message);
        }
    }

    //
    public function actionCreateSetPoints() {

        $model = new SetPoints;
        $params = array();
        parse_str($_POST['data'], $params);
        $model->attributes = $params['SetPoints'];

        //Check if it is related to any product profile 

        if (!empty($model->product_id)) {
            $model->save(false);

            $dataList = SetPoints::model()->findAll('product_id = :pid ', array(':pid' => $model->product_id));

            $this->renderPartial('_setpoints_list', array('dataList' => $dataList));
        } else {

            $meessage['error'] = 1;
            $meessage['detail'] = 'Error creating set-point. Please contact Sabia.';

            echo json_encode($meessage);
        }

        // echo json_encode($model->attributes);
    }

    public function actionUpdateSetPoints($id, $data = null) {
        $model = SetPoints::model()->findByPk($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (empty($id)) {
            $message['error'] = 1;

            $message['message'] = 'not valid set point';

            echo json_encodee($message);

            die();
        }

        if (isset($_REQUEST['data'])) {


            $data = $data;
            $params = array();
            parse_str($data, $params);
            
            $setPointName = $model->sp_name;
            $oldSetPValue =  $model->sp_value_num;
	     
            $model->attributes = $params['SetPoints'];
            $updatedSetPValue = $model->sp_value_num;
			
	    $this->updateConfigLog($params['SetPoints'],"rm_set_points","sp_name",$setPointName);
			
			
            if ($model->save(false)) {
                $dataList = SetPoints::model()->findAll('product_id = :pid', array(':pid' => $model->product_id));
                
                $setPointLog = new SetPointsLog();
                
                $setPointLog->sp_name = $setPointName;
                $setPointLog->sp_value = $updatedSetPValue;
                $setPointLog->sp_updated = date("Y-m-d H:i:s");
                
                $setPointLog->save(false);
                


                $this->renderPartial('_setpoints_list', array('dataList' => $dataList));
            }
        } else {

            $this->renderPartial('_setpoints_form', array('model' => $model));
        }
    }

    public function actionUpdateElementCompostion($elementid, $data = null) {



        $model = ElementComposition::model()->findByPk($elementid);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($data)) {


            $data = $data;
            $params = array();
            parse_str($data, $params);
			$params['ElementComposition']['element_value'] = ($params['ElementComposition']['element_value'] > 0)? (($params['ElementComposition']['element_value']/100)) : 0;

            $model->attributes = $params['ElementComposition'];
			
			$this->updateConfigLog($params['ElementComposition'],"rm_element_composition","element_id",$elementid);
			
            if ($model->save(false)) {
                $dataList = ElementComposition::model()->findAll('source_id = :sid', array(':sid' => $model->source_id));

                $this->renderPartial('_element_composition_list', array('dataList' => $dataList));
            }
        } else {

            $this->renderPartial('_element_composition_form', array('model' => $model));
        }
    }

    public function actionDeleteSetpoint($id) {

        $model = SetPoints::model()->findByPk($id);
        if (SetPoints::model()->deleteByPk($id)) {

            $dataList = SetPoints::model()->findAll('product_id=:id', array(':id' => $model->product_id));
            $this->renderPartial('_setpoints_list', array('dataList' => $dataList));
        }
    }

    public function actionCreateSource() {

        $model = new Source();

        $model->product_id = 1;
        $params = array();
        parse_str($_REQUEST['data'], $params);

        if (!empty($model->product_id)) {
            $model->attributes = $params['Source'];
            $model->save(false);
            $dataList = Source::model()->findAll('product_id = :pid', array(':pid' => $model->product_id));

            $this->renderPartial('_source_list', array('dataList' => $dataList));
        } else {

            $meessage['error'] = 1;
            $meessage['detail'] = 'Please select profile';

            echo json_encode($meessage);
        }
    }
	

    public function actionUpdateSource($sourceid, $data = null) {
		$connection=Yii::app()->db;  
        $model = Source::model()->findByPk($sourceid);

        if (isset($_REQUEST['data'])) {
            $data = $_REQUEST['data'];
            $params = array();
            parse_str($data, $params);
			$params['Source']['src_min_feedrate']=($params['Source']['src_min_feedrate'] > 0)? (($params['Source']['src_min_feedrate']/100)) : 0;
			$params['Source']['src_max_feedrate']=($params['Source']['src_max_feedrate'] > 0)? (($params['Source']['src_max_feedrate']/100)) : 0;
            $model->attributes = $params['Source'];
			$curDateTime = date("Y-m-d H:i:s");
			$srcTyp = $params['Source']['src_type'];
			$srcDel = $params['Source']['src_delay'];
			if(!isset($srcDel) || $srcDel == '')
				$srcDel = 0;
			$this->updateConfigLog($params['Source'],"rm_source","src_name",$params['Source']['src_name']);
			
			$updateQuery = "UPDATE rm_fdr_delay_counter set fdr_delay = $srcDel ,fdr_counter=1, fdr_updated='{$curDateTime}' WHERE fdr_name = '{$srcTyp}'";

			$command = $connection->createCommand($updateQuery);
			$command->execute();
				
            if ($model->save(false)) {
                $dataList = Source::model()->findAll('product_id = :pid', array(':pid' => $model->product_id));
                $this->renderPartial('_source_list', array('dataList' => $dataList));
            }
        } else {
            $this->renderPartial('_source_form', array('model' => $model));
        }
    }

    public function actionDeletesource($sourceid) {

        $model = Source::model()->findByPk($sourceid);
        $model->delete();
        $dataList = Source::model()->findAll('product_id = :pid', array(':pid' => $model->product_id));

        $this->renderPartial('_source_list', array('dataList' => $dataList));
    }

    public function actionCheckProfileName($name) {


        $isExits = ProductProfile::model()->exists("product_name=:pname", array(':pname' => $name));

        // echo $isExits;
        $message = array();
        if ($isExits) {
            $message['error'] = 1;
        } else {

            $message['error'] = 0;
        }

        echo json_encode($message);
    }

    public function actionSubmitProposed() {
    
	$dbName = Yii::app()->params["dbName"];
        $connection=Yii::app()->db;  
    	
        $rawMixParams = array(
            "dummy",
            "Feeder1_cmd_m",
            "Feeder2_cmd_m",
            "Feeder3_cmd_m",
            "Feeder4_cmd_m",
            "Feeder5_cmd_m",
            "Feeder6_cmd_m",
            "Feeder7_cmd_m",
            "Feeder8_cmd_m"
        );
                

        $srcArray = array();
        $pquery = "SELECT src_1_m,src_2_m,src_3_m,src_4_m,src_5_m,src_6_m,src_7_m,src_1_r,src_2_r,src_3_r,src_4_r,src_5_r,src_6_r,src_7_r FROM  rm_source_feeder_inputs WHERE src_id_fk= (SELECT product_id FROM rm_product_profile where default_profile = 1) ORDER BY src_fid DESC LIMIT 1";
        $pId = 0;
        $smfd = 0;
        
        $command = $connection->createCommand($pquery);
		$result = $command->query()->readAll();        

        if (($result)) {
            foreach($result as $row){

                for ($i = 1; $i <= 7; $i++) {

                    $sId = $i;
                    $mfr = $row["src_" . $i . "_m"];
                    $ssm = $row["src_" . $i . "_r"];
                    if ($ssm != 1)
                        $mfr = 0;
                    $smfd += $mfr;

                    $srcArray[$sId] = array("mfr" => $mfr, "sm" => $ssm);
                }
            }
        }
        //echo "SUM: " . $smfd ."\n";
        $feedRatesSubmitted = $_REQUEST["feedRates"];
        
        $setPoints = $_REQUEST['setPoints'];
        $proposed = array();
        $nproposed = array();

        foreach ($feedRatesSubmitted as $id => $val) {
            $keywords = preg_split("/[_]/", $id);

            $kString = isset($keywords[1]) ? $keywords[1] : '';
           
            if ($kString == "pr") {
                $proposed[$keywords[0]] = $val;
            } elseif ($kString == "nw") {
                $nproposed[$keywords[0]] = $val;
            }
        }

        //print_r($proposed);
        //print_r($nproposed);
        //exit();

        for ($i = 1; $i <= count($srcArray); $i++) {
            if (!isset($nproposed[$i]))
                $nproposed[$i] = 0;

            if (($i == 6) || ($i == 7))
                $nproposed[$i] = round(($nproposed[$i] * 1), 2);   //*6.8
            else
                $nproposed[$i] = round(($nproposed[$i] * 1), 2);   //1.6
        }
        //print_r($nproposed);
        //echo "\n";
        $nfdr = 0;
        $colsStr = '';
        $valsStr = '';
        foreach ($nproposed as $sid => $sval) {
            //write tons/hr
            //$nfdr = ($sval * $smfd / 100);
            //Write percentage itself
            $nfdr = $sval;

            //percentage again
            $nfdr = round(($nfdr), 2);

            $colsStr .= "`" . $rawMixParams[$sid] . "`, ";
            $valsStr .= "'" . ($nfdr) . "',";
	    $tmpsql = "UPDATE rm_source set src_proposed_feedrate='$nfdr' where src_id = $sid";
	    $tcommand = $connection->createCommand($tmpsql );
            $tcommand->execute();
        }
        $dateTime = date("Y-m-d H:i:s");

        if (0)
            echo $inQuery . "\n";
        
        //Insert into feeder inputs
        $inQuery = "INSERT INTO `rm_ctrl_output_feedrates` (`id`, {$colsStr} `updated`) VALUES(NULL,{$valsStr}'{$dateTime}')";
        $command = $connection->createCommand($inQuery);
        $command->execute();		

        if (0)
            echo $inQuery. "\n";

        $updQ = "UPDATE  `rm_settings` SET  `varValue` =  '1' WHERE  `rm_settings`.`varName` =  'newRawResult'";
        $command = $connection->createCommand($updQ);
        $command->execute();
		
        $pr = serialize($proposed);
        $npr = serialize($nproposed);
        $user = Yii::app()->user->id;
        $serSetPoints = serialize($setPoints);

        //record into feeder outpuits/changes
        $inuQuery = "INSERT INTO `rm_user_submitted_dump` (`id`, user, proposed, new_proposed, `updated`,setPoints) VALUES(NULL,'$user', '{$pr}','{$npr}','{$dateTime}','{$serSetPoints}')";

        if (0)
            echo $inuQuery . "\n";

		$command = $connection->createCommand($inuQuery);
		$command->execute();        

        echo "Success";
        return 1;
    }

    public function actionGetProgress($productid = NULL) {

        $progress = 0;

        if (isset($productid)) {



            $isProductExits = ProductProfile::model()->exists('product_id = :pid', array(':pid' => $productid));

            if ($isProductExits) {


                $progress += 25;

                $isSetPointExits = SetPoints::model()->exists('product_id = :pid', array(':pid' => $productid));
                if ($isSetPointExits)
                    $progress +=25;


                $isSourceExits = Source::model()->exists('product_id = :pid', array(':pid' => $productid));

                if ($isSourceExits) {
                    $progress += 25;
                    $sourceAll = Source::model()->findAll('product_id = :pid', array(':pid' => $productid));
                    $isElementExits = false;

                    if (!empty($sourceAll)) {

                        foreach ($sourceAll as $source) {

                            $isTmpElementExits = ElementComposition::model()->exists('source_id = :src_id', array(':src_id' => $source->src_id));

                            if ($isTmpElementExits)
                                $isElementExits = true;
                        }
                    }


                    if ($isElementExits)
                        $progress += 25;
                }
            }

            $this->renderPartial('_progress', array('progress' => $progress));
        } else {
            $this->renderPartial('_progress', array('progress' => $progress));
        }
        // $this->renderPartial('_progress',array('progress'=>$progress));
    }

    public function actionSourcelist($productid) {



        $sorcesModel = Source::model()->findAll('product_id = :pid', array(':pid' => $productid));
        $SourcList = CHtml::listData($sorcesModel, 'src_id', 'src_name');
        echo CHtml::dropDownList('selected-source-id', '', $SourcList, array('empty' => 'Select sector', 'class' => 'pull'));
    }

    public function actionLMessages() {

        $this->render('messageLogger');
    }
	
    public function actionHistory() {

        $this->render('history');
    }
      
    public function actionIohistory() {

        $this->render('iohistory');
    }
      
    public function actionSimulate() {

        $this->render('simulate');
    }	
    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $dataProvider = new CActiveDataProvider('ProductProfile');

        $profileLog = new CActiveDataProvider('ProfileLog');
        $this->render('admin', array(
            'dataProvider' => $dataProvider,
            'dataProfileLog' => $profileLog,
        ));
    }

    public function actionCalib() {
        $this->render('calib');
    }
   
   public function actionRmsettings() {

        $dataProvider = new CActiveDataProvider('Settings');

        $this->render('rm_settings', array(
            'dataProvider' => $dataProvider,
        ));
    }
    public function actionLoadrmsettingsform($name) {
        if (isset($name)) {
            $model = Settings::model()->findBYPk($name);
            $this->renderPartial('_rm_settings_form', array('model' => $model));
        } else {
        	echo "Problem with Settings Keyword";
        }
    }
    
    public function actionSavermsettings() {
        if (isset($_REQUEST['formdata'])) {
            $params = array();
            parse_str($_REQUEST['formdata'], $params);

            $var_name = $params['Settings']['varName'];
            $t_var_name = $params['Settings']['varName'];

            $model = Settings::model()->findByPk($var_name);
			$tempAr = array();
			$setAr  = $params['Settings'];

			if(isset($params['Settings']['CurFeeder']) && isset($params['Settings']['ConstFeeder'])){
				if($params['Settings']['varValue'] == 0){
					$params['Settings']['varValue'] = str_replace($params['Settings']['CurFeeder'].";","",$params['Settings']['ConstFeeder']);
					$params['Settings']['varValue'] = str_replace($params['Settings']['CurFeeder'],"",$params['Settings']['varValue']);
					$params['Settings']['varValue'] = $params['Settings']['CurFeeder'].";" . $params['Settings']['varValue'];					
				}else{
					$params['Settings']['varValue'] = str_replace($params['Settings']['CurFeeder'].";","",$params['Settings']['ConstFeeder']);
				}
				$tempAr = array("varName"=>$setAr['varName'],"varKey"=>$setAr['varKey'],"varValue"=>$setAr['varValue'], "CurFeeder"=>$setAr['CurFeeder'], "ConstFeeder"=>$setAr['ConstFeeder']);
				
			}else {
				
				$tempAr = array("varName"=>$setAr['varName'],"varKey"=>$setAr['varKey'],"varValue"=>$setAr['varValue']);
			}
			
			$this->updateConfigLog($tempAr,"rm_settings", "varKey", $setAr['varKey']);			

            $model->attributes = $var_name = $params['Settings'];
            $model->save(false);

			if($t_var_name == "MASTER_CONTROL_MODE" && (!$setAr['varValue'])){
				//Switch Off Auto Mode
				$tempAr = array("varName"=>"AUTOMODE","varKey"=>"Automatic","varValue"=>0);
				$this->updateConfigLog($tempAr,"rm_settings", "varKey", "Automatic");			
				$query = "UPDATE rm_settings SET varValue='0' WHERE varName='AUTOMODE' ";
				$tcommand = Yii::app()->db->createCommand($query);
				$tresult  = $tcommand->query();
				
				//Switch Off Semi-Auto Mode
				$tempAr = array("varName"=>"AUTO_TEST","varKey"=>"AUTO_TEST","varValue"=>0);
				$this->updateConfigLog($tempAr,"rm_settings", "varKey", "AUTO_TEST");			
				$query = "UPDATE rm_settings SET varValue='0' WHERE varName='AUTO_TEST' ";
				$tcommand = Yii::app()->db->createCommand($query);
				$tresult  = $tcommand->query();			
			}
            
            //$message = array('message'=>"Updated Successfully ",'error_code'=>0);
            
            echo json_encode($message);
        }else {
            
               $message = array('message'=>"Updation Failed ",'error_code'=>1);
        }
    }
    
    public function actionActiveProfile($pid, $pname) {

        $this->activateProfile($pid, $pname);
    }
    
    public function actionGeterrormessages($logid) {
        if(isset($logid)){
            $errorMsgModel = LogMessages::model()->findAll('msg_log_run_id = :logid',array(':logid'=>$logid));
            $this->renderPartial('errormessages',array('model'=>$errorMsgModel));
        }else{
	    	echo "ERROR: No data available for this logid: $logid ";
        }
    }

    protected function activateProfile($product_id, $pname) {

        $this->serialiseProfile($product_id, $pname);
        $profileLogModel = ProfileLog::model()->findByPk($product_id);
        $profileModel = ProductProfile::model()->findByPk(1);

        $productName = $profileLogModel->product_name;
        $sources = unserialize($profileLogModel->source);
        $elements = unserialize($profileLogModel->elements);
        $setpoints = unserialize($profileLogModel->setpoints);

        SetPoints::model()->deleteAll();
        Source::model()->deleteAll();
        ElementComposition::model()->deleteAll();
		$dbName = Yii::app()->params["dbName"];


        $sql = "ALTER TABLE $dbName.rm_source AUTO_INCREMENT=1";
        $connection=Yii::app()->db;   
      
        $command=$connection->createCommand($sql);
        $command->execute();
      
    
        $profileModel->product_name = $productName;
        $profileModel->save(false);

        //Insert Sources

        foreach ($sources as $source) {

            $sourceModel = New Source;

            $sourceModel->attributes = $source;


            //Default Product Id is always one;
            $sourceModel->product_id =1;
           // $sourceModel->source_id = $source['source_id'];                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                

            $result = $sourceModel->save(false);
        }


        //Insert Sources

        foreach ($setpoints as $setpoint) {

            $setPointModel = New SetPoints;

            $setPointModel->attributes = $setpoint;
            //Default Product Id is always one;
            $setPointModel->product_id = 1;

            $setPointModel->save(false);
        }

        foreach ($elements as $element) {

            $elementModel = new ElementComposition;
            $elementModel->attributes = $element;
            $elementModel->save(false);
        }




        $error = array();

        $error['status'] = 0;
        $error['msg'] = 'Profile Switched Successfully';

        echo json_encode($error);
    }

    protected function serialiseProfile($pid, $pname) {

        //Default profile id is always one

        $pid = 1;
        $productProfile = ProductProfile::model()->find('product_id = :product_id', array(':product_id' => $pid));

        $productName = $productProfile->product_name;
        $productId = $productProfile->product_id;
        $sourcList = array();
        $setPointList = array();
        $elementCompasitionList = array();



        $setPointsModel = SetPoints::model()->findAll('product_id = :pid', array(':pid' => $productId));
        foreach ($setPointsModel as $setpoint) {

            $setPoinModel = SetPoints::model()->find('sp_id =:sp_id', array(':sp_id' => $setpoint->sp_id));
            $setPointList[] = $setPoinModel->attributes;
        }


        //Soueces List

        $sourcesModel = Source::model()->findAll('product_id = :pid', array(':pid' => $productId));
        foreach ($sourcesModel as $source) {
            $sourceId = $source->src_id;

            $sourcesModel = Source::model()->find('src_id = :sid', array(':sid' => $source->src_id));
           
            $sourcList[] = $source->attributes;

            $elementCompotision = ElementComposition::model()->findAll('source_id = :sid', array(':sid' => $sourceId));
            foreach ($elementCompotision as $element) {



                $elementCompasitionList[] = $element->attributes;
            }
        }


        $serialisedSource = serialize($sourcList);
        $serialisedelementCompositon = serialize($elementCompasitionList);
        $serialisedSetpoints = serialize($setPointList);

        $productLog = new ProfileLog;
        $userId = Yii::app()->user->id;
        // $productLog->product_id =NULL;
        $productLog->product_name = $productName;	//Copied from Db
        $productLog->profile_name = $pname;		//Sent from Post
        $productLog->source = $serialisedSource;
        $productLog->setpoints = $serialisedSetpoints;
        $productLog->elements = $serialisedelementCompositon;
        $productLog->user_id = $userId;

        $productLog->save(false);
    }

    public function ActionViewProfileLog($pid) {

        $profileLogModel = ProfileLog::model()->findByPk($pid);


        $this->renderPartial(
                '_profile_log_view', array('productLogModel' => $profileLogModel)
        );
    }

    public function actionCheckNameAvailable($pname) {

        $isExists = ProfileLog::model()->exists('product_name = :product_name', array(':product_name' => $pname));


        if ($isExists) {

            echo 0;
        } else {

            echo 1;
        }
    }

    public function actionDeleteProfileLog($pid) {

		if(!isset($pid)){

            $message['error'] = 1;
            $message['message'] = "Operation failed. Profile Id Null";
        }else if($pid == 1){
            $message['error'] = 1;
            $message['message'] = "Operation failed. Base Profile cannot be deleted!";
        }else {
        $result = ProfileLog::model()->deleteByPk($pid);

        $message = array();

        if ($result) {

            $message['error'] = 0;
            $message['message'] = "ProfileLog deleted successfully";
        } else {

            $message['error'] = 1;
            $message['message'] = "Operation failed. ProfileLog could be deleted";
        }

        }
        echo json_encode($message);
    }

	
	public function updateConfigLog($valAr,$inTable, $inkey, $inVal){

		$connection=Yii::app()->db;  
		$dbArray = array();
		$outPutAr = "";
		//print_r($valAr);exit();
        $SelQuery = "SELECT * FROM $inTable WHERE $inkey = '{$inVal}' LIMIT 1";
		
        $command = $connection->createCommand($SelQuery);
		$result = $command->query()->readAll();        
		//echo $SelQuery;echo "<br/>";
		//exit();
        if (($result)) {
            foreach($result as $row){
				$dbArray = $row;
			}//foreach
		}//if
		$resultDiffAr =array_diff($valAr,$dbArray);
		//echo "valAr"; print_r($valAr);
		//echo "dbArray"; print_r($dbArray);
		//echo "resultDiffAr"; print_r($resultDiffAr);
		//echo "<br/>";
		foreach($resultDiffAr as $id=>$val){
			
			if($inTable == "rm_settings"){
				
				if(isset($valAr["CurFeeder"])){
					$id = $valAr["CurFeeder"];
					
					if($dbVal)
						$outPutAr = " $id is being controled by Rawmix now";
					else
						$outPutAr = " $id is a constant Feeder now";
								
					$updateQuery = "UPDATE rm_fdr_delay_counter set fdr_counter=1, fdr_updated='{$curDateTime}' ".
								   "WHERE fdr_name = (SELECT src_type FROM rm_source where src_name='{$id}') ";
					//echo $updateQuery ;
					$command = $connection->createCommand($updateQuery);
					$command->execute();
					
				}else {
					$id = $valAr["varKey"];
					
					$dbVal = $dbArray["varValue"];
					$inpVal = $valAr["varValue"];
					$outPutAr = " $id was changed from $dbVal to $inpVal";
				}
				
				$insQ = "INSERT into rm_config_log values (NULL,'{$inTable}','{$id}','{$dbVal}','{$outPutAr}',now())";
				//echo $insQ . "<br/>";
				$command = $connection->createCommand($insQ);
				$command->execute();
				break;
			}
			else if(isset($dbArray[$id])){

				{
					$dbVal = round($dbArray[$id],3);
					$inpVal = round($valAr[$id],3);
				}
				if($dbVal != $inpVal){

					$outPutAr = " For $inVal $id was changed from $dbVal to $inpVal";
					
					
					$insQ = "INSERT into rm_config_log values (NULL,'{$inTable}','{$id}','{$val}','{$outPutAr}',now())";
					//echo $insQ . "<br/>";
					$command = $connection->createCommand($insQ);
					$command->execute();
				} 
			}
		}
		

		
		return $outPutAr;       
	}

}//class