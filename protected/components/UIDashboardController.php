<?php

class UIDashboardController extends UIController {

    public $layout = '//layouts/column1';
    public $defaultAction = 'dash';
    private $_errorPortlets = array(); //Abhinandan. Feb27th added..
    private $_portlets = array(); // id, title, content
    private $_tableName;
    private $_layID;
    private $_userIdFieldName;
    private $_userPrefFieldName;  //Abhinandan. The db column holding the serialized info..
    private $_defaultLayout;      //Abhinandan. Feb8th: Added for specifying which layout record == 1 of the 'default_layout' field..
    private $_defaultLayoutValue = 1; //Abhinandan. Feb8th: Specifies what the defaultLayout value should be. Defaults to 1.
    private $_columns = 3;
    private $_showHeader = true;
    private $_autoSave = false;
    private $editable = false; // user can save/reset preference
    private $url;              // images url
    private $contentBefore;
    private $contentAfter;
    private $userId;
    private $widgetsPos;      //Abhinandan. March10th..
    //Abhinandan. Jan21st 2013:
    private $_mainDB;
    private $_layoutsTable = 'gadlay_layouts';
    private $_gadgetsDataTable = 'gadlay_gadgets_data';
    private $_elementsTable = 'gadlay_elements';
    private $_chartsTable = 'gadlay_charts';
    private $_tablesTable = 'gadlay_table_elements';

    public function init() {
	parent::init();
		$this->_mainDB = Yii::app()->params['dbName'];
	$defDTime = Yii::app()->params['defaultDateTime'];
//	date_default_timezone_set($defDTime);

	if (isset($_REQUEST['langId'])) {


		Yii::app()->language= $_REQUEST['langId'];
		}

	Yii::app()->language = 'en';
	//Reset Debug Counters=1
	if (isset($_REQUEST['resetDb']) && ($_REQUEST['resetDb'] == 'yes')) {
	    echo "This feature is not supported in Production Version";
		return;
	    $ltime = date("Y-m-d H:i:s");
	    //date("Y-m-d H:i:s");
	    $tableSrc = $_REQUEST['table'];
	    //Abhinandan. Be careful, sometimes wrapping $variable can lead to unexpected fetched result.. other times, not.

	    $query = "SELECT LocalendTime FROM `$tableSrc` WHERE" .
		    " LocalendTime <= '$ltime' ORDER BY `LocalendTime` DESC LIMIT 1";

	    $tcommand = Yii::app()->db->createCommand($query)->queryRow();

	    $current_TimeStamp = $tcommand["LocalendTime"];

	    $daysBehind = round((strtotime($ltime) - strtotime($current_TimeStamp)) / 86400) + 3; //How many minutes

	    if ($daysBehind > 0) {
		$tcommand = Yii::app()->db->createCommand("UPDATE `$tableSrc` set LocalstartTime = DATE_ADD(LocalstartTime, INTERVAL $daysBehind DAY) WHERE 1");
		$tcommand->execute();
		$tcommand = Yii::app()->db->createCommand("UPDATE `$tableSrc` set LocalendTime 	= DATE_ADD(LocalendTime, INTERVAL $daysBehind DAY) WHERE 1");
		$tcommand->execute();
		$tcommand = Yii::app()->db->createCommand("UPDATE `$tableSrc` set GMTendTime 	= DATE_ADD(GMTendTime , INTERVAL $daysBehind DAY) WHERE 1");
		$tcommand->execute();
	    } else {
		$tcommand = Yii::app()->db->createCommand("UPDATE `$tableSrc` set LocalstartTime = DATE_SUB(LocalstartTime, INTERVAL $daysBehind DAY) WHERE 1");
		$tcommand->execute();
		$tcommand = Yii::app()->db->createCommand("UPDATE `$tableSrc` set LocalendTime 	= DATE_SUB(LocalendTime, INTERVAL $daysBehind DAY) WHERE 1");
		$tcommand->execute();
		$tcommand = Yii::app()->db->createCommand("UPDATE `$tableSrc` set GMTendTime 	= DATE_SBU(GMTendTime , INTERVAL $daysBehind DAY) WHERE 1");
		$tcommand->execute();
	    }
	    echo "DATABASE UPDATED SUCCESSFULLY";
	    exit();
	}

	if (!empty($_GET['layout']))
	    $this->layout = $_GET['layout'];


	$cSettings = UISettings::getConfig(Yii::app()->user->getId());
	if (isset($cSettings['screen_resolution'])) {
	    Yii::app()->params['screen_resolution'] = $cSettings['screen_resolution'];
	}
	if (isset($cSettings['uicolor'])) {
	    Yii::app()->params['uicolor'] = $cSettings['uicolor'];
	}
	if (isset($cSettings['bodyClass'])) {
	    Yii::app()->params['bodyClass'] = $cSettings['bodyClass'];
	}

	if (isset($_GET['langId']) && in_array($_GET['langId'], Yii::app()->params['langArray'])) {
	    Yii::app()->language = $_GET['langId'];
	} else if (isset($cSettings['language'])) {
	    Yii::app()->language = $cSettings['language'];
	} else {
	    Yii::app()->language = 'en';
	}
    }

    public function attributeLabels() {
	return array(
	);
    }

    /* AB352014 Addition for New Table_Type for elements/Averages Table Type */

    public function actionGetDataTables() {
	if (isset($_POST['table_type'])) {
	    $tType = $_POST['table_type'];

	    $oTables = Yii::app()->db->createCommand("SHOW TABLES FROM $this->_mainDB like '%{$tType}%'");

	    $rs = $oTables->queryAll(); ////

	    $output_arr = array();
	    foreach ($rs as $k1 => $v1) {
		foreach ($v1 as $k2 => $v2) {
		    $output_arr[] = $v2;
		}
	    } {
		echo CJSON::encode($output_arr);
	    }
	}
    }

    /**
     * Set params needed for store user's preference to DB
     * @param <string> $tableName the table name
     * @param <string> $userIdField the user's ID field name
     * @param <string> $userPrefField the user's preference field name
     */
    public function setTableParams($tableName, $userIdField, $userPrefField, $defaultLayout) {
	$this->_tableName = $tableName;
	$this->_userIdFieldName = $userIdField;
	$this->_userPrefFieldName = $userPrefField;
	$this->_defaultLayout = $defaultLayout;
	$this->editable = true;
    }

    /**
     * Array of portlets definition.
     * The portlet is array
     *      id - integer
     *      title - string
     *      content - string
     * @param <array> $portlets
     */
    public function setPortlets($portlets) {
	//Controller::fb('UIDashboardController, this->_columns is ' . $this->_columns);
	if (count($portlets) < $this->_columns)
	    $this->_columns = count($portlets);  // Abhinandan.  count($portlets) = 1, so let it be that $this->_columns = 1
	$this->_portlets = $portlets;            // And stuff our global array..
    }

    /**
     * Method  setErrorPortlet()
     *  @param  array  The portlet we wish to set as the error portlet
     *
     */
    public function setErrorPortlets($errorPortlets) {
	if (count($errorPortlets) < $this->_columns)
	    $this->_columns = count($errorPortlets);  // Abhinandan.  count($portlets) = 1, so let it be that $this->_columns = 1
	$this->_errorPortlets = $errorPortlets;            // And stuff our global array..
    }

    /**
     * Sets dashboard columns count
     * @param <int> $columnsCount
     */
    public function setColumns($columnsCount) {
	$this->_columns = ($columnsCount < count($this->_columns)) ?
		count($this->_portlets) : $columnsCount;
    }

    /**
     * Autosave dashboard state via AJAX
     * @param <bool> $flagAutosave
     */
    public function setAutosave($flagAutosave) {
	$this->_autoSave = $flagAutosave;
    }

    /**
     * Show or hide dashboard header
     * @param <bool> $flagShow
     */
    public function setShowHeaders($flagShow = true) {
	$this->_showHeader = $flagShow;
    }

    /**
     * Set page's content before dashboard
     * @param <string> $content
     */
    public function setContentBefore($content = null) {
	$this->contentBefore = $content;
    }

    /**
     * Set page's content after dashboard
     * @param <string> $content
     */
    public function setContentAfter($content = null) {
	$this->contentAfter = $content;
    }

    /* LayoutHandle() */

    protected function LayoutHandle($layoutContents) {
	//echo CJSON::encode($layoutContents);
	$layout_options = array(
	    '_layoutsTable' => $this->_layoutsTable,
	    'lay_id' => 'NOT NULL',
	    'user_id' => $this->uid,
	    'a_href' => 'NULL',
	    'subname' => $layoutContents, //Name of the layout..
	    'gadPlacement' => 'NULL',
	    'last_updated' => 'NULL',
	);

	$origin = __FUNCTION__;

	//Invoke 'HandleProcess()' , then pass back to client..
	if ($output_str = $this->HandleProcess($layout_options, $origin)) {
	    $to_client_arr = array();
	    $to_client_arr['lay_id'] = $output_str;
	    $to_client_arr['user_id'] = $layout_options['user_id'];

	    echo CJSON::encode($to_client_arr);   //@return 'lay_id' & 'user_id' to client ajax receive..
	}
    }

    /* GadgetHandle() */

    protected function GadgetHandle($fields_arr) {
	//echo CJSON::encode($gadgetName. ', ' .$userId. ', ' .$gadgetType. ', ' . $gadget_detector_source . ', ' . $gadget_data_source . ', ' .$gadgetSize);

	if ($fields_arr['gadgetType'] == 'Alerts') {
	    //TESTING..
	    //echo CJSON::encode( $fields_arr['gadgetSize']. ', ' .$fields_arr['gadget_data_source'] );

	    $gadget_options = array(
		'_gadgetsDataTable' => $this->_gadgetsDataTable,
		'gadget_data_id' => $fields_arr['gadget_data_id'],
		'gadgetType' => $fields_arr['gadgetType'],
		'lay_id' => $fields_arr['layoutId'],
		'gadget_name' => $fields_arr['gadgetName'],
		'gadget_size' => $fields_arr['gadgetSize'],
		'last_updated' => 'NULL',
		'data_source' => $fields_arr['gadget_data_source'],
		'detector_source' => $fields_arr['gadget_detector_source'],
	    );
	} elseif ($fields_arr['gadgetType'] == 'Charts') {
	    $gadget_options = array(
		'_gadgetsDataTable' => $this->_gadgetsDataTable,
		'gadget_data_id' => $fields_arr['gadget_data_id'],
		'gadgetType' => $fields_arr['gadgetType'],
		'lay_id' => $fields_arr['layoutId'],
		'gadget_name' => $fields_arr['gadgetName'],
		'gadget_size' => $fields_arr['gadgetSize'],
		'last_updated' => 'NULL',
		'data_source' => $fields_arr['gadget_data_source'],
		'detector_source' => $fields_arr['gadget_detector_source'],
		'group_style' => $fields_arr['gadget_group_style'],
		'display_style' => $fields_arr['gadget_chart_style'],
	    );
	} elseif ($fields_arr['gadgetType'] == 'System_Messages') {   //'System_Messages'(server-side) AKA 'Table'(client-side)..
	    $gadget_options = array(
		'_gadgetsDataTable' => $this->_gadgetsDataTable,
		'gadget_data_id' => $fields_arr['gadget_data_id'],
		'gadgetType' => $fields_arr['gadgetType'],
		'lay_id' => $fields_arr['layoutId'],
		'gadget_name' => $fields_arr['gadgetName'],
		'gadget_size' => $fields_arr['gadgetSize'], //Abhinandan. Statically placed here for 'Table'... ENUM fields in db, so this value is really irrelevant to anything in the future..
		'last_updated' => 'NULL',
		'data_source' => $fields_arr['gadget_data_source'],
		'detector_source' => $fields_arr['gadget_detector_source'],
	    );
	} elseif ($fields_arr['gadgetType'] == 'IdiotLights') {
	    //TESTING..
	    //echo CJSON::encode( $fields_arr['gadgetSize']. ', ' .$fields_arr['gadget_data_source'] );

	    $gadget_options = array(
		'_gadgetsDataTable' => $this->_gadgetsDataTable,
		'gadget_data_id' => $fields_arr['gadget_data_id'],
		'gadgetType' => $fields_arr['gadgetType'],
		'lay_id' => $fields_arr['layoutId'],
		'gadget_name' => $fields_arr['gadgetName'],
		'gadget_size' => $fields_arr['gadgetSize'],
		'last_updated' => 'NULL',
		'data_source' => $fields_arr['gadget_data_source'],
		'detector_source' => $fields_arr['gadget_detector_source'],
	    );
	} elseif ($fields_arr['gadgetType'] == 'Live_Status') {
	    //TESTING..
	    //echo CJSON::encode( $fields_arr['gadgetSize']. ', ' .$fields_arr['gadget_data_source'] );

	    $gadget_options = array(
		'_gadgetsDataTable' => $this->_gadgetsDataTable,
		'gadget_data_id' => $fields_arr['gadget_data_id'],
		'gadgetType' => $fields_arr['gadgetType'],
		'lay_id' => $fields_arr['layoutId'],
		'gadget_name' => $fields_arr['gadgetName'],
		'gadget_size' => $fields_arr['gadgetSize'],
		'last_updated' => 'NULL',
		'data_source' => $fields_arr['gadget_data_source'],
		'detector_source' => $fields_arr['gadget_detector_source'],
	    );
	}


	//echo CJSON::encode($gadget_options['detector_source']);

	$origin = __FUNCTION__;
	if ($output_str = $this->HandleProcess($gadget_options, $origin)) { //01/22/2013
	    echo CJSON::encode($output_str);  //Abhinandan. $output_str represents the 'gadget_data_id' of the 'gadlay_gadgets_data' table..
	}
    }

//end GadgetHandle()..


    /* ElementHandle() */

    protected function ElementHandle($element_type, $gadget_data_id, $order_location, $element_colorset, $show_value, $element_setpoint) {
	$gadget_options = array(
	    '_elementsTable' => $this->_elementsTable,
	    'element_type' => $element_type,
	    'gadget_data_id' => $gadget_data_id,
	    'order_location' => $order_location,
	    'element_colorset' => $element_colorset,
	    'show_value' => $show_value,
	    'element_setpoint' => $element_setpoint
	);

	$origin = __FUNCTION__;
	if ($output_str = $this->HandleProcess($gadget_options, $origin)) {  //Abhinandan. Jan27th 2013..
	    echo CJSON::encode($output_str);  //Abhinandan. $output_str represents the 'gadget_data_id' of the 'gadlay_gadgets_data' table..
	}
    }

