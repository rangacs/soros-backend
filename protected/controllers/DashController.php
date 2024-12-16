<?php

/**
 * @author Serg.Kosiy <serg.kosiy@gmail.com>
 */
class DashController extends UIDashboardController {

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


        /*
         *  Abhinandan. Element inside 'size' array, 
         *           determine how the picture (.png)
         *           will be placed inside the wrapper
         *           div.. all this happends when the
         *           user adds a gadget where he/she can
         *           see it.
         *           
         *  set array of portlets
         *    array (@top level) becomes $this->_portlets
         *                                                                    
         */
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
    // LR 03-24-2014
    public function actionSaveRtmaForm() {



        if (isset($_REQUEST['formdata'])):


            $result = array();
            parse_str($_POST['formdata'], $params);
            // $model->attributes = $params['ProductProfile'];


            if (isset($params['ConfigMaster'])) {

                $configMasterModel = new ConfigMaster;
                $configMasterModel->attributes = $params['ConfigMaster'];
                if ($configMasterModel->save(false)) {

                    $result['error'] = 0;

                    $result['rtaMasterID'] = $configMasterModel->rtaMasterID;

                    $result['db_string'] = $configMasterModel->DB_ID_string;

                    echo json_encode($result);
                    //$result['id']=
                }

                //$this->redirect(array('view','id'=>$model->product_id));
            } else if (isset($params['RtaPhysicalConfig'])) {

                $rtaPhysicalConfigModel = new RtaPhysicalConfig;
                $rtaPhysicalConfigModel->attributes = $params['RtaPhysicalConfig'];
                if ($rtaPhysicalConfigModel->save(false)) {

                    echo json_encode($result);
                }
            } else if (isset($params['RtaDbConfig'])) {

                $rtaDbConfigModel = new RtaDbConfig;
                $rtaDbConfigModel->attributes = $params['RtaDbConfig'];
                if ($rtaDbConfigModel->save(false))
                    echo json_encode($result);
            }else if (isset($params['RtaDerivedConfig'])) {

                $rtaDerivedConfigModel = new RtaDerivedConfig;
                $rtaDerivedConfigModel->attributes = $params['RtaDerivedConfig'];
                if ($rtaDerivedConfigModel->save(false))
                    echo json_encode($result);
            }else if (isset($params['RtaAveragedConfig'])) {

                $rtaAveragedConfigModel = new RtaAveragedConfig;
                $rtaAveragedConfigModel->attributes = $params['RtaAveragedConfig'];
                if ($rtaAveragedConfigModel->save(false))
                    echo json_encode($result);
            }else if (isset($params['RtaAverageGroupids'])) {

                $rtaAverageGroupidsModel = new RtaAverageGroupids;
                $rtaAverageGroupidsModel->attributes = $params['RtaAverageGroupids'];
                if ($rtaAverageGroupidsModel->save(false))
                    echo json_encode($result);
            }


        //



        endif;
    }

    public function actionPlot() {


        return $this->render('plot');
    }
    public function actionSetDefaultAvgRange() {

        $avgRange = $_REQUEST['default_avg_range'];


        $_SESSION['default_avg_range'] = $avgRange;
    }

}
