<?php
//------------------------------------------------------------------------------
// rtaController.php - Script to write analysis results to the database
//------------------------------------------------------------------------------ 

error_reporting(E_ALL);

define("RFID_TAGGRP_ID",103);
define("CHL_QTY",30);
define("FILE_LOG",0);

//define("UNLOAD_CONFIRM_JSON_TAG", "UnloadConfirmCbaList");        //OLD
define("UNLOAD_CONFIRM_JSON_TAG", "evntQCTripResultConfirm");

chdir( dirname ( __FILE__ ) ); 

define('__SERVER__', 'live'); 
//define('__SERVER__', 'testserver_166'); 

if (__SERVER__ != 'live') {
//166 for test
    define('__WWW_PATH__', '../');
    define('__HELIOS_DB__', '../');
} else {
//for live
    define('__WWW_PATH__', '../../../');
    define('__HELIOS_DB__', '../../protected/config/');
}

require_once (__WWW_PATH__.'defines.php');
require_once (__WWW_PATH__.'configfile.php');
require_once 'mysqlTools.php';

require_once 'rtaUtils.php';
require_once 'kalmanFilter.php';
require_once 'rtaConfigObj.php';

require_once 'tagUtility.php';


$sltime = date("Y-m-d H:i:s");

$fbuftxt = "\n$sltime Rta Controller started running!\n ";
//for helios
$heliosdb_connection = require (__HELIOS_DB__.'db.php');
global $heliosdb_connection;

function getDBName(){
	global $fbuftxt;
    
    if (RTA_PRODUCT_NAME != "helios")
        return RTA_DB_NAME;

    $dbstr = $GLOBALS['heliosdb_connection'];

    $dbhostAr = explode('=', $dbstr['connectionString']);
    $dbtypeAr = explode(':', $dbhostAr[0]);

    $dbtype = $dbtypeAr[0];
    $dbhost = $dbtypeAr[1];
    $dbName = $dbhostAr[2];
    return $dbName;
}

function connectDB() {
	global $fbuftxt;
    //$dbstr = $GLOBALS['dbLocal']['db'];
    $dbstr = $GLOBALS['heliosdb_connection'];

    $dbhostAr = explode('=', $dbstr['connectionString']);
    $dbtypeAr = explode(':', $dbhostAr[0]);

    $dbtype = $dbtypeAr[0];
    $dbhost = $dbtypeAr[1];
    $dbName = $dbhostAr[2];

    $dbhost = "localhost"; //temp
    $dbusername = $dbstr['username'];
    $dbpassword = $dbstr['password'];

    $con = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbName);
    // Check connection
    if (mysqli_connect_errno()) {
        echo("Failed to connect to MySQL: " . mysqli_connect_error());
        $fbuftxt .= "Failed to connect to MySQL: ";
        //exit(1);
    }

    // echo("Connected to DB : $dbName <br/>" . mysqli_connect_error());

    return $con;
}

function disconnectDB($con) {
    mysqli_close($con);
//    $log->traceSkipDB("disconnectDB exit");
}


function retrieveAllRTACfgs($db)
{
	global $fbuftxt;
    $endTic = time();    // data record time for each configuration
    $rtaConf = new ConfigFile();
    $rtaConf->load (RTA_CONF); // readable
    $rtaConf->setPath( "/" . "rtad" );

    // Must retrieve and do the physical configurations first, then derived, then averaged.  This is accomplished by sorting the db results based on rtaConfigTable
    $masterCfgArray = retrieveAllObjectsFromMYSQL($db, 'rtaMasterID', RTA_MASTER_CONFIG_TABLE, 'rtaMasterConfig', 'DBwriteActive', '1', 'ASC',NULL,'=', 'rtaConfigTable'); // Array of all the existing RTA configurations set to 'Active';
    if (empty($masterCfgArray))
        return false;
    for ($i=0; $i<count($masterCfgArray); $i++)
    {
        $rtaCfgArray[$i] = $masterCfgArray[$i]->retrieveConfigClass($db);
        if ($rtaCfgArray[$i] === false)
            unset ($rtaCfgArray[$i]);

        $rtaCfgArray[$i]->endTic = $endTic;
        $rtaCfgArray[$i]->rtaConf = $rtaConf;

        if ($rtaCfgArray[$i]->rtaConfigTable == RTA_PHYSICAL_TABLE_NAME)
        {
            // The RTA stores the detector name differently than analysisObj gives it to us so here we just map the differences
            if ($rtaCfgArray[$i]->detectorID == 'Detector 1')
                $rtaCfgArray[$i]->detector_physical_ID = 'datad1';
            elseif ($rtaCfgArray[$i]->detectorID == 'Detector 2')
                $rtaCfgArray[$i]->detector_physical_ID = 'datad2';
            elseif ($rtaCfgArray[$i]->detectorID == 'Average')
                $rtaCfgArray[$i]->detector_physical_ID = 'average';
        }
    }

    return $rtaCfgArray;
}


function report_status($write_status, $cfgObj)
{
	global $fbuftxt, $processTags;
    $stime = time();
    $ltime = date("Y-m-d H:i:s");
    echo "$ltime: Config Description:  $cfgObj->DB_ID_string " . "</br>\n";
    $fbuftxt .= "$ltime: Config Description:  $cfgObj->DB_ID_string " . "</br>\n";
    
    if ($write_status == "success" || $write_status) {
        echo "$ltime: Write to database was successful " . "</br>\n";
        $fbuftxt .= "$ltime: Write to database was successful " . "</br>\n";
    }elseif ($write_status == "increment success") {
        echo "$ltime: Config count for $cfgObj->DB_ID_string was incremented by one " . "</br>\n";
//    elseif ($write_status == "inactive")
//        echo "Configuration $cfgObj->DB_ID_string is inactive.\n";
        $fbuftxt .= "$ltime: Config count for $cfgObj->DB_ID_string was incremented by one " . "</br>\n";
    }elseif ($write_status == "create failure") {
        echo "$ltime: Create Error.  Unable to create the rta data table for configuration $cfgObj->DB_ID_string " . "</br>\n";
//    elseif ($write_status == "rename failure")
//        echo "Rename Error.  Unable to change the tablename to $cfgObj->DB_ID_string.\n";
        $fbuftxt .= "$ltime: Create Error.  Unable to create the rta data table for configuration $cfgObj->DB_ID_string " . "</br>\n";
    }elseif ($write_status == "rtaProc failure") {
        echo "$ltime: rtaProc failed for configuration $cfgObj->DB_ID_string " . "</br>\n";
        $fbuftxt .= "$ltime: rtaProc failed for configuration $cfgObj->DB_ID_string " . "</br>\n";
    }elseif ($write_status == "serverAveraging failure") {
        echo "$ltime: multiple server Averaging failed for $cfgObj->DB_ID_string " . "</br>\n";
        $fbuftxt .= "$ltime: multiple server Averaging failed for $cfgObj->DB_ID_string " . "</br>\n";
    }
    elseif ($write_status == "counter state failure") {
        echo "$ltime: database counter state failed to update for $cfgObj->DB_ID_string " . "</br>\n";
        $fbuftxt .= "$ltime: database counter state failed to update for $cfgObj->DB_ID_string " . "</br>\n";
    }
    else {
        echo "$ltime: Write to database failed for configuration $cfgObj->DB_ID_string " . "</br>\n";
        $fbuftxt .= "$ltime: Write to database failed for configuration $cfgObj->DB_ID_string " . "</br>\n";
    }
    
    $etime = time();
    $ltime = date("Y-m-d H:i:s",strtotime($etime));
    
    $telapsed = $etime - $stime;
    //echo "$etimeStamp : This configuration took $telapsed " . "</br>\n";
    //$fbuftxt .= "$etimeStamp : This configuration took $telapsed " . "</br>\n";
}

// Create an database connection object using the username, host, and password for a particular user
if (RTA_PRODUCT_NAME != "helios")
    $db = database_connect('localhost', 'root', 'mysqlRoot', RTA_DB_NAME);
else
    $db = connectDB(); //for helios

if (!$db)
    exit(1);

/*
if ($result = $db->query("SELECT DATABASE()")) {
    $row = $result->fetch_row();
    echo "using DB to write rtma data : ".$row[0]." </br>\n";
    $fbuftxt .= "using DB to write rtma data : ".$row[0]." </br>\n";
    $result->close();
}
*/

// writePhysicalData() - single physical detectors
// writeDerivedData() - any calculation involving involving a single RTA table preferably a physical detector (filtering, source decay, etc.)
// writeAveragedData() - any average between RTA tables including detectors, and remote analyzers
// processTags() - process any tagged data that is ready

$rtaCfgsArray = retrieveAllRTACfgs($db);    // Array of all the existing RTA configurations in object form from rta_config_master table
if (!$rtaCfgsArray)
    exit();

foreach ($rtaCfgsArray as $rtaCfgObj)
{
    $write_status = $rtaCfgObj->processConfig($db);
    report_status($write_status, $rtaCfgObj);
}

$ltime = date("Y-m-d H:i:s");
// Check to see if any tags have finished 
$tagsArray = retrieveAllObjectsFromMYSQL($db, 'tagID', QUEUED_TAG_INDEX_TABLE, 'rtaTagObj', 'status', "queued' AND tagName NOT LIKE 'AutoTag_%' AND LocalendTime <= '$ltime");
if (empty($tagsArray)) {
    echo "$ltime No tags to process<br/>\n";
    $fbuftxt .= "$ltime No tags to process<br/>\n";
} else {

    foreach ($tagsArray as $tagDataIndx => $tagDataObj) {
        $tagDataObj->processTag($db);
    }
}

$ltime = date("Y-m-d H:i:s");
//Send Curl Output
$evntUnLoadConfirmJson = getUnLoadConfirm();

if($evntUnLoadConfirmJson && strlen($evntUnLoadConfirmJson)>5) {
	echo "$ltime: Sending Curl Post<br/>\n";
	$fbuftxt .= "$ltime: Sending Curl Post<br/>\n";
	$fbuftxt .= "$ltime: $evntUnLoadConfirmJson<br/>\n";
    
        sendCurlPostData($evntUnLoadConfirmJson);

        $ltime = date("Y-m-d H:i:s");
        echo "$ltime: Exiting Curl Post<br/>\n";
}

$ltimeDiff = round(strtotime($ltime) - strtotime($sltime),2);

$fbuftxt .= "Exiting rtaController<br/>\n\n\n";

if(FILE_LOG) {
    $myfile = fopen("rtdlog.txt", "a");
    fwrite($myfile, $fbuftxt);
    fclose($myfile);
}