    /* LargeElementHandle() */

    protected function LargeElementHandle($fields_arr) {

	if (($val = $fields_arr['large_data_type']['gadgetType']) !== FALSE) {
	    if ($val == 'IdiotLights') {
		foreach ($fields_arr as $k1 => $v1) {
		    foreach ($v1 as $k2 => $v2) {
			if (is_numeric($k2)) {    //We only want numeric indexes passed along..
			    $gadget_options = array(
				'_elementsTable' => $this->_elementsTable,
				'element_type' => $v2['element_type'],
				'gadget_data_id' => $v2['gadget_data_id'],
				'order_location' => $v2['order_location'],
				'element_colorset' => $v2['element_colorset'],
				'show_value' => $v2['show_value'],
				'element_setpoint' => $v2['element_setpoint']
			    );

			    $origin = __FUNCTION__;
			    $this->HandleProcess($gadget_options, $origin);
			}
		    } //end inner foreach..
		} //end top foreach..
		//echo CJSON::encode( 'back from the dark-side' );
	    } elseif ($val == 'LiveStatus') {
		//echo CJSON::encode( $fields_arr );
		foreach ($fields_arr as $k1 => $v1) {
		    foreach ($v1 as $k2 => $v2) {
			if (is_numeric($k2)) {     //We only want numeric indexes passed along..
			    $gadget_options = array(
				'_elementsTable' => $this->_elementsTable,
				'element_type' => $v2['element_type'],
				'gadget_data_id' => $v2['gadget_data_id'],
				'order_location' => $v2['order_location'],
				'element_colorset' => $v2['element_colorset'],
				'show_value' => $v2['show_value'],
				'element_setpoint' => $v2['element_setpoint']
			    );

			    $origin = __FUNCTION__;
			    $this->HandleProcess($gadget_options, $origin);
			}
		    } //end inner foreach..
		} //end top foreach..
		//echo CJSON::encode( 'back from the dark-side' );
	    } elseif ($val == 'Alerts') {
		//echo CJSON::encode( $fields_arr );
		foreach ($fields_arr as $k1 => $v1) {
		    foreach ($v1 as $k2 => $v2) {
			if (is_numeric($k2)) {     //We only want numeric indexes passed along..
			    $gadget_options = array(
				'_elementsTable' => $this->_elementsTable,
				'element_type' => $v2['element_type'],
				'gadget_data_id' => $v2['gadget_data_id'],
				'order_location' => $v2['order_location'],
				'element_colorset' => $v2['element_colorset'],
				'show_value' => $v2['show_value'],
				'element_setpoint' => $v2['element_setpoint']
			    );

			    $origin = __FUNCTION__;
			    $this->HandleProcess($gadget_options, $origin);
			}
		    } //end inner foreach..
		} //end top foreach..
		//echo CJSON::encode( 'back from the dark-side' );
	    }
	}
    }

//end LargeElementHandle()..


    /* ChartHandle() */

    protected function ChartHandle($chart_arr) {
	$gadget_options = array(
	    '_chartsTable' => $this->_chartsTable,
	    'gadget_data_id' => $chart_arr['gadgetDataId'],
	    'chart_type' => $chart_arr['chart_type'],
	    'x_axis_item' => $chart_arr['x_axis_item'],
	    'y_axis_item' => $chart_arr['y_axis_item'],
	    'choose_items' => $chart_arr['choose_items'],
	    'saved_chart_options' => 'NULL',
	);

	$origin = __FUNCTION__;
	if ($output_str = $this->HandleProcess($gadget_options, $origin)) {  //Abhinandan. Jan29th 2013..
	    echo CJSON::encode($output_str);  //Abhinandan. $output_str represents the 'gadget_data_id' of the 'gadlay_gadgets_data' table..
	}
    }

    /* TableHandle()
     *
     *  NOTE: Assumptions in this order:
     *   0 => VALUE for gadget_data_id
     *   1 => VALUE for saved_table_options
     */

    protected function TableHandle() {
	$temp_arr = func_get_args();
	$gadget_options = array();

	for ($i = 0; $i < count($temp_arr); ++$i) {
	    if ($i == 0) {
		$gadget_options['gadget_data_id'] = $temp_arr[$i];
	    }
	    if ($i == 1) {
		$gadget_options['saved_table_options'] = $temp_arr[$i];
	    }
	}
	$gadget_options['_tablesTable'] = $this->_tablesTable;


	$origin = __FUNCTION__;

	if ($output_str = $this->HandleProcess($gadget_options, $origin)) {  //Abhinandan. Jan31st 2013..
	    echo CJSON::encode($output_str);  //Abhinandan. $output_str represents the 'gadget_data_id' of the 'gadlay_gadgets_data' table..
	}
    }

//end TableHandle()..

    /*

     * Lakshmi ranganath
     * Purpose: to load existing gadget information
     */

    public function actionGetGadgetDetail($id) {
	$gadgetModel = GadgetsData::model()->findBySql("SELECT * FROM `gadlay_gadgets_data` where gadget_data_id='{$id}'");
	$gadget_options = array(
	    'gadget_data_id' => $gadgetModel->gadget_data_id,
	    'gadget_type' => $gadgetModel->gadget_type,
	    'lay_id' => $gadgetModel->lay_id,
	    'widgetsPos' => $gadgetModel->widgetsPos,
	    'gadget_name' => $gadgetModel->gadget_name,
	    'gadget_size' => $gadgetModel->gadget_size,
	    'last_updated' => $gadgetModel->last_updated,
	    'data_source' => $gadgetModel->data_source,
	    'detector_source' => $gadgetModel->detector_source,
	    'group_style' => $gadgetModel->group_style,
	    'display_style' => $gadgetModel->display_style
	);
	$gadget = array();
	$gadget['gadget_options'] = $gadget_options;
	$gadget['gadget_elements'] = array();

	echo json_encode($gadget);
//       $gedgetDetail = array();
	//  var_dump($gadgetModel);
    }

    /*
     *  Abhinandan.
     *   Purpose: Find the relevant config option value (inside the serialized array)..
     *
     *   Ref: Table = gadlay_config_options
     *   @param string keyword : Exists as value from keyword field
     *   @param string key     : The key to reference within the serialized array (inside db)..
     *
     *   How to Invoke:    $this->lookupConfigOption('data_source', $gadget_data_source)
     *    @return ie 'ds_2' || 'dt_2' ...
     */

    private function lookupConfigOption($keyword, $key) {
	$config_table = 'gadlay_config_options';

	$pref = Yii::app()->db->createCommand("SELECT
							  value
							FROM $config_table
							WHERE keyword = '$keyword' "
		)->queryRow();
	$config_arr = ($pref['value'] === null) ? '' : unserialize(stripslashes($pref['value']));

	foreach ($config_arr as $k1 => $v1) {
	    if ($k1 == $key) {
		return $v1;
	    }
	}
    }

    /* HandleProcess() */

    private function HandleProcess($handle_options, $origin) {
	if (!is_null($handle_options) && !is_null($origin)) {
	    if ($origin == "LayoutHandle") {
		$table = $handle_options['_layoutsTable'];  // gadlay_layouts
		$lay_id = $handle_options['lay_id'];         // NOT NULL
		$user_id = $handle_options['user_id'];        // 7
		$a_href = $handle_options['a_href'];         // NULL
		$subname = $handle_options['subname'];        // Layout D
		$gadPlacement = $handle_options['gadPlacement'];  //NULL
		$last_updated = $handle_options['last_updated'];  //NULL
		//Abhinandan. Be careful, sometimes wrapping $variable can lead to unexpected fetched result.. other times, not.
		$command = Yii::app()->db->createCommand("INSERT INTO
							     $table (`lay_id`,`user_id`,`a_href`,`subname`,`gadPlacement`,`last_updated`)
						VALUES ($lay_id,$user_id,$a_href,'$subname',$gadPlacement,$last_updated)"
		);

		//echo "INSERT INTO $table (`lay_id`,`user_id`,`a_href`,`subname`,`gadPlacement`,`last_updated`)VALUES ($lay_id,$user_id,$a_href,'$subname',$gadPlacement,$last_updated)";

		$command->execute();
		return Yii::app()->db->getLastInsertId();   //Refers to lay_id.. (our auto increment field)..
	    } //end if origin == "LayoutHandle"
	    elseif ($origin == "GadgetHandle") {
		$table = $handle_options['_gadgetsDataTable'];   // gadlay_gadgets_data
		$gadget_data_id = $handle_options['gadget_data_id'];      // 'NOT NULL'
		$gadgetType = $handle_options['gadgetType'];          // Alerts
		$lay_id = $handle_options['lay_id'];              // 2
		$gadget_name = $handle_options['gadget_name'];        // Layout A
		$gadget_size = $handle_options['gadget_size'];        // 'small'
		$last_updated = $handle_options['last_updated'];       // 'NULL'
		$data_source = $handle_options['data_source'];        // 'ds_2'
		$detector_source = $handle_options['detector_source'];    // 'dt_2'

		if ($gadgetType == "Charts") {
		    $group_style = $handle_options['group_style'];        // 'ds_2'
		    $display_style = $handle_options['display_style'];    // 'dt_2'
		} else {
		    $group_style = '';        // 'ds_2'
		    $display_style = '';      // 'dt_2'
		}

		$gadgetId = $handle_options['gadget_data_id'];
		//On update
		if (!empty($gadgetId)) {
		    //On update
		    $gadgetModel = GadgetsData::model()->findByPk($gadgetId);
		    $gadgetModel->attributes = $handle_options;
		    $gadgetModel->save(false);
		    return $gadgetModel->gadget_data_id;
		} else {
		    // On save
		    ///Abhinandan. Be careful, sometimes wrapping $variable can lead to unexpected fetched result.. other times, not.

		    $sql = "INSERT INTO $table (`gadget_data_id`,`gadget_type`,`lay_id`,`gadget_name`,`gadget_size`,`last_updated`,`data_source`,`detector_source`,`group_style`,`display_style`)".
                            "VALUES ('{$gadget_data_id}', '$gadgetType', $lay_id, '$gadget_name', '$gadget_size', $last_updated, '$data_source', '$detector_source','$group_style','$display_style' )";
		    $command = Yii::app()->db->createCommand($sql);
		    $command->execute();
		    return Yii::app()->db->getLastInsertId();  //Abhinandan. Jan22nd 2013.
		}

		/* echo "<br/>INSERT INTO
		  $table (`gadget_data_id`,`gadget_type`,`lay_id`,`gadget_name`,`gadget_size`,`last_updated`,`data_source`,`detector_source`)
		  VALUES ($gadget_data_id, '$gadgetType', $lay_id, '$gadget_name', '$gadget_size', $last_updated, '$data_source', '$detector_source' )";
		 */
	    } //end if origin == "GadgetHandle"
	    elseif (($origin == "ElementHandle") || ($origin == "LargeElementHandle")) {
		//return 1;     //Testing..


		$table = $handle_options['_elementsTable'];    // gadlay_elements
		$element_type = $handle_options['element_type'];      // Element 1
		$gadget_data_id = $handle_options['gadget_data_id'];    // 307
		$order_location = $handle_options['order_location'];    // 1
		$element_colorset = $handle_options['element_colorset'];  // 3set
		$show_value = $handle_options['show_value'];        // TRUE
		$element_setpoint = $handle_options['element_setpoint'];   // "{\"green\":\"1\",\"orange\":\"2\",\"red\":\"3\",\"blue\":\"4\",\"gray\":\"5\"}"
		//Convert TRUE to 1, and FALSE to 0..
		if ($show_value == "TRUE") {
		    $show_value = 1;
		} else if ($show_value == "FALSE") {
		    $show_value = 0;
		}

		//Re-format the jsonified string to a PHP formatted (serialized) string..
		$_parsed_element_setpoint = json_decode($element_setpoint);
		if (is_object($_parsed_element_setpoint)) {
		    $_arr_element_setpoint = array();


		    foreach ($_parsed_element_setpoint as $k1 => $v1) {
			if ($k1 == 'green') {
			    $_arr_element_setpoint['green'] = (int) $v1;
			} elseif ($k1 == 'orange') {
			    $_arr_element_setpoint['orange'] = (int) $v1;
			} elseif ($k1 == 'red') {
			    $_arr_element_setpoint['red'] = (int) $v1;
			} elseif ($k1 == 'blue') {
			    $_arr_element_setpoint['blue'] = (int) $v1;
			} elseif ($k1 == 'gray') {
			    $_arr_element_setpoint['gray'] = (int) $v1;
			}
		    }//end foreach $_parsed_element_setpoint..

		    $element_setpoint = serialize($_arr_element_setpoint);
		} // end   if is_object($_parsed_element_setpoint) ...
		//Abhinandan. Be careful, sometimes wrapping $variable can lead to unexpected fetched result.. other times, not.
		//Working, bug fixed!..
		$command = Yii::app()->db->createCommand("INSERT INTO
							      $table (`element_id`,`element_type`,`gadget_data_id`,`order_location`,`element_colorset`,`element_setpoint`,`show_value`)
						 VALUES ('NOT NULL', '" . $element_type . "', '" . $gadget_data_id . "', '" . $order_location . "', '" . $element_colorset . "', '" . $element_setpoint . "', '" . $show_value . "' )"
		);


		$command->execute();    //Commenting out for now...
		//return Yii::app()->db->getLastInsertId();  //Abhinandan. Jan27th 2013.
	    } elseif ($origin == "ChartHandle") {
		$table = $handle_options['_chartsTable'];        // gadlay_charts
		$gadget_data_id = $handle_options['gadget_data_id'];      // 353
		$chart_type = $handle_options['chart_type'];          // candle
		$x_axis_item = $handle_options['x_axis_item'];         // aluminum
		$y_axis_item = $handle_options['y_axis_item'];         // single
		$choose_items = $handle_options['choose_items'];        // copper
		$saved_chart_options = $handle_options['saved_chart_options']; //NULL

		$command = Yii::app()->db->createCommand("INSERT INTO
							      $table (`gadget_data_id`,`chart_type`,`x_axis_item`,`y_axis_item`,`choose_items`,`saved_chart_options`)
						 VALUES ($gadget_data_id, '$chart_type', '$x_axis_item', '$y_axis_item', '$choose_items', $saved_chart_options )"
		);

		$command->execute();
		return Yii::app()->db->getLastInsertId();  //Abhinandan. Jan22nd 2013.
	    } elseif ($origin == "TableHandle") {
		$table = $handle_options['_tablesTable'];    //gadlay_table_elements
		$gadget_data_id = $handle_options['gadget_data_id'];  // 381
		$saved_table_options = $handle_options['saved_table_options'];  // '{"row2":{"tr_id":"row2","td_content":"Element 1"},"row3":{"tr_id":"row3","td_content":"Element 2"}}'
		//Re-format the jsonified string to a PHP formatted (serialized) string..
		$parsed_obj = json_decode($saved_table_options);
		if (is_object($parsed_obj)) {
		    $temp_arr = array();

		    foreach ($parsed_obj as $k1 => $v1) {
			if (is_object($v1)) {
			    foreach ($v1 as $k2 => $v2) {
				$temp_arr[$k1][$k2] = $v2;
			    }
			}
		    }//end top foreach..

		    $temp_str = serialize($temp_arr);
		    $saved_table_options = $temp_str;  //$saved_table_options is now changed to PHP serialized string..
		} // end   if is_object($_parsed_element_setpoint) ...
		//Insert record into db..
		$command = Yii::app()->db->createCommand("INSERT INTO
							      $table (`table_id`, `gadget_data_id`, `saved_table_options`)
						 VALUES ('NOT NULL', $gadget_data_id, '$saved_table_options' )"
		);

		$command->execute();
		return Yii::app()->db->getLastInsertId();  //Abhinandan. Jan31st 2013.
	    } //end    elseif($origin == "TableHandle") ..
	}//end if !is_null layout_options And !is_null origin..
    }

//end HandleProcess()..

    public function actionCheckName() {
	if (isset($_POST['layoutContents'])) {
	    $laName = $_POST['layoutContents'];
	    $select_current_layout = Yii::app()->db->createCommand("SELECT
								     lay_id
								     FROM gadlay_layouts
								     WHERE subname = '$laName' ")->queryRow();

	    if (isset($select_current_layout['lay_id'])) {
		$current_layout = $select_current_layout['lay_id'];
		echo "Layout Name already exists!";
	    } else {
		echo "Success";
	    }
	}
    }

    /*
     *  Method:  actionTagging()
     *
     */

    public function actionTagging() {

	$tagDataProvider = new CActiveDataProvider('RtaTagIndexQueued');
	$tmodel = new RtaTagIndexQueued('search');
	$tmodel->unsetAttributes();  // clear any default values


	$ctagDataProvider = new CActiveDataProvider('RtaTagIndexCompleted');
	$ctmodel = new RtaTagIndexCompleted('search');
	$ctmodel->unsetAttributes();  // clear any default values

	$connection = Yii::app()->db;   // assuming you have configured a "db" connection
	$command = $connection->createCommand("SELECT tagGroupID,tagGroupName from tag_group WHERE 1");
	$dataRList = $command->query();
	$rowsList = $dataRList->readAll();

	$connection2 = Yii::app()->db;   // assuming you have configured a "db" connection
	$command2 = $connection2->createCommand("SELECT rtaMasterID,DB_ID_string as data_src FROM rta_config_master WHERE 1");
	$dataRList2 = $command2->query();
	$rowsList2 = $dataRList2->readAll();

	$model = new TagGroup('search');
	$model->unsetAttributes();  // clear any default values

	$dataProvider = new CActiveDataProvider('TagGroup');

	$a = $this->fetchLeftMenu();   //July 3rd 2013 commenting out CONSTRUCTION..

	$this->registerScript();      //July 3rd 2013 commenting out CONSTRUCTION..

	$pref = $this->getPreference();       //Abhinandan. Fetch parsed 'title' field from 'dashboard_page' table..

	$uid = $this->uid;
	$layouts = Layouts::model()->findAll(array('condition' => 'user_id=:x', 'params' => array(':x' => $uid)));  //Abhinandan.. Hooks into 'gadlay_layouts' table..

	$this->render('tagging', array(
	    'portlets' => $this->applyUserPref($pref), //Invoke 'applyUserPref()'which is actually array output from the combined logic of arrays: ( $pref array AND $this->_portlets ) ..
	    'columns' => $this->_columns,
	    'model' => $model,
	    'tagmodel' => $tmodel,
	    'ctagmodel' => $ctmodel,
	    'showHeader' => $this->_showHeader,
	    'autoSave' => $this->_autoSave,
	    'editable' => $this->editable,
	    'resetUrl' => $this->createUrl('resetDash'),
	    'url' => Yii::app()->theme->baseUrl . "/css/images",
	    'contentBefore' => $this->contentBefore,
	    'contentAfter' => $this->contentAfter,
	    'left_menu_arr' => $a, //July 3rd 2013 commenting out CONSTRUCTION..
	    'tagsData' => $tagDataProvider,
	    'ctagsData' => $ctagDataProvider,
	    'dataProvider' => $dataProvider,
	    'tagGroupsList' => $rowsList,
	    'analysisList' => $rowsList2,
	));
    }

    /*
     *  Method:  actionCreate()
     *   @var  portlets  Array of portlets as defined in DashController, as specified inside db.
     *
     */

    public function actionCreate() {
	//Controller::fb('actionCreate here');
	//$mwc = new MasterTemplateiii;   //Abhinandan. July29th 2013: Begin logging..

	if (isset($_POST['layoutContents'])) {

	    $this->LayoutHandle($_POST['layoutContents']);
	    return;
	} elseif (isset($_POST['gadgetType'])) {

	    if (($_POST['gadgetType'] == 'Alerts') && isset($_POST['gadgetName']) && isset($_POST['userId']) && isset($_POST['gadget_detector_source']) && isset($_POST['gadget_data_source']) && isset($_POST['gadgetSize']) && isset($_POST['layoutId'])) {

		foreach ($_POST as $k1 => $v1) {
		    $fields_arr[$k1] = $v1;
		}
		$this->GadgetHandle($fields_arr);  ///////May26th LEFT OFF HERE!!!
		return;
	    } elseif (($_POST['gadgetType'] == 'Charts') && isset($_POST['gadgetName']) && isset($_POST['userId']) && isset($_POST['gadget_detector_source']) && isset($_POST['gadget_data_source']) && isset($_POST['layoutId'])) {    //If gadget is Charts..
		$fields_arr = array();
		foreach ($_POST as $k1 => $v1) {
		    $fields_arr[$k1] = $v1;
		}

		$this->GadgetHandle($fields_arr);

		return;
	    } elseif (($_POST['gadgetType'] == 'Tables') && isset($_POST['gadgetName']) && isset($_POST['userId']) && isset($_POST['gadget_detector_source']) && isset($_POST['gadget_data_source']) && isset($_POST['layoutId'])) {
		$fields_arr = array();
		foreach ($_POST as $k1 => $v1) {  //Important: Went ahead and changed 'gadgetType' = 'Table'  TO 'gadgetType' = 'System_Messages' (because in our db, System_Messages is actually Table..)
		    if ($k1 == 'gadgetType') {
			$fields_arr[$k1] = 'System_Messages';
		    } else {
			$fields_arr[$k1] = $v1;
		    }
		}
		$this->GadgetHandle($fields_arr);
		return;
		/*
		  echo CJSON::encode('server, gadgetType is Table');
		  return;
		 */
	    } elseif (($_POST['gadgetType'] == 'IdiotLights') && isset($_POST['gadgetName']) && isset($_POST['userId']) && isset($_POST['gadget_detector_source']) && isset($_POST['gadget_data_source']) && isset($_POST['gadgetSize']) && isset($_POST['layoutId'])) {

		foreach ($_POST as $k1 => $v1) {
		    $fields_arr[$k1] = $v1;
		}
		$this->GadgetHandle($fields_arr);
		return;
	    } elseif (($_POST['gadgetType'] == 'Live_Status') && isset($_POST['gadgetName']) && isset($_POST['userId']) && isset($_POST['gadget_detector_source']) && isset($_POST['gadget_data_source']) && isset($_POST['gadgetSize']) && isset($_POST['layoutId'])) {
		foreach ($_POST as $k1 => $v1) {
		    $fields_arr[$k1] = $v1;
		}
		$this->GadgetHandle($fields_arr);
		return;
	    }
	} elseif (isset($_POST['element_type']) && isset($_POST['gadget_data_id']) && isset($_POST['order_location']) && isset($_POST['element_colorset']) && isset($_POST['show_value']) && isset($_POST['element_setpoint'])) {

	    $this->ElementHandle($_POST['element_type'], $_POST['gadget_data_id'], $_POST['order_location'], $_POST['element_colorset'], $_POST['show_value'], $_POST['element_setpoint']);
	    return;
	} elseif (isset($_POST['gadgetDataId']) && isset($_POST['chart_type']) && isset($_POST['x_axis_item']) && isset($_POST['y_axis_item']) && isset($_POST['choose_items'])) {
	    Controller::fb('golf');
	    $chart_arr = array();
	    foreach ($_POST as $k1 => $v1) {
		$chart_arr[$k1] = $v1;
	    }
	    $this->ChartHandle($chart_arr);
	    return;
	} elseif (isset($_POST['gadget_data_id']) && isset($_POST['saved_table_options'])) {
	    Controller::fb('hulu');
	    $this->TableHandle($_POST['gadget_data_id'], $_POST['saved_table_options']);
	    return;
	    /*
	      echo CJSON::encode('holla');
	      return;
	     */
	} elseif (isset($_REQUEST['error_msg'])) {               //Feb27th..
	    Controller::fb('india');
	    Controller::fb('inside REQUEST error_msg');

	    $a = $this->fetchLeftMenu();

	    $this->registerScript();

	    //$pref = $this->getPreference();

	    $layouts = Layouts::model()->findAll(); // Added by LakshmiR (need to filler layout by userid)

	    $this->render('createDash', array(
		// 'portlets'      => $this->applyUserPref($pref),  //Invoke 'applyUserPref()'which is actually array output from the combined logic of arrays: ( $pref array AND $this->_portlets ) ..
		'columns' => $this->_columns,
		'showHeader' => $this->_showHeader,
		'autoSave' => $this->_autoSave,
		'editable' => $this->editable,
		'resetUrl' => $this->createUrl('resetDash'),
		'url' => Yii::app()->theme->baseUrl . "/css/images",
		'contentBefore' => $this->contentBefore,
		'contentAfter' => $this->contentAfter,
		'left_menu_arr' => $a,
		'layouts' => $layouts,
	    ));
	} elseif (isset($_POST['large_data_type'])) {
	    //Lakshmi Ranganath
	    //On Update: To remove existing elements if any
	    $gadgetDataId = $_POST['large_data_type'][1]['gadget_data_id'];
	    $result = GadlayElements::model()->deleteAll('gadget_data_id = :gid', array(':gid' => $gadgetDataId));
	    $this->LargeElementHandle($_POST);
	    //echo CJSON::encode( $_POST );
	    return;
	    //$fields_arr[$k1] = $v1;
	}

	//Controller::fb('jackalope');
	//First page load, fetch left menu settings from db..

	$a = $this->fetchLeftMenu();   //July 3rd 2013 commenting out CONSTRUCTION..

	$this->registerScript();      //July 3rd 2013 commenting out CONSTRUCTION..
	//Controller::fb('actionCreate invoking getPreference()');
	$pref = $this->getPreference();       //Abhinandan. Fetch parsed 'title' field from 'dashboard_page' table..

	$uid = $this->uid;
	$layouts = Layouts::model()->findAll(array('condition' => 'user_id=:x', 'params' => array(':x' => $uid)));  //Abhinandan.. Hooks into 'gadlay_layouts' table..
	//Controller::fb('about to render the create dash');
	//'portlets' => $this->applyUserPref() ;  @return  $tempArray..
	$this->render('createDash', array(
	    'portlets' => $this->applyUserPref($pref), //Invoke 'applyUserPref()'which is actually array output from the combined logic of arrays: ( $pref array AND $this->_portlets ) ..
	    'columns' => $this->_columns,
	    'showHeader' => $this->_showHeader,
	    'autoSave' => $this->_autoSave,
	    'editable' => $this->editable,
	    'resetUrl' => $this->createUrl('resetDash'),
	    'url' => Yii::app()->theme->baseUrl . "/css/images",
	    'contentBefore' => $this->contentBefore,
	    'contentAfter' => $this->contentAfter,
	    'left_menu_arr' => $a, //July 3rd 2013 commenting out CONSTRUCTION..
	    'layouts' => $layouts,
	));
    }

//end actionCreate()..

    public function actionTheme() {
	//Controller::fb('jackalope');
	//First page load, fetch left menu settings from db..

	$a = $this->fetchLeftMenu();   //July 3rd 2013 commenting out CONSTRUCTION..

	$this->registerScript();      //July 3rd 2013 commenting out CONSTRUCTION..
	//Controller::fb('actionCreate invoking getPreference()');
	$pref = $this->getPreference();       //Abhinandan. Fetch parsed 'title' field from 'dashboard_page' table..

	$uid = $this->uid;
	$layouts = Layouts::model()->findAll(array('condition' => 'user_id=:x', 'params' => array(':x' => $uid)));  //Abhinandan.. Hooks into 'gadlay_layouts' table..
	//
	//Controller::fb('about to render the create dash');
	//'portlets' => $this->applyUserPref() ;  @return  $tempArray..

	$this->render('createTheme', array(
	    'portlets' => $this->applyUserPref($pref), //Invoke 'applyUserPref()'which is actually array output from the combined logic of arrays: ( $pref array AND $this->_portlets ) ..
	    'columns' => $this->_columns,
	    'showHeader' => $this->_showHeader,
	    'autoSave' => $this->_autoSave,
	    'editable' => $this->editable,
	    'resetUrl' => $this->createUrl('resetDash'),
	    'url' => Yii::app()->theme->baseUrl . "/css/images",
	    'contentBefore' => $this->contentBefore,
	    'contentAfter' => $this->contentAfter,
	    'left_menu_arr' => $a, //July 3rd 2013 commenting out CONSTRUCTION..
	    'layouts' => $layouts,
	    'mainDB' => $this->_mainDB //LR 03/19/2014
	));
    }

    //Build up the most recent version of GadPlacement..
    protected function buildCurrentGadPlacement() {
	$uid = $this->uid;
	$build_gadPlacement_query = "SELECT
				   l.lay_id,
				   l.user_id,
				   l.gadPlacement,
				   l.default_layout,
				   g.widgetsPos,
				   g.gadget_data_id,
				   g.gadget_type,
				   g.lay_id,
				   g.gadget_size,
				   m.id,
				   m.small,
				   m.medium,
				   m.large
				  FROM gadlay_layouts as l
				   INNER JOIN gadlay_gadgets_data AS g
				  ON l.lay_id = g.lay_id
				   INNER JOIN gadlay_master_portlets AS m
				  ON g.gadget_type = m.id
				   WHERE l.user_id = $uid
				    AND l.default_layout = 1";


	$command = Yii::app()->db->createCommand($build_gadPlacement_query);
	$rs = $command->queryAll();


	if (count($rs) > 0) {
	    $formal_arr = array();   //The complete array which will eventually be serialized into 'gadlay_layouts.gadPlacement'..
	    $widgetsPos_arr = array();   //Prior to serialization..
	    $widString = "";
	    foreach ($rs as $k1 => $v1) {
		if (is_array($v1)) {
		    if (!is_null($v1['widgetsPos'])) {
			$widgetsPos_var = $v1['widgetsPos'];
			$widgetsPos_arr[0][$widgetsPos_var]['gadget_type'] = $v1['gadget_type'];
			$widgetsPos_arr[0][$widgetsPos_var]['gadget_size'] = $v1[$v1['gadget_size']];
		    }
		}
	    } //end top foreach..
	    //Controller::fb($widgetsPos_arr);

	    ksort($widgetsPos_arr[0]);


	    //Build the 'widString'..
	    foreach ($widgetsPos_arr[0] as $k1 => $v1) {
		if (isset($v1['gadget_type'])) {
		    $widString = $widString . $v1['gadget_type'] . ':';
		}
		if (isset($v1['gadget_size'])) {
		    $widString = $widString . $v1['gadget_size'] . ';';
		}
	    }

	    //Controller::fb($widString);
	    //Now, go back and combine the pieces (original becomes manipulated), to form the formal_arr (what we are seeking prior to insert into db..)
	    $formal_arr = array();
	    $formal_arr['columnsCount'] = "0";
	    foreach ($widgetsPos_arr as $k1 => $v1) {
		foreach ($v1 as $k2 => $v2) {
		    foreach ($v2 as $k3 => $v3) {
			if ($k3 == 'gadget_type') {
			    $formal_arr['widgetsPos'][$k1][$k2] = $v3;
			}
		    }
		}
	    }
	    $formal_arr['widString'] = $widString;

	    //Controller::fb($formal_arr);
	    //Last but not least, go ahead and serialize our array..
	    $formal_str = serialize($formal_arr);

	    unset($widgetsPos_arr); //Garbage collection..

	    $update_query = "UPDATE $this->_layoutsTable
			  SET `gadPlacement` = '$formal_str'
			   WHERE user_id = $uid
			    AND default_layout = 1";
	    $command = Yii::app()->db->createCommand($update_query);

	    //Controller::fb($update_query);

	    $command->execute();
	    //COMMENTING OUT... construction here...
	    /*
	     */
	} //end  if( count($rs) > 0 ) ...
    }

//end  buildCurrentGadPlacement()..



    /* Invoked @ 'createDash::fetchLayoutGadgets() ~ line 2033' */

    public function actionGetgadgets() {

	$lId = $_POST['layoutId'];
	$firstOrmore = $_POST['firstOrmore'];

	if (isset($lId)) {

	    $this->makeLayoutDefault($lId); //Firstly, make the layout we selected the default layout..

	    if ($firstOrmore == 'moreGadgets') { //Go ahead and build up the most current 'gadlay_layouts.gadPlacement' field..
		$this->buildCurrentGadPlacement();
	    }

	    $layout = Layouts::model()->findByPk($lId);    //@return  single record..


	    if (count($layout) > 0) {

		$glist = array();
		$idList = ( ($layout->gadPlacement === null) ? '' : unserialize(stripslashes($layout->gadPlacement)) );
		$idList_json_str = CJSON::encode($idList);
		$idList_json_obj = json_decode($idList_json_str);


		if (is_object($idList_json_obj)) {

		    $i = 0;
		    foreach ($idList_json_obj as $k1 => $v1) {
			if ($k1 == 'widgetsPos') {
			    foreach ($v1[$i] as $k2 => $v2) {
				$glist['layout_info'][$v2] = $this->preGadget($layout->lay_id, $v2);     //Pre-gadget returns an array..
			    }
			}
			if ($k1 == 'widString') {

			    $temp_arr = array();
			    $perm_arr = array();

			    $temp_arr['widString'] = $v1;
			    $widString_arr = explode(';', $temp_arr['widString']);

			    //foreach parse I.
			    foreach ($widString_arr as $k1 => $v1) {
				$no_space_v1 = preg_replace('/\s+/', '', $v1);          //Abhinandan. Reference json_decode_test_A.php
				$widString_val = substr($no_space_v1, strpos($no_space_v1, ':') + 1);
				$wSv_offset = strlen($widString_val) + 1;
				$gadgetType = substr($no_space_v1, 0, -($wSv_offset));

				$perm_arr[][$gadgetType] = $widString_val;      // Abhinandan. June6th:  [] represents the widgetsPos..
				//$perm_arr[$gadgetType] = $widString_val;
			    }


			    //foreach parse II..
			    for ($j = 0; $j < count($perm_arr); ++$j) {            //Abhinandan. June6th construction. done.
				foreach ($perm_arr[$j] as $key1 => $val1) {
				    if (isset($glist['layout_info'][$key1][$j])) {
					$glist['layout_info'][$key1][$j]['gadget_widString_value'] = $val1;
				    }
				}
			    }
			}
		    }
		    $glist['msg'] = 'success';
		    $glist['layoutName'] = $layout->subname;

		    //To be used exclusively as a reference if needed (on the client-side)...
		    $glist['perm_arr'] = $perm_arr;
		} else {
		    $glist['msg'] = 'No gadgets exist for this layout';
		}
	    }

	    echo CJSON::encode($glist);
	    return;
	}
    }

//end actionGetgadgets()..


    /*
     *  Abhinandan.
     *   Grab the gadget record
     *   Goals:
     *      A. If a specific gadget (ie 1x) exists for Layout 1.. done.
     *      B. If multiple gadgets (ie 2x) exist for Layout 1.. done.
     *    @param int  lay_id
     *    @param str  gadget_type  ( originally pieced 'gadlay_layouts.gadPlacement' )
     *      ie 'Live_Status'
     *
     *    @return  array  gadget-related info..
     */

    private function preGadget($lay_id, $gadget_type) {

	$temp_arr = array();
	$gadget = GadgetsData::model()->findAllByAttributes(
		array(), $condition = 'lay_id = :param_lay_id AND gadget_type = :param_gadget_type', $params = array(
	    ':param_lay_id' => $lay_id,
	    ':param_gadget_type' => $gadget_type
		)
	);



	//Goal A & B (@method description)
	if ((count($gadget) > 0)) {      //Goal A & B..
	    $gadget_count = count($gadget);
	    if ($gadget_count == 1) {
		$temp_arr['distinct_gadget'] = TRUE;
	    } elseif ($gadget_count > 1) {
		$temp_arr['distinct_gadget'] = FALSE;
	    }

	    //$temp_arr = $gadget;
	    for ($i = 0; $i < $gadget_count; ++$i) {
		$widgetsPos = $gadget[$i]['widgetsPos'];

		$temp_arr[$widgetsPos]['gadget_data_id'] = $gadget[$i]['gadget_data_id'];     //Abhinandan. June6th: Changed from $temp_arr[$i] TO $temp_arr[$widgetsPos]..
		$temp_arr[$widgetsPos]['gadget_type'] = $gadget[$i]['gadget_type'];
		$temp_arr[$widgetsPos]['gadget_name'] = $gadget[$i]['gadget_name'];
		$temp_arr[$widgetsPos]['lay_id'] = $gadget[$i]['lay_id'];
	    }
	}
	return $temp_arr;
    }

//end  preGadget()..


    /* Invoked @ 'showDash::dashObj.grabDashboardLayout() ~ line 58' */

    public function actionGrabdashboardLayout() {
	$lId = $_POST['lay_id'];
	if (isset($lId)) {
	    if ($this->makeLayoutDefault($lId)) {   //Firstly, make the layout we selected the default layout..
		return true;
	    }
	} else {
	    throw new CHttpException(404, 'The layout id was not found for the requested action.');
	}
    }

    /* Makes the layout default ie "1" in 'gadlay_layouts' table */

    protected function makeLayoutDefault($lay_id) {
	$uid = $this->uid;
	try {

	    $select_current_layout = Yii::app()->db->createCommand("SELECT
							     lay_id
							     FROM gadlay_layouts
							    WHERE user_id = $uid
							     AND default_layout = 1
							   "
		    )->queryRow();

	    $current_layout = $select_current_layout['lay_id'];
	    $update_current = "UPDATE $this->_layoutsTable
			  SET `default_layout` = 0
			  WHERE lay_id = $current_layout
		      ";

	    $update_query = "UPDATE $this->_layoutsTable
			  SET `default_layout` = $this->_defaultLayoutValue
			  WHERE lay_id = $lay_id
		    ";
	    $command_old = Yii::app()->db->createCommand($update_current);
	    $command_old->execute();

	    $command = Yii::app()->db->createCommand($update_query);
	    $command->execute();
	    return true;
	} catch (Exception $e) {
	    echo "Was not able to change the default layout.";
	    return false;
	}
    }

    /* Invoked @ createDash::selectGadget ~ line 3068 */

    public function actionGetDataSourceTables($internal_flag = null) {
	try {

	    $query = "SHOW TABLES FROM $this->_mainDB like '%analysis%'";
	    $command = Yii::app()->db->createCommand($query);
	    $rs = $command->queryAll();

	    $output_arr = array();
	    foreach ($rs as $k1 => $v1) {
		foreach ($v1 as $k2 => $v2) {
		    $output_arr[] = $v2;
		}
	    }

	    if ($internal_flag == null) { //if ajax request..
		echo CJSON::encode($output_arr);
	    } elseif ($internal_flag == 'actionDash') {
		return $output_arr;
	    }

	    return true;
	} catch (Exception $e) {
	    echo "Was not able to fetch the tables from db.";
	    return false;
	}
    }

    public function actionGetDataSourceColumnsFromTable() {
	$table_name = isset($_POST['table_name']) ? $_POST['table_name'] : null;
	try {
	    $pref_arr = $this->lookupColumnPreferences($table_name, $this->uid);
	    echo CJSON::encode($pref_arr);
	} catch (Exception $e) {
	    echo "Was not able to access the column preferences data from the db.";
	    return false;
	}
    }

//  LR 03-20-2014
    public function actionGetDetectorSourceCoulmns($detector = NULL) {
	$dbName = Yii::app()->params["dbName"];
	$sql = "SELECT `COLUMN_NAME`
	FROM `INFORMATION_SCHEMA`.`COLUMNS`
	WHERE `TABLE_SCHEMA`='$dbName'
	AND `TABLE_NAME`='{$detector}'";

	$columns = Yii::app()->db->createCommand($sql)->query()->readAll();

	//var_dump($columns);
	$savedColumn =  $this->lookupColumnPreferences($detector,Yii::app()->user->id);
	$this->renderPartial('helperfiles/_columnlist',array('columns'=>$columns,'savedColumns'=>$savedColumn));
    }

    public function actionSaveColumnPreferences(){

	//$json = $pref ;

	if(isset($_POST['pref']) || isset($_POST['detector'])):
	$pref = $_POST['pref'];

	$detector = $_POST['detector'];
	$userid = Yii::app()->user->id;


	ColumnPreferences::model()->deleteAll('user_id = :user_id and table_name= :table_name',array(':user_id'=>$userid,':table_name'=>$detector));

	$model =  new ColumnPreferences;
	$serializedData = serialize($pref);
	$model->user_id = $userid;

	$model->table_name = $detector;

	$model->allowed_columns_shown = $serializedData;

	$model->last_updated = time();
	$model->save();



	endif;
    }

    /* Invoked internally by actionGetDataSourceColumnsFromTable() */

    private function lookupColumnPreferences($config_table, $user_id) {
	$config_table = strtolower($config_table);
	$pref = Yii::app()->db->createCommand("SELECT
							  allowed_columns_shown
							FROM gadlay_column_preferences
							WHERE user_id = '$user_id'
					     AND table_name = '$config_table' "
		)->queryRow();

	$config_arr = ($pref['allowed_columns_shown'] === null) ? '' : unserialize(stripslashes($pref['allowed_columns_shown']));
	return $config_arr;
    }

    /* ajaxGetData() */

    protected function ajaxGetData($parsed_requestors) {
	$parsed_out_arr = array();
	//echo CJSON::encode('charlie');
	if (isset($parsed_requestors['fe_a_strong'])) {
	    $pref = Yii::app()->db->createCommand("SELECT
							  SiO2
							FROM dynamic_gadget_data
							WHERE internal_id = 1"
		    )->queryRow();

	    $parsed_requestors['fe_a_strong']['data'] = $pref['SiO2'];
	    echo CJSON::encode($parsed_requestors);
	}
    }

//end ajaxGetData()..




    /*
     * Abhinandan.
     *  fetchDashboardElements()
     *   Purpose: Invoked by actionDash() to fetch
     *             gadgetElements upon first page load.
     *
     *   @invoke  method  getPreference()
     *   @return  array
     *
     */

    public function fetchDashboardElements($gadgetType) {

	$origin = __FUNCTION__;


	$elements_arr = $this->getPreference($gadgetType, $origin);     ///

	return $elements_arr;
    }

//end fetchLeftMenu()..


    /*
     * Abhinandan.
     *  Method:  fetchDashboardElementsColors()
     *   @ Invokee : 'actionDash()'
     *   @ Param:    array  $elements_arr_i            (Represents the ith subarray only)
     *   @ Param:    array  $fetched_element_value_arr (See example below:)
     *                                                 Note: 0 represents the index which will
     *                                                 attach itself to the proper location
     *                                                 on the $elements_arr_i array..
     *
     *    Sample $fetched_element_value_arr = a string... Feb12th 2013..
     *
     *
     *   @Return (internal) string  Attaches the (new)
     *                              'active_color' unto
     *                               the original
     *                               parameter $elements_arr_i
     *
     *   @Return  array  The newly modified $elements_arr_i
     */

    protected function fetchDashboardElementsColors($elements_arr_i, $fetched_element_value_arr, $i) {

	//Controller::fb('inside fetchDashboardElementsColors');
	$colors = array(
	    '3set' => array(
		0 => 'red',
		1 => 'orange',
		2 => 'green'
	    ),
	    '5set' => array(
		0 => 'white',
		1 => 'blue',
		2 => 'red',
		3 => 'orange',
		4 => 'green'
	    )
	);

	$fetched_element = $fetched_element_value_arr;  //Feb12th: $fetched_element_value_arr is ie '5' belonging to Aluminum..

	if ($elements_arr_i['i'] == $i) {
	    $n = 0;
	    while ($n >= 0) {
		if ($fetched_element < $elements_arr_i['element_setpoint'][$n][0]) {
		    if ($elements_arr_i['element_colorset'] == '3set') {
			if ($n == 2) {
			    //$color = 'Color about to go past. Color should be ' .$colors[ $elements_arr_i['element_colorset'] ][$n];
			    $color = $colors[$elements_arr_i['element_colorset']][$n];
			    $n = -1;
			    return $color;
			} elseif ($fetched_element > $elements_arr_i['element_setpoint'][$n + 1][0]) {
			    //$color = 'Color should be ' .$colors[ $elements_arr_i['element_colorset'] ][$n+1];
			    $color = $colors[$elements_arr_i['element_colorset']][$n + 1];
			    $n = -1;
			    return $color;
			}

			$n++;
		    } elseif ($elements_arr_i['element_colorset'] == '5set') {
			if ($n == 4) {
			    //$color = 'Color about to go past. Color should be ' .$colors[ $elements_arr_i['element_colorset'] ][$n];
			    $color = $colors[$elements_arr_i['element_colorset']][$n];
			    $n = -1;
			    return $color;
			} elseif ($fetched_element > $elements_arr_i['element_setpoint'][$n + 1][0]) {
			    //$color = 'Color should be ' .$colors[ $elements_arr_i['element_colorset'] ][$n+1];
			    $color = $colors[$elements_arr_i['element_colorset']][$n + 1];
			    $n = -1;
			    return $color;
			}

			$n++;
		    }
		} elseif ($fetched_element > $elements_arr_i['element_setpoint'][$n][0]) {
		    //$color = 'Color is greater than ' .$elements_arr_i['element_setpoint'][$n][0].'. Color should be ' .$colors[ $elements_arr_i['element_colorset'] ][$n];
		    $color = $colors[$elements_arr_i['element_colorset']][$n];
		    $n = -1;
		    return $color;
		} elseif ($fetched_element == $elements_arr_i['element_setpoint'][$n][0]) {
		    //$color = 'Color is equal to '  .$elements_arr_i['element_setpoint'][$n][0].'. Color should be ' .$colors[ $elements_arr_i['element_colorset'] ][$n];
		    $color = $colors[$elements_arr_i['element_colorset']][$n];
		    $n = -1;
		    return $color;
		}
	    }//while..
	}//end    if..
	//return $elements_arr_i;
    }

//end fetchDashboardElementsColors()..

    /**
     *  The controller's default action
     */
    public function actionDash() {
	//Controller::fb('inside actionDash');
	//$mwc = new MasterTemplateiii;   //Abhinandan. July29th 2013: Begin logging..


	$uid = $this->uid;
	$default = 1;

	//ABHITBD
	$controllerid = $_SERVER["REQUEST_URI"];
		$pos = strpos($controllerid , "rawmix");

		// The !== operator can also be used.  Using != would not work as expected
		// because the position of 'a' is 0. The statement (0 != false) evaluates
		// to false.
		if ($pos !== false) {
	    $default = 4;
	    $uid = 999;
	    $this->uid = 999;
	}


	/* BEGIN invoked by view _d_liveStats.php */
	if (isset($_POST['ajaxForwardRequest'])) {
	    if (($_POST['ajaxForwardRequest'] == "fetchDashboardElements") && ($_POST['gadgetType'] != 'System_Messages') && ($_POST['gadgetType'] != 'Charts') && ($_POST['gadgetType'] != 'AlarmBar')) {
		$this->widgetsPos = $_POST['widgetsPos'];     //Abhinandan. March10th.. Set widgetsPos for eventual use @ query ~ line 1664..

		$elements_arr = $this->fetchDashboardElements($_POST['gadgetType']);

		// /////July 16th 2013......BEGIN Construction..
		$container_elements_arr = array();

		$all_tables_arr = $this->actionGetDataSourceTables(__FUNCTION__);  //Abhinandan.  @return 2dim array.. see below for loop..


		for ($i = 0; $i < count($elements_arr); ++$i) {
		    $element_type = $elements_arr[$i]['element_type'];            //Abhinandan.  Linux cares about type-sensitive table names, so we just have to use the actual table name.. not the one returned in $elements_arr[$i]['detector_source'] ...
		    foreach ($all_tables_arr as $k1 => $v1) {
			if (strtoupper($elements_arr[$i]['detector_source']) == strtoupper($v1)) {
			    $detector_source = $v1;
			}
		    }
		    //$detector_source = $elements_arr[$i]['detector_source'];   //Abhinandan. Commented out..July16th 2013..
		    // July16th 2013.. Commenting out original logic with timestamps +/- 3600..
		    //$query           = "SELECT `$element_type` FROM `$detector_source` WHERE created < unix_timestamp() + 3600 AND created > unix_timestamp() - 7200";
		    Yii::app()->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		    $query = "SELECT AVG(`$element_type`) as $element_type FROM `$detector_source` WHERE LocalendTime <(FROM_UNIXTIME( UNIX_TIMESTAMP() ,  '%y-%m-%d %h:%i:%s' )) AND LocalendTime > (FROM_UNIXTIME( UNIX_TIMESTAMP() - 900 ,  '%y-%m-%d %h:%i:%s' )) ";
		    $command = Yii::app()->db->createCommand($query);
		    $rs = $command->queryAll();

		    $fetched_element_value_arr = array();

		    if (count($rs) > 0) {
			foreach ($rs as $rs_k1 => $rs_v1) {
			    foreach ($rs_v1 as $rs_k2 => $rs_v2) {
				$fetched_element_value_arr[$rs_k1][$rs_k2][0] = round($rs_v2, 2);      //Feb12th..
			    }
			}
		    }

		    //Feb12th..  Let each $fetched_element_value_arr contain LEGACY records for a single given element ie 'Aluminum' ..
		    foreach ($fetched_element_value_arr as $fev_k1 => $fev_v1) {
			foreach ($fev_v1 as $fev_k2 => $fev_v2) {
			    $fetched_element_value_arr[$fev_k1][$fev_k2][] = $this->fetchDashboardElementsColors($elements_arr[$i], $fev_v2[0], $i);  //Feb12th: Added @param int $i ...
			}
		    }

		    $elements_arr[$i]['data_value'] = $fetched_element_value_arr;    //June3rd 2013: Never actually makes it here...
		} //end for    each of the elements..
		//print_r($elements_arr);

		echo CJSON::encode($elements_arr);
		return;
		///////July 16th 2013......END construction..
	    } //end if == "fetchDashboardElements"..
	    //Tables or system messages
	    elseif (($_POST['ajaxForwardRequest'] == "fetchDashboardElements") && ($_POST['gadgetType'] == 'System_Messages') && ($_POST['gadgetType'] != 'Charts') && ($_POST['gadgetType'] != 'AlarmBar')) {
		//Abhinandan. March12th.. Future TODO: Dave you will want to set a global 'detector' variable being passed from the client.. this way we do not have to statically assign the detector name in the SQL query..
		$widgetsPos = $_POST['widgetsPos'];
		//$get_detector = "SELECT detector_source FROM gadlay_gadgets_data WHERE widgetsPos = $widgetsPos ";

		$select_current_layout = Yii::app()->db->createCommand("SELECT lay_id FROM gadlay_layouts WHERE user_id = $uid AND default_layout = $default")->queryRow();

		$lay_id = $select_current_layout['lay_id'];

		$get_detector = "SELECT data_source,
			 detector_source,
			 display_style,
			 group_style
						FROM gadlay_gadgets_data WHERE widgetsPos = $widgetsPos AND lay_id = $lay_id ";
				//echo $get_detector;
		$element_command = Yii::app()->db->createCommand($get_detector);

		$element_rs = $element_command->queryAll();

		if (count($element_rs) > 0) {
		    $temp_element_arr = array();
		    foreach ($element_rs as $element_rs_k1 => $element_rs_v1) {
			foreach ($element_rs_v1 as $element_rs_k2 => $element_rs_v2) {
			    if (($element_rs_k2 == 'data_source') || ($element_rs_k2 == 'detector_source') || ($element_rs_k2 == 'display_style') || ($element_rs_k2 == 'group_style')) {
				$temp_element_arr[$element_rs_k2] = $element_rs_v2;
			    }
			}
		    }
		}

		$element_name = $temp_element_arr['data_source'];
		$detector_source = $temp_element_arr['detector_source'];
		$c_display_style = $temp_element_arr['display_style'];
		$c_group_style = $temp_element_arr['group_style'];

		$elemArray = explode(";", $element_name);
		$dispElem = "";
		$cntElem = 0;

		foreach ($elemArray as $elem) {
		    if ($elem) {
			$dispElem .= ",`$elem`";
			$cntElem++;
		    }
		}


		$ind = 0;
		$elem_list = explode(";", $element_name);
		$theadStr = '<tr>';
		$tbodyStr = '';

		$widthStr = floor(100 / (count($elem_list) + 1));
		$theadStr .='<th class="ui-state-default" style="width:' . $widthStr . '%;" ><div class="DataTables_sort_wrapper" >Time-Stamp</div></th>';

		foreach ($elem_list as $eval) {
		    if (!empty($eval)) {
			$theadStr .='<th class="ui-state-default" style="width:' . $widthStr . '%;" ><div class="DataTables_sort_wrapper" >' . $eval . '</div></th>';
		    }//set eval
		}
		$theadStr .='</tr>';

		//'System_Messages'
		//date_default_timezone_set('America/Los_Angeles');

		if (($_REQUEST['pageType'] == "timeRange") && (isset($_REQUEST['sTime'])) && (isset($_REQUEST['eTime']))) {
		    $prevTime = ($_REQUEST['eTime']);
		    $curTime = ($_REQUEST['sTime']);
		} else if (($_REQUEST['pageType'] == "tagIndex") && (isset($_REQUEST['tag'])) && (isset($_REQUEST['tagStatus']))) {

		    $tagId = ($_REQUEST['tag']);
		    $tagGrpId = ($_REQUEST['tGrp']);
		    $tagStat = ($_REQUEST['tagStatus']);

		    if ($tagStat == "queued") {
			$tagStatTable = "rta_tag_index_" . $tagStat;
		    } else {
			$tagStatTable = "rta_tag_index_completed";
		    }

		    $tGrpSet = 1;
		} else {
		    //TODO Change TIMERANGE
		    $curTime = date("Y-m-d H:i:s");
		    $curTimeSt = strtotime($curTime);

		    $prevTimeSt = $curTimeSt - (60 * 60); //60 mins worth Information
		    $prevTime = date("Y-m-d H:i:s", $prevTimeSt);
		    //echo $prevTimeSt;
		}

		//echo $curTime . " : " . $prevTime;exit();
		//Selecting Tagged Data and Take Out Limiting
		if ($tGrpSet) {
		    //Get the analysis Table Name
		    $tableNameQuery = "SELECT DB_ID_string,LocalstartTime as sTime,LocalendTime as eTime FROM `rta_config_master` rcm WHERE rcm.`rtaMasterID` IN (SELECT tag.`rtaMasterID` FROM $tagStatTable tag WHERE tagID=$tagId)";
		    $tcommand = Yii::app()->db->createCommand($tableNameQuery)->queryRow();

		    $analysisTableName = "analysis_" . $tcommand['DB_ID_string'];
		    $prevTime = $tcommand['sTime'];
		    $curTime = $tcommand['eTime'];

		    $cntRows = Yii::app()->db->createCommand("SELECT count(dataID) as cnt FROM `$analysisTableName`")->queryRow();

		    if ($cntRows["cnt"] > 0) {
			$query = "SELECT `LocalendTime` $dispElem FROM `$analysisTableName` WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC";
		    } else {
			$outData = "ERR::";
			echo $outData;
			return;
		    }
		} else {
		    //Abhinandan. Feb26th: Left off here.. NEED to fetch from db and then pass back..
		    $query = "SELECT `LocalendTime` $dispElem FROM `$detector_source` WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC";
		}
		$command = Yii::app()->db->createCommand($query, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY);

		$rs = $command->queryAll();

		if (count($rs) > 0) {
		    $rowCntr = 0;

		    foreach ($rs as $rs_k1 => $rs_v1) {
			$rowCntr++;

			if ($rowCntr % 2 == 0)
			    $tbodyStr .= '<tr class="gradeX even">';
			else
			    $tbodyStr .= '<tr class="gradeX odd">';

			if (is_array($rs_v1)) {
			    foreach ($rs_v1 as $rs_k2 => $rs_v2) {
				//if($rs_k2 =='endTic')
				if ($rs_k2 == 'LocalendTime') {
									$date = new DateTime($rs_v2);
									$trs_v2 = $date->format('m/d/y H:i:s');
				    //$trs_v2 = date("m/d/y h:i:s",$rs_v2);
				    $tbodyStr .= '<td style="width: 160px;">' . $trs_v2 . '</td>';
				} else if ($rs_v2 != ' ') {
				    $tbodyStr .= '<td >' . $rs_v2 . '</td>';
				}
			    }
			    $tbodyStr .= '</tr>';
			}
		    }
		}

		echo $theadStr . "##" . $tbodyStr;
		return;
	    }
	    //Charts
	    elseif (($_POST['ajaxForwardRequest'] == "fetchDashboardElements") && ($_POST['gadgetType'] != 'System_Messages') && ($_POST['gadgetType'] != 'AlarmBar') && ($_POST['gadgetType'] == 'Charts')) {

		// Abhinandan. The following pre-logic grabs both the data_source (ie element name) AND the detector_source (ie Detector-1) from the db..
		// This sets us up for the main query following afterwards..
		$widgetsPos = $_POST['widgetsPos'];
		$uid = $this->uid;
		//$get_element = "SELECT data_source, detector_source FROM `gadlay_gadgets_data` WHERE widgetsPos = '$widgetsPos' ";      ////////

		$get_element = "SELECT
			 g.data_source,
			 g.detector_source,
			 g.display_style,
			 g.group_style,
			 g.lay_id,
			 l.lay_id,
			 l.default_layout
			FROM $this->_gadgetsDataTable AS g
			INNER JOIN $this->_layoutsTable AS l
			 ON l.lay_id = g.lay_id
			WHERE g.widgetsPos = '$widgetsPos'
			 AND l.user_id = $uid
			 AND l.default_layout = $default";
		//echo $get_element;exit();
		$element_command = Yii::app()->db->createCommand($get_element);

		$element_rs = $element_command->queryAll();
		//echo "read:".print_r($element_rs);
		if (count($element_rs) > 0) {
		    $temp_element_arr = array();
		    foreach ($element_rs as $element_rs_k1 => $element_rs_v1) {
			foreach ($element_rs_v1 as $element_rs_k2 => $element_rs_v2) {
			    if (($element_rs_k2 == 'data_source') || ($element_rs_k2 == 'detector_source') || ($element_rs_k2 == 'display_style') || ($element_rs_k2 == 'group_style')) {
				$temp_element_arr[$element_rs_k2] = $element_rs_v2;
			    }
			}
		    }
		}

		$element_name = $temp_element_arr['data_source'];
		$detector_source = $temp_element_arr['detector_source'];
		$c_display_style = $temp_element_arr['display_style'];
		$c_group_style = $temp_element_arr['group_style'];

		$elemArray = explode(";", $element_name);
		$dispElem = "";
		$cntElem = 0;

		foreach ($elemArray as $elem) {
		    if ($elem) {
			$dispElem .= ",`$elem`";
			$cntElem++;
		    }
		}


		$top_arr = array();
		$ind = 0;
		$elem_list = explode(";", $element_name);

		foreach ($elem_list as $eval) {
		    if (!empty($eval)) {
			$top_arr[$eval] = array();
		    }//set eval
		}

		//date_default_timezone_set('America/Los_Angeles');
		//TODO Change TIMERANGE
		if (($_REQUEST['pageType'] == "timeRange") && (isset($_REQUEST['sTime'])) && (isset($_REQUEST['eTime']))) {
		    $prevTime = ($_REQUEST['eTime']);
		    $curTime = ($_REQUEST['sTime']);
		} else if (($_REQUEST['pageType'] == "tagIndex") && (isset($_REQUEST['tag'])) && (isset($_REQUEST['tagStatus']))) {
		    //$prevTime		= ($_REQUEST['eTime']);
		    //$curTime     	= ($_REQUEST['sTime']);
		    $tagId = ($_REQUEST['tag']);
		    $tagGrpId = ($_REQUEST['tGrp']);
		    $tagStat = ($_REQUEST['tagStatus']);

		    if ($tagStat == "queued") {
			$tagStatTable = "rta_tag_index_" . $tagStat;
		    } else {
			$tagStatTable = "rta_tag_index_completed";
		    }

		    $tGrpSet = 1;
		} else {
		    //TODO Change TIMERANGE
		    $curTime = date("Y-m-d H:i:s");
		    $curTimeSt = strtotime($curTime);

		    $prevTimeSt = $curTimeSt - (60 * 60); //60 mins worth Information
		    $prevTime = date("Y-m-d H:i:s", $prevTimeSt);
		}

		//Selecting Tagged Data and Take Out Limiting
		if ($tGrpSet) {
		    //Get the analysis Table Name
		    $tableNameQuery = "SELECT DB_ID_string,LocalstartTime as sTime,LocalendTime as eTime FROM `rta_config_master` rcm WHERE rcm.`rtaMasterID` IN (SELECT tag.`rtaMasterID` FROM $tagStatTable tag WHERE tagID=$tagId)";
		    $tcommand = Yii::app()->db->createCommand($tableNameQuery)->queryRow();

		    $analysisTableName = "analysis_" . $tcommand['DB_ID_string'];
		    $prevTime = $tcommand['sTime'];
		    $curTime = $tcommand['eTime'];

		    $cntRows = Yii::app()->db->createCommand("SELECT count(dataID) as cnt FROM `$analysisTableName`")->queryRow();

		    if ($cntRows["cnt"] > 0) {
			$query = "SELECT `LocalendTime` $dispElem FROM `$analysisTableName` WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC"; // LIMIT 100
		    } else {
			$outData = "ERR::";
			echo $outData;
			return;
		    }
		} else {
		    //Abhinandan. Feb26th: Left off here.. NEED to fetch from db and then pass back..
		    //$query = "SELECT `created`, `Calcium` FROM `Detector-1` ";             //Abhinandan. made `Detector-1` uppdercase D because Linux @ castor is sensitive and needs this uppercase D.
		    $query = "SELECT `LocalendTime` $dispElem FROM `$detector_source` WHERE LocalendTime BETWEEN( '$prevTime') AND ('$curTime') ORDER BY LocalendTime ASC"; // LIMIT 100
		}

		if ($c_group_style == "hgrouped")
		    $query .= " LIMIT 10";

		//$query = "SELECT `LocalendTime` ,`Al2O3`,`SiO2`,`Fe2O3` FROM `Analysis_detector_1` WHERE LocalendTime".
		//			" BETWEEN( '2014-01-12 01:07:44') AND ('2014-01-17 02:07:44') ORDER BY LocalendTime ASC LIMIT 1500";
		//echo $query;
		$command = Yii::app()->db->createCommand($query);

		$rs = $command->queryAll(); ////

		if (count($rs) > 0) {
		    $temp_arr = array();
		    foreach ($rs as $rs_k1 => $rs_v1) {

			foreach ($rs_v1 as $rs_k2 => $rs_v2) {
			    //debugg..

			    if ($c_group_style == "individual") { //TODO
				$key = 1;   //Individual tag
			    } else
				$key = array_search($rs_k2, $elemArray); // $key = 2;

			    if ($rs_k2 == 'LocalendTime') {
				//$temp_arr[$rs_k1][1] = $rs_v2;
				$rs_v2 = strtotime($rs_v2);     //Date to time:TIMERANGE
				$temp_arr[$rs_k1][$rs_k2] = $rs_v2 * 1000;
				$lastTimeStamp = $rs_v2 * 1000;
			    } else {
				$temp_arr[$rs_k1][$rs_k2] = $rs_v2;       //Abhinandan. need to multiply x 1000 @ client now..

				if ($c_group_style == "hgrouped") {
				    $top_arr[$rs_k2][$rs_k1] = array($lastTimeStamp, $rs_v2); //Send the Values Directly.
				} else {
				    $top_arr[$rs_k2][$rs_k1] = $rs_v2;
				}
			    }
			}
		    }
		} else if (count($rs) <= 0) {
		    $outData = "ERR::";
		    echo $outData;

		    return;
		}

		$outData = "SUC::cstyle:$c_display_style##gstyle:$c_group_style##elemName:$element_name";
		$outData .="##timeStart:" . strtotime($prevTime) . "##timeEnd:" . strtotime($curTime);

		foreach ($top_arr as $elemn => $vall) {
		    $outData .="##" . $elemn . "**" . str_replace("\"", "", CJSON::encode($vall)) . "";
		}


		echo $outData;

		return;
	    }//charts display
	    //Alarmbar

	    /*
	      elseif( ($_POST['ajaxForwardRequest'] == "fetchDashboardElements") && ($_POST['gadgetType'] != 'System_Messages') && ($_POST['gadgetType'] != 'Charts') &&($_POST['gadgetType'] == 'AlarmBar')  )
	      {

	      //First..
	      $query = "SELECT `short_descrip`, `long_descrip`, `user_id`, `timestamp` FROM `tbl_audit_trail_ii` WHERE controller_name = 'masterTemplateiii' ORDER BY `id` DESC LIMIT 1";
	      $command = Yii::app()->db->createCommand($query);

	      $rs_A = $command->query();
	      if( count($rs_A) > 0 )
	      {
	      $k = 0;
	      foreach($rs_A as $rs_k1 => $rs_v1){
	      foreach($rs_v1 as $rs_k2 => $rs_v2){
	      if($rs_k2 == 'short_descrip')
	      {
	      $messages_sd_arr[$k] = $this->actionDashAlarmBar_A($rs_v2);
	      }
	      if($rs_k2 == 'timestamp')
	      {
	      $messages_sd_arr[$k]['logged_time'] = $rs_v2;
	      }
	      if($rs_k2 == 'long_descrip')
	      {
	      $messages_sd_arr[$k]['long_descrip'] = $rs_v2;
	      }
	      if($rs_k2 == 'user_id')
	      {
	      $messages_sd_arr[$k]['user_id'] = $rs_v2;
	      }
	      }
	      ++$k;
	      }
	      $k = 0;
	      }


	      //Second query..
	      $query = "SELECT `short_descrip`, `long_descrip`, `user_id`, `timestamp` FROM `tbl_audit_trail_ii` WHERE controller_name = 'uISettings' ORDER BY `id` DESC LIMIT 1";
	      $command = Yii::app()->db->createCommand($query);

	      $rs_B = $command->query();
	      if( count($rs_B) > 0 )
	      {
	      $j = 1;
	      foreach($rs_B as $rs_k1 => $rs_v1){
	      foreach($rs_v1 as $rs_k2 => $rs_v2){
	      if($rs_k2 == 'short_descrip')
	      {
	      $messages_sd_arr[$j] = $this->actionDashAlarmBar_A($rs_v2);
	      }
	      if($rs_k2 == 'timestamp')
	      {
	      $messages_sd_arr[$j]['logged_time'] = $rs_v2;
	      }
	      if($rs_k2 == 'long_descrip')
	      {
	      $messages_sd_arr[$j]['long_descrip'] = $rs_v2;
	      }
	      if($rs_k2 == 'user_id')
	      {
	      $messages_sd_arr[$j]['user_id'] = $rs_v2;
	      }
	      }
	      ++$j;
	      }
	      $j = 0;
	      }


	      //First query..
	      $query = "SELECT `short_descrip`, `long_descrip`, `user_id`, `timestamp` FROM `tbl_audit_trail_ii` WHERE controller_name = 'dash' ORDER BY `id` DESC LIMIT 1";
	      $command = Yii::app()->db->createCommand($query);

	      $rs_C = $command->query();

	      if( count($rs_C) > 0 )
	      {
	      $i = 2;
	      foreach($rs_C as $rs_k1 => $rs_v1){
	      foreach($rs_v1 as $rs_k2 => $rs_v2){
	      if($rs_k2 == 'short_descrip')
	      {
	      $messages_sd_arr[$i] = $this->actionDashAlarmBar_A($rs_v2);
	      }
	      if($rs_k2 == 'timestamp')
	      {
	      $messages_sd_arr[$i]['logged_time'] = $rs_v2;
	      }
	      if($rs_k2 == 'long_descrip')
	      {
	      $messages_sd_arr[$i]['long_descrip'] = $rs_v2;
	      }
	      if($rs_k2 == 'user_id')
	      {
	      $messages_sd_arr[$i]['user_id'] = $rs_v2;
	      }
	      }
	      ++$i;
	      }
	      $i = 0;
	      }


	      //Give color to each element of each subarray @messages_sd_arr..
	      for($m=0; $m<count($messages_sd_arr); ++$m){
	      if($messages_sd_arr[$m]['priority'] == 'low')
	      {
	      $messages_sd_arr[$m]['color'] = 'blue';
	      }
	      elseif($messages_sd_arr[$m]['priority'] == 'medium')
	      {
	      $messages_sd_arr[$m]['color'] = 'orange';
	      }
	      elseif($messages_sd_arr[$m]['priority'] == 'high')
	      {
	      $messages_sd_arr[$m]['color'] = 'red';
	      }
	      } //end for..


	      //Set custom message for each element of each subarray @messages_sd_arr..
	      for($p=0; $p<count($messages_sd_arr); ++$p){
	      if($messages_sd_arr[$p]['category'] == 'UserActivity')
	      {
	      $messages_sd_arr[$p]['custom_message'] = "User Activity was detected for ". $messages_sd_arr[$p]['user_id'] . ". ";
	      }
	      elseif($messages_sd_arr[$p]['category'] == 'ManagerActivity')
	      {
	      $messages_sd_arr[$p]['custom_message'] = "Manager Activity was detected for ". $messages_sd_arr[$p]['user_id'] . ". ";
	      }
	      foreach($messages_sd_arr[$p] as $k1 => $v1){
	      if($k1 == 'long_descrip')
	      {
	      if($v1 == 'actionIndex')
	      {
	      $messages_sd_arr[$p]['custom_message'] .= "The main page was accessed. ";
	      }
	      elseif($v1 == 'actionUpdate')
	      {
	      $messages_sd_arr[$p]['custom_message'] .= "An update was performed. ";
	      }
	      elseif($v1 == 'actionDash')
	      {
	      $messages_sd_arr[$p]['custom_message'] .= "The dashboard page was accessed. ";
	      }
	      }
	      if($k1 == 'message_type')
	      {
	      if($v1 == 'unauthorized_access_attempt')
	      {
	      $messages_sd_arr[$p]['custom_message'] .= "This was an unauthorized access attempt. ";
	      }
	      elseif($v1 == 'settings_changed')
	      {
	      $messages_sd_arr[$p]['custom_message'] .= "User settings were modified. ";
	      }
	      elseif($v1 == 'user_tracking')
	      {
	      $messages_sd_arr[$p]['custom_message'] .= "This user's whereabouts are currently being recorded. ";
	      }
	      }
	      }
	      }

	      echo CJSON::encode($messages_sd_arr);
	      //echo CJSON::encode($record_list);
	      return;

	      } //end if  AlarmBar gadget..
	     */
	}//end if isset ajaxForwardRequest..
	elseif (isset($_POST['ajaxErrorRequest'])) {
	    echo CJSON::encode('server says hello error_gadget');
	    return;
	}


	if (isset($_POST['lse_json_serialized'])) {

	    $lse_obj = json_decode($_POST['lse_json_serialized']);
	    if (is_object($lse_obj)) {
		$temp_arr = array();
		foreach ($lse_obj as $k1 => $v1) {
		    if (($k1 == 'fe_a_strong') && (is_object($v1))) {


			foreach ($v1 as $k2 => $v2) {
			    if ($k2 == 'id') {
				$temp_arr[$k1][$k2] = $v2;
			    } elseif ($k2 == 'data') {
				$temp_arr[$k1][$k2] = $v2;
			    }
			}
		    }
		}
	    }

	    $this->ajaxGetData($temp_arr);
	    return;
	}

	// Default
	//Get TagGroups List
	$connection = Yii::app()->db;   // assuming you have configured a "db" connection
	$command = $connection->createCommand("SELECT tagGroupID,tagGroupName from tag_group WHERE 1");
	$dataRList = $command->query();
	$rowTagGrpsList = $dataRList->readAll();


	//Get Tag(s) List
	$command = $connection->createCommand("SELECT tagID,tagGroupID,tagName,LocalstartTime,LocalendTime,status FROM `rta_tag_index_queued` union all SELECT tagID,tagGroupID,tagName,LocalstartTime,LocalendTime,status  FROM `rta_tag_index_completed` ");
	$dataRList = $command->query();
	$rowTagsList = $dataRList->readAll();


	//Abhinandan. First page load, fetch left menu settings from db..
	$a = $this->fetchLeftMenu();

	// echo "echo I am here";
	$this->registerScript();


	//Controller::fb($pref);   //May28th: $pref looks good coming from the db..

	$uid = $this->uid;
	$controllerid = $this->id;
	$default = 1;


	//LR05/03/2014
	if ($controllerid == 'rawmix') {
	    $default = 4;
	    $uid = 999;
	    $this->uid = 999;
	} else if ($controllerid == 'dash2') {
	    $default = 2;
	} else if ($controllerid == 'dash3') {
	    $default = 3;
	}


	$pref = $this->getPreference(NULL, NULL, $default);
	$def_layout = Layouts::model()->findAll(array('condition' => 'user_id=:x AND default_layout=:y', 'params' => array(':x' => $uid, ':y' => $default)));


	$layouts = Layouts::model()->findAll(array('condition' => 'user_id=:x', 'params' => array(':x' => $uid)));
	//Controller::fb($layouts);
	//Abhinandan. Both @$pref['widString'] AND @$pref['widgetsPos'] MUST be in identical order, so the new code @DashHelper::createDisplay() can work properly..
	

	$this->render('showDash', array(
	    'portlets' => $this->applyUserPref($pref), //May28th: Bug fix needed here..auto-adding gadgets to our layout..
	    'widString' => $pref['widString'], //Abhinandan. Jan13th 2013: Fixed "undefined index widString" error. Solution: Re-reserialized 'title' field of "dashboard_page" table..
	    'columns' => $this->_columns,
	    'showHeader' => $this->_showHeader,
	    'autoSave' => $this->_autoSave,
	    'editable' => $this->editable,
	    'def_layout' => $def_layout,
	    'resetUrl' => $this->createUrl('resetDash'),
	    'url' => Yii::app()->theme->baseUrl . "/css/images",
	    'contentBefore' => $this->contentBefore,
	    'contentAfter' => $this->contentAfter,
	    'left_menu_arr' => $a,
	    'layouts' => $layouts, //////////////NEW.. july10th 2013..
	    'tagGrpDataLst' => $rowTagGrpsList,
	    'tagDataLst' => $rowTagsList,
	));
    }

    public function actionUpdtLogMessagesPopBox() {
		if(isset($_REQUEST["makeread"])){
			$pid = $_REQUEST["makeread"];
			$pidStr = "id={$pid}";
			if($_REQUEST["makeread"] == 999)
				$pidStr = 1;
			$query = "UPDATE `rm_pop_messages` set flag=1 WHERE $pidStr";
			$tcommand = Yii::app()->db->createCommand($query);
			$tcommand->execute();

			echo "success";
			exit();
		}//if makread
    }

    public function actionGetLogMessagesPopBox() {
		$messages_sd_arr =array();
	//Just give me counters
		if(isset($_REQUEST["counter"]) == 1){
			$query = "SELECT count(*) as counter FROM `rm_pop_messages` WHERE controller_name = 'rhea' and flag=0 ORDER BY `id` DESC LIMIT 10";
			$tcommand = Yii::app()->db->createCommand($query)->queryRow();

			if(count($tcommand) > 0){
				$counter = $tcommand["counter"];
			}
			else
				$counter = 0;

			echo $counter;
			exit();
		}

	//First query..
	$query = "SELECT id,flag, `short_descrip`, `long_descrip`, `user_id`,priority, `timestamp` FROM `rm_pop_messages` WHERE controller_name = 'rhea' and flag=0 ORDER BY `id` DESC LIMIT 10";
	$rs_C = Yii::app()->db->createCommand($query)->query();

	$i = 0;
	//print_r($query);
	//if (count($rs_C) > 0)
	{

	    foreach ($rs_C as $rs_k1 => $rs_v1) {
		$popid = $rs_v1["id"];
		foreach ($rs_v1 as $rs_k2 => $rs_v2) {
		    if ($rs_k2 == 'short_descrip') {
			$messages_sd_arr[$i] = $this->actionDashAlarmBar_A($rs_v2);

			$sMsg = "<a id='in_popchecker{$popid}' class='in_popchecker ' title='{$popid}' >".
					'<img width="35" height="35" src="'.Yii::app()->theme->baseUrl.'/images/navicons/93.png"></a><br/>';
			if ($rs_v2 == "SUCCESS") {
				$messages_sd_arr[$i]['class'] = " ui-state-success";
			    //$sMsg .= "<span style='font-weight:bold;'>Success!</span>";
			} else {
				$messages_sd_arr[$i]['class'] = " ui-state-error";
			    //$sMsg .= "<span style='font-weight:bold;'>Error!</span>";
			}
			$messages_sd_arr[$i]['short_descrip'] = $sMsg;
		    }
		    if ($rs_k2 == 'timestamp') {
			$messages_sd_arr[$i]['logged_time'] = $rs_v2;
		    }
		    if ($rs_k2 == 'long_descrip') {
			$messages_sd_arr[$i]['long_descrip'] = $rs_v2;
		    }
		    if ($rs_k2 == 'user_id') {
			$messages_sd_arr[$i]['user_id'] = $rs_v2;
		    }
		    if ($rs_k2 == 'priority') {
			$messages_sd_arr[$i]['priority'] = $rs_v2;
		    }
		}//foreach
		++$i;
	    }//foreach
	    $i = 0;
	}


	//Give color to each element of each subarray @messages_sd_arr..
	for ($m = 0; $m < count($messages_sd_arr); ++$m) {
	    if ($messages_sd_arr[$m]['priority'] == 'low') {
		$messages_sd_arr[$m]['color'] = 'blue';
	    } elseif ($messages_sd_arr[$m]['priority'] == 'medium') {
		$messages_sd_arr[$m]['color'] = 'orange';
	    } elseif ($messages_sd_arr[$m]['priority'] == 'high') {
		$messages_sd_arr[$m]['color'] = 'red';
	    } else {
		$messages_sd_arr[$m]['color'] = 'white';
	    }
	} //end for..


	//Set custom message for each element of each subarray @messages_sd_arr..
	for ($p = 0; $p < count($messages_sd_arr); ++$p) {

	    foreach ($messages_sd_arr[$p] as $k1 => $v1) {
		if ($k1 == 'long_descrip') {
		    $messages_sd_arr[$p]['custom_message'] = "<p style='font-size:medium;vertical-align: bottom;color:black;line-height: 18px;padding-left:5px;border-bottom:1px solid white;' class='".$messages_sd_arr[$p]['class'] ."' >" .
													"<span style='font-weight: bold; text-align: left; float: left; padding: 10px 10px 10px 0px;'>".$messages_sd_arr[$p]['logged_time']."</span>".
													$messages_sd_arr[$p]['short_descrip'] . "<span style='margin-top:5px;display:block;'> " . $messages_sd_arr[$p]['long_descrip'] . "</span".
												"</p>";
		}
	    }
	}

	$messages_sd_arr[999]['custom_message'] = '<p style="font-size: medium; vertical-align: bottom; color: black; line-height: 18px; padding-left: 5px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: white;" class=" ui-state-error">'.
													'<span style="font-weight: bold; text-align: left; float: left; padding: 10px 10px 10px 0px;">Mark All Read</span>'.
													"<a id='in_popchecker999' class='in_popchecker ' title='999' >".
										'<img width="35" height="35" src="'.Yii::app()->theme->baseUrl.'/images/navicons/93.png"></a><br/>'.
												'</p>';
	echo CJSON::encode($messages_sd_arr);
	//echo CJSON::encode($record_list);
	return;
    }


    public function actionDashAlarmBar_A($db_str) {
	$temp_arr = array();
	$exploded_arr = explode(';', $db_str);

	foreach ($exploded_arr as $k1 => $v1) {
	    if (strlen($v1) > 0) {
		$no_space_v1 = preg_replace('/\s+/', '', $v1);                         //strip whitespace..
		$exploded_val = substr($no_space_v1, strpos($no_space_v1, ':') + 1);  //Grab everything after :
		$wSv_offset = strlen($exploded_val) + 1;                              //Define offset for next step @ $field_name..

		$field_name = substr($no_space_v1, 0, -($wSv_offset));
		$temp_arr[$field_name] = $exploded_val;
	    }
	}
	return $temp_arr;
    }

//end actionDashAlarmBar_A()..

    /**
     * Save dashboard state via AJAX
     * @param <int> $userId
     *
     * Abhinandan. July9th 2013 - Re-write actionSaveDash()..
     *  Purpose: Serialize the array prior to passing off to 'setPreference()'..
     * Sample of what $_POST looks like (after encoding it to screen):
     *  { "widgetsPos" : [ ["Alerts"] ], "columnsCount" : "0" }
     *
     */
    public function actionSaveDash($userId) {
	if (isset($userId) && !empty($userId)) {
	    //echo CJSON::encode('alpha');

	    if (($id = ($userId > 0) ? $userId : FALSE) !== FALSE) {  //Abhinandan. If we have a valid userid..
		for ($i = 0; $i < count($_POST['result'][0]); ++$i) {                                   //$i is our widgetPos value..
		    $no_space_v1 = preg_replace('/\s+/', '', $_POST['result'][0][$i]);
		    $gid = substr($no_space_v1, strpos($no_space_v1, ':') + 1);    // 1010 ..gadget id
		    $wSv_offset = strlen($gid) + 1;
		    $gadgetType = substr($no_space_v1, 0, -($wSv_offset));                // Alerts

		    $update_query = "UPDATE $this->_gadgetsDataTable
			  SET `widgetsPos` = '$i'
			   WHERE gadget_data_id = $gid
			    AND gadget_type = '$gadgetType'";
		    $command = Yii::app()->db->createCommand($update_query);
		    $command->execute();
		}
		echo CJSON::encode('Changes Saved.');
		/*
		  $preference = serialize(
		  array(
		  'result' => $_POST['result']
		  )
		  );

		  echo CJSON::encode($preference);

		  //Feb24th commenting out, debugging..

		  echo (int) $this->setPreference($preference);
		  Yii::app()->end();
		 */
	    } else {
		throw new CHttpException(404, 'The user id was not found for the requested action.');
	    }
	}
    }

//end actionSaveDash()..


    /*
     * Abhinandan.
     *  fetchLeftMenu()
     *   Purpose: Used by actionCreate ($post requests having 'alpha' as parameter)
     *            to fetch menu data upon first page load.
     *
     *   @invoke  method  getPreference()
     *   @return  array
     *
     */

    protected function fetchLeftMenu() {
	$menu_options = array(
	    'select' => "menu_data",
	    'from' => "left_menu",
	    'where' => "user_id",
	);

	$origin = __FUNCTION__;

	//Controller::fb('fetchLeftMenu(), now invoking getPreference..');
	$menu_arr = $this->getPreference($menu_options, $origin);

	return $menu_arr; //debuggin output for now..
    }

//end fetchLeftMenu()..

    /**
     * Reset dashboard state to default and go to default action
     */
    public function actionResetDash() {
	if ($this->setPreference(null)) {
	    Yii::app()->user->setFlash('resetDashboard', YII::t('Dashboard', 'Dashboard reset to default.'));
	    $this->redirect(array($this->defaultAction));
	}
    }

    /**
     * Store dashboard state to DB
     * If $preference is null - reset to default
     * @param <string> $preference
     * @return <bool>
     */
    private function setPreference($preference) {
	$conn = Yii::app()->db;
	$command = $conn->createCommand();
	try {
	    if ($this->getPreference()) {
		$command->update($this->_tableName, array($this->_userPrefFieldName => addslashes($preference)),
			//$this->_userIdFieldName . "=:id",
			$this->_userIdFieldName . "=:id AND " . $this->_defaultLayout . "=" . $this->_defaultLayoutValue, array(":id" => $this->uid));
	    }

	    return true;
	} catch (Exception $e) {
	    echo "Something Went wrong";
	    return false;
	}
    }

    /**
     * Read dashboard state from DB
     * @return <array>
     *
     *  Abhinandan. sample createCommand:
     *   EDIT: 12/26: Added placeholder params ($menu_options and $origin)
     *        * These placeholder options are set and called by 'fetchLeftMenu()'..
     *   'select title from dashboard_page where user_id=7'
     *
     *  i.) If we have a result set, parse it. Sample output:
     *
     *      $pref = array(
     *                 'columnsCount' => '0'
     *                 'widgetsPos' => array(
     *                                   '0' => array(
     *                                            '0' => 'Live_Status'
     *                                            '1' => 'Charts'
     *                                            '2' => 'Alerts'
     *                                            '3' => 'Meter_Gauge'
     *                                            '4' => 'System_Messages'
     *                                            '5' => 'AlarmBar'
     *                                            '6' => 'IdiotLights'
     *                                   )
     *                 ),
     *
     *                 'widString' => 'button-toggle:1; Live_Status:6; Charts:6; Alerts:2; Meter_Gauge:2; System_Messages:4; AlarmBar:6; IdiotLights:6; '
     *      )
     */
    //Lr 08/03/14  to show different dashboard
    public function getPreference($menu_options = null, $origin = null, $defaultLayout = 1) {

	//Abhinandan. $this->uid = 1 (guest)..
	if (!is_null($menu_options) && !is_null($origin)) {
	    if ($origin == "fetchLeftMenu") {
		$select = $menu_options['select'];  // "menu_data"
		$from = $menu_options['from'];    // "left_menu"
		$where = $menu_options['where'];   // "user_id"
		$uid = $this->uid;

		//Abhinandan. Be careful, sometimes wrapping $variable can lead to unexpected fetched result.. other times, not.
		$pref = Yii::app()->db->createCommand("SELECT
							   $select
							  FROM $from
							  WHERE $where = $uid"
			)->queryRow();

		return ($pref[$select] === null) ? '' : unserialize(stripslashes($pref[$select]));
	    } //end if origin == 'fetchLeftMenu'
	    elseif ($origin == "fetchDashboardElements") {
		Controller::fb('inside fetchDashboardElements');
		$color_keys = array(//Abhinandan. Keys which will be later used in 'fetchDashboardElementsColors()' for pulling the appropriate 'active_color'...
		    '3set' => array(
			'red' => 0,
			'orange' => 1,
			'green' => 2
		    ),
		    '5set' => array(
			'white' => 0,
			'blue' => 1,
			'red' => 2,
			'orange' => 3,
			'green' => 4
		    )
		);


		$gadgetType = $menu_options;
		$uid = $this->uid;
		$widgetsPos = $this->widgetsPos;



		$query = "SELECT
		  e.element_setpoint,
		  e.element_id,
		  e.element_type,
		  e.gadget_data_id,
		  e.order_location,
		  e.element_colorset,
		  g.gadget_data_id,
		  g.gadget_type,
		  g.widgetsPos,
		  g.last_updated,
		  g.data_source,
		  g.detector_source,
		  g.lay_id,
		  l.lay_id,
		  l.user_id,
		  l.default_layout
		 FROM gadlay_elements AS e
		  INNER JOIN gadlay_gadgets_data AS g
		   ON e.gadget_data_id = g.gadget_data_id
		  INNER JOIN gadlay_layouts as l
		   ON g.lay_id = l.lay_id
		    WHERE g.gadget_type = '$gadgetType'
		     AND g.widgetsPos = '$widgetsPos'
		     AND l.user_id = $uid
		     AND l.default_layout = {$defaultLayout} ";



		$command = Yii::app()->db->createCommand($query, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY);


		$rs = $command->queryAll();

		if (count($rs) > 0) {
		    $setpoint_arr = array();
		    $template_arr = array();
		    foreach ($rs as $k1 => $v1) {
			if (is_array($v1)) {
			    //Build up our '$setpoint_arr' array with numeric array indices.. (for later comparison of set point values)
			    $setpoint_arr = unserialize($v1['element_setpoint']);
			    arsort($setpoint_arr);                                 //Might need to change to 'sort', we WANT to re-index the keys..   //Sort; Index 0 contains highest value.. working on downwards.
			    $temp_arr = array();
			    foreach ($setpoint_arr as $sp_k1 => $sp_v1) {                                    //Abhinandan : Feb7th 2013, new code..
				if ($sp_k1 == 'red') {
				    $temp_arr[$color_keys[$v1['element_colorset']][$sp_k1]][0] = $sp_v1;
				}
				if ($sp_k1 == 'orange') {
				    $temp_arr[$color_keys[$v1['element_colorset']][$sp_k1]][0] = $sp_v1;
				}
				if ($sp_k1 == 'green') {
				    $temp_arr[$color_keys[$v1['element_colorset']][$sp_k1]][0] = $sp_v1;
				}
				if ($sp_k1 == 'white') {
				    $temp_arr[$color_keys[$v1['element_colorset']][$sp_k1]][0] = $sp_v1;
				}
				if ($sp_k1 == 'blue') {
				    $temp_arr[$color_keys[$v1['element_colorset']][$sp_k1]][0] = $sp_v1;
				}
			    }

			    $template_arr[$k1]['element_setpoint'] = $temp_arr;              //Custom-built (see directly above)..
			    $template_arr[$k1]['element_type'] = $v1['element_type'];
			    $template_arr[$k1]['data_source'] = $v1['data_source'];
			    $template_arr[$k1]['detector_source'] = $v1['detector_source'];
			    $template_arr[$k1]['active_color'] = "";                      //Represents what the user sees. It is affected by the data passed back from the 'data_source' (server-side)...
			    $template_arr[$k1]['i'] = $k1;

			    $template_arr[$k1]['element_colorset'] = $v1['element_colorset'];

			    $template_arr[$k1]['dom_unique_attribute'] = $v1['gadget_data_id'] . "_" . $v1['element_id'] . "_" . $v1['last_updated'];
			    $template_arr[$k1]['order_location'] = $v1['order_location'];
			}
		    }
		    return $template_arr;
		}//end  if count($rs) > 0
	    } //end if origin == "fetchDashboardElements"
	}



	/*
	 *   May29th 2013:
	 *     Build the 'gadlay_layouts.gadPlacement' field before you actually fetch it..
	 *      Before it was fetching what the trigger was storing whenever a layout was initially created..
	 *
	 */

	$uid = $this->uid;
	$build_gadPlacement_query = "SELECT
				   l.lay_id,
				   l.user_id,
				   l.gadPlacement,
				   l.default_layout,
				   g.widgetsPos,
				   g.gadget_data_id,
				   g.gadget_type,
				   g.lay_id,
				   g.gadget_size,
				   m.id,
				   m.small,
				   m.medium,
				   m.large
				  FROM gadlay_layouts as l
				   INNER JOIN gadlay_gadgets_data AS g
				  ON l.lay_id = g.lay_id
				   INNER JOIN gadlay_master_portlets AS m
				  ON g.gadget_type = m.id
				   WHERE l.user_id = $uid
				    AND l.default_layout = {$defaultLayout} ";


		//echo $build_gadPlacement_query;
	$command = Yii::app()->db->createCommand($build_gadPlacement_query, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY);
	$rs = $command->queryAll();


	if (count($rs) > 0) {
	    $formal_arr = array();   //The complete array which will eventually be serialized into 'gadlay_layouts.gadPlacement'..
	    $widgetsPos_arr = array();   //Prior to serialization..
	    $widString = "";
	    foreach ($rs as $k1 => $v1) {
		if (is_array($v1)) {
		    if (!is_null($v1['widgetsPos'])) {
			$widgetsPos_var = $v1['widgetsPos'];
			$widgetsPos_arr[0][$widgetsPos_var]['gadget_type'] = $v1['gadget_type'];
			$widgetsPos_arr[0][$widgetsPos_var]['gadget_size'] = $v1[$v1['gadget_size']];
		    }
		}
	    } //end top foreach..
	    //Controller::fb($widgetsPos_arr);

	    ksort($widgetsPos_arr[0]);


	    //Build the 'widString'..
	    foreach ($widgetsPos_arr[0] as $k1 => $v1) {
		if (isset($v1['gadget_type'])) {
		    $widString = $widString . $v1['gadget_type'] . ':';
		}
		if (isset($v1['gadget_size'])) {
		    $widString = $widString . $v1['gadget_size'] . ';';
		}
	    }

	    //Controller::fb($widString);
	    //Now, go back and combine the pieces (original becomes manipulated), to form the formal_arr (what we are seeking prior to insert into db..)
	    $formal_arr = array();
	    $formal_arr['columnsCount'] = "0";
	    foreach ($widgetsPos_arr as $k1 => $v1) {
		foreach ($v1 as $k2 => $v2) {
		    foreach ($v2 as $k3 => $v3) {
			if ($k3 == 'gadget_type') {
			    $formal_arr['widgetsPos'][$k1][$k2] = $v3;
			}
		    }
		}
	    }
	    $formal_arr['widString'] = $widString;

	    //Controller::fb($formal_arr);
	    //Last but not least, go ahead and serialize our array..
	    $formal_str = serialize($formal_arr);

	    unset($widgetsPos_arr); //Garbage collection..

	    $update_query = "UPDATE $this->_layoutsTable
			  SET `gadPlacement` = '$formal_str'
			   WHERE user_id = $uid
			    AND default_layout = {$defaultLayout}";
	    $command = Yii::app()->db->createCommand($update_query);

	    //Controller::fb($update_query);

	    $command->execute();
	    //COMMENTING OUT... construction here...
	    /*
	     */
	} //end  if( count($rs) > 0 ) ...


	/*
	 *  Abhinandan.
	 *   Re: Migration of 'dashboard_page.title'
	 *             TO
	 *                    'gadlay_layouts.gadPlacement'
	 *
	 */
	$pref = Yii::app()->db->createCommand(
			array(
			    'select' => $this->_userPrefFieldName,
			    'from' => $this->_tableName,
			    //'where' => $this->_userIdFieldName . " = :id",
			    'where' => $this->_userIdFieldName . " = :id AND " . $this->_defaultLayout . "=" . $defaultLayout,
			    'params' => array(":id" => $this->uid)
			)
		)->queryRow();

	return ($pref[$this->_userPrefFieldName] === null) ? '' : unserialize(stripslashes($pref[$this->_userPrefFieldName]));
    }

    /**
     * Read dashboard state from DB
     * @return <array>
     *
     *  Abhinandan. sample createCommand:
     *   'select title from dashboard_page where user_id=7'
     *
     *  i.) If we have a result set, parse it. Sample output:
     *
     *      $pref = array(
     *                 'columnsCount' => '0'
     *                 'widgetsPos' => array(
     *                                   '0' => array(
     *                                            '0' => 'Live_Status'
     *                                            '1' => 'Charts'
     *                                            '2' => 'Alerts'
     *                                            '3' => 'Meter_Gauge'
     *                                            '4' => 'System_Messages'
     *                                            '5' => 'AlarmBar'
     *                                            '6' => 'IdiotLights'
     *                                   )
     *                 ),
     *
     *                 'widString' => 'button-toggle:1; Live_Status:6; Charts:6; Alerts:2; Meter_Gauge:2; System_Messages:4; AlarmBar:6; IdiotLights:6; '
     *      )
     */
    /*
      private function getPreference()
      {
      $pref = Yii::app()->db->createCommand(
      array(
      'select' => $this->_userPrefFieldName,
      'from' => $this->_tableName,
      'where' => $this->_userIdFieldName . " = :id",
      'params' => array(":id" => $this->uid)
      )
      )->queryRow();

      return ($pref[$this->_userPrefFieldName] === null) ? '' : unserialize(stripslashes($pref[$this->_userPrefFieldName]));
      }
     */

    private function registerScript() {
	//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/test_Abhinandan.js');

	Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/dashboard.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/dashboard.js', CClientScript::POS_END);

	//  Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/ui/jquery.ui.widget.js',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/ui/jquery.ui.mouse.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/ui/jquery.ui.draggable.js', CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/ui/jquery.ui.droppable.js', CClientScript::POS_END);

	//Abhinandan. Added Jan31st 2013..
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/json2.js', CClientScript::POS_END);

	//Abhinandan. Added Feb20th 2013.. (sound effects)
	//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/nifty/niftyplayer.js');
	//Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl . '/assets/AGHcharts/highcharts.js' );
	//Yii::app()->clientScript->registerScriptFile( Yii::app()->baseUrl . '/assets/AGHcharts/modules/exporting.js' );


	$param['saveUrl'] = $this->createUrl('saveDash', array('userId' => $this->uid));
	$param['autoSave'] = $this->_autoSave;
	$param = CJavaScript::encode($param);
	$js = "jQuery().dashbrd($param);";        //jQuery().dashbrd({'saveUrl':'/helios_official_ug_logg_lang_dbcache_hs/dash/saveDash?userId=7','autoSave':true});

	Yii::app()->clientScript->registerScript(__CLASS__ . '#dashboard', $js);
    }

    /**
     * Reorder portlets
     * @param <array> $pref
     * @return <array>
     *
     *  12/17: Abhinandan.
     */
    private function applyUserPref($pref) {
	//Controller::fb($pref);
	//Controller::fb($this->_portlets);
	$tempArray = array();

	if ($pref != null) {        //Abhinandan. If gadPlacement was previously found to HAVE a valid settings string inside (its) field..

	    if (($this->_columns >= $pref['columnsCount']) && (count($pref['widgetsPos']) <= count($this->_portlets))) {   //Here!!..

		$widgets = $pref['widgetsPos'];  //3-dimensional Container holding the individual subarrays..

		foreach ($widgets as $keyColumn => $widgetColumn) {

		    if (!empty($widgetColumn)) {  //If the subarray is not empty..
			foreach ($widgetColumn as $keyRow => $widgetRow) {  //3rd dimension reporting for duty..
			    foreach ($this->_portlets as $key1 => $portlet) {  //$portlet = sub-array container..
				if ($portlet['id'] == $widgetRow) {               //Abhinandan. Need to rewrite better; right now it relies on matching value pairs from each array..bad.
				    //Abhinandan. Everything inside here appears to be working properly..
				    $tempArray[$keyColumn][$keyRow] = $portlet;    //Abhinandan. For every match, stuff our $tempArray with the appropriate sub-array (of $pref array)..
				    $this->_portlets[$key1]['indexed'] = true;
				    break;
				}
			    }
			    unset($portlet);  //Abhinandan. Not sure if this needs to be here or not..
			}
			unset($widgetRow); //Abhinandan. ""
		    } //end  if !empty($widgetColumn)..
		}//end outter foreach..
		unset($widgetColumn);  //Abhinandan. ""

		if (count($this->_portlets) > count($tempArray)) {

		    foreach ($this->_portlets as $portlet) {
			if (empty($portlet['indexed'])) {
			    //$tempArray[$keyColumn][++$keyRow] = $portlet;	    //Abhinandan. Commented out the culprit responsible for auto-adding gagdgets which were not originally in our profile..
			}
		    }
		}
	    }//end Here 12/17..
	}//end if($pref != null)..
	//Controller::fb($tempArray);
	//Controller::fb($this->_portlets);

	if (empty($tempArray)) {
	    //Abhinandan. Feb27th..
	    //Original
	    /*
	      $i = 0; // column
	      $j = 0; //row

	      foreach($this->_portlets as $portlet){
	      $tempArray[$i++][$j] = $portlet;
	      if($i >= $this->_columns)
	      {
	      $i = 0;
	      $j++;
	      }
	      }//end foreach..
	     */

	    //Controller::fb($tempArray);
	} //end if empty $tempArray..
	//Controller::fb($tempArray);
	return $tempArray;
    }

//end applyUserPref()..
}

?>