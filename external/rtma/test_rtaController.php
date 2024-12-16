<?php
//------------------------------------------------------------------------------
// rtaController.php - Script to write analysis results to the database
//------------------------------------------------------------------------------ 
error_reporting(0);
define("RFID_TAGGRP_ID",103);
define("CHL_QTY",30);

require_once ('../../../configfile.php');
require_once 'mysqlTools.php';
require_once 'rtaUtils.php';
require_once 'kalmanFilter.php';
require_once 'rtaConfigObj.php';

function getDBName(){
	
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
    $endTic = time();    // data record time for each configuration

    //echo "retrieveAllRTACfgs<br/>";
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

function report_status($write_status, $cfgObj, $start_time)
{
    echo "Config Description:  $cfgObj->DB_ID_string " . "</br>\n";
    if ($write_status == "success")
        echo "Write to database was successful " . "</br>\n";
    elseif ($write_status == "increment success")
        echo "Config count for $cfgObj->DB_ID_string was incremented by one " . "</br>\n";
//    elseif ($write_status == "inactive")
//        echo "Configuration $cfgObj->DB_ID_string is inactive.\n";
    elseif ($write_status == "create failure")
        echo "Create Error.  Unable to create the rta data table for configuration $cfgObj->DB_ID_string " . "</br>\n";
//    elseif ($write_status == "rename failure")
//        echo "Rename Error.  Unable to change the tablename to $cfgObj->DB_ID_string.\n";
    elseif ($write_status == "rtaProc failure")
        echo "rtaProc failed for configuration $cfgObj->DB_ID_string " . "</br>\n";
    elseif ($write_status == "serverAveraging failure")
        echo "multiple server Averaging failed for $cfgObj->DB_ID_string " . "</br>\n";
    elseif ($write_status == "counter state failure")
        echo "database counter state failed to update for $cfgObj->DB_ID_string " . "</br>\n";
    else
        echo "Write to database failed for configuration $cfgObj->DB_ID_string " . "</br>\n";
    $end_time = time();
    $telapsed = $end_time - $start_time;
    $etimeStamp = gmdate("M d Y H:i:s", $end_time);
    //echo "$etimeStamp : This configuration took $telapsed seconds " . "</br>\n";
}

function processRFIDTag() {
    global $db;
    //echo "processRFIDTag<br/>";  
    $sqlTagQueued 	  = "select * from rta_tag_index_queued  where tagGroupID= " . RFID_TAGGRP_ID;
    $queuedResult 	  = $db->query($sqlTagQueued);

    $totalQuedResult = array();

    while ($row = $queuedResult->fetch_assoc()) {
        //var_dump($row);
        
        $startTime 	= $row['LocalstartTime'];
        $endTime 	= $row['LocalendTime'];
        $tagName 	= $row['tagName'];
        $tagID 	= $row['tagID'];

        $totalQueuedSql = "SELECT round(sum(totalTons),2)as totalTons, avg(Ash) as Ash,avg(Moisture) as Moisture,avg(BTU) ".
                          "as BTU,avg(Sulfur) as Sulfur,'{$tagName}' as tagName, '{$tagID}' as tagID,LocalstartTime as w_start_timestamp, LocalendTime as w_end_timestamp  FROM `analysis_a1_a2_blend` ".
                          "WHERE LocalstartTime > '{$startTime}' AND LocalendTime <=  '{$endTime}' AND Al2O3 > 0  AND SiO2 > 0 AND Fe2O3 > 0";
        $totalQResult   = $db->query($totalQueuedSql);
        //echo $totalQueuedSql;
        
        $totalQueuedResult[] = $totalQResult->fetch_assoc();
    }
    
    return ($totalQueuedResult);    
}


function checkTolLimit($elemName,$elemVal) {
    global $db;
    $withinRange = 0;
    //echo "checkTolLimit $elemName,$elemVal<br/>";  
    $spTolChk 	  = "select * from wcl_rfid_set_points  where sp_name= '{$elemName}' AND sp_status=1 LIMIT 1";
    $spTolChRes 	  = $db->query($spTolChk);

    while ($row = $spTolChRes->fetch_assoc()) {
        //var_dump($row);
        
        $sp_name 	    = $row['sp_name'];
        $sp_value_num 	= $row['sp_value_num'];
        $sp_tol 	    = $row['sp_tol'];
        $sp_status 	    = $row['sp_status'];

        if($elemName == $sp_name && ($elemVal >= round($sp_value_num - $sp_tol,2)) && ($elemVal <= round($sp_value_num + $sp_tol,2))) {
            $withinRange = 1;
        }
    }
    
    return ($withinRange);    
}

function getTripInfo($tagId) 
{
    global $db;
    $tripInfoAr = array();
    
    //echo "getTripInfo $tagId<br/>";  
    if(!isset($tagId)){
        echo "Problem loading Tag Settings\n<br/>";
    }
    
    $spTolChk 	  = "select * from wcl_truckinfo where w_tripID IN (select DISTINCT wcl_map_trId from wcl_trucktagmap where wcl_map_tagId= '{$tagId}')";
    $spTolChRes   = $db->query($spTolChk);
    //echo $spTolChk;
    
    if($row = $spTolChRes->fetch_assoc()) {
        $tripInfoAr = $row; 
    }
    
    return $tripInfoAr;
}

function varCleanUp($qArr) {

    $qArr["w_trigType"] = "SAB-QC";
    $qArr["w_ash"] = $qArr["Ash"];
    $qArr["w_ash_tol_limit"] = $qArr["Ash_tol_limit"];
    $qArr["w_moisture"] = $qArr["Moisture"];
    $qArr["w_moisture_tol_limit"] = $qArr["Moisutre_tol_limit"];
    $qArr["w_sulfur"] = $qArr["Sulfur"];
    $qArr["w_sulfur_tol_limit"] = $qArr["Sulfur_tol_limit"];
    $qArr["w_gcv"] = $qArr["BTU"];
    $qArr["w_gcv_tol_limit"] = $qArr["BTU_tol_limit"];
    $qArr["w_total_tons"] = $qArr["totalTons"];
    
    unset($qArr["tagName"]);
    unset($qArr["tagID"]);
    unset($qArr["Ash"]);
    unset($qArr["Moisture"]);
    unset($qArr["Sulfur"]);
    unset($qArr["BTU"]);
    unset($qArr["Ash_tol_limit"]);
    unset($qArr["Moisutre_tol_limit"]);
    unset($qArr["Sulfur_tol_limit"]);
    unset($qArr["BTU_tol_limit"]);
    unset($qArr["w_timestamp"]);
    unset($qArr["totalTons"]);
    
    return $qArr;
}

function adjustEndTime($tagId, $tagName) 
{
    global $db;
    //echo "adjustEndTime $tagId, $tagName<br/>";  
    if(!isset($tagId)){
        echo "Problem loading Tag Settings\n<br/>";
    }
    
    $newEndTime = date("Y-m-d H:i:s");		
    $updQuery 	= "UPDATE rta_tag_index_queued ".
                "SET LocalendTime='{$newEndTime}' WHERE tagID='{$tagId}' AND tagName='{$tagName}'";
    //echo ($updQuery);
    $result 	= $db->query($updQuery);
    //echo "UPDATED tag ($tagId, $tagName) to new EndTime = '{$newEndTime}' \n<br/>";
    return 1;
}

// Create an database connection object using the username, host, and password for a particular user
$db = database_connect('localhost', 'root', 'mysqlRoot', "wonder_sabia_v1_m2_1");

if (!$db)
    exit(1);

if ($result = $db->query("SELECT DATABASE()")) {
    $row = $result->fetch_row();
    echo "using DB to write rtma data : ".$row[0]." </br>\n";
    $result->close();
}

// writePhysicalData() - single physical detectors
// writeDerivedData() - any calculation involving involving a single RTA table preferably a physical detector (filtering, source decay, etc.)
// writeAveragedData() - any average between RTA tables including detectors, and remote analyzers
// processTags() - process any tagged data that is ready

$rtaCfgsArray = retrieveAllRTACfgs($db);    // Array of all the existing RTA configurations in object form from rta_config_master table
//var_dump($rtaCfgsArray);
/*
if (!$rtaCfgsArray)
    exit();

foreach ($rtaCfgsArray as $rtaCfgObj)
{
    $start_time = time(); // used only for tracking how long a given configuration takes
    $write_status = $rtaCfgObj->processConfig($db);
    report_status($write_status, $rtaCfgObj, $start_time);
}

*/
$elemAr = array("Ash","Moisutre","Sulfur","BTU");

$jsonTagData = array();
$queuedTagsAr = processRFIDTag();
foreach($queuedTagsAr as $qTag) {
    $tripAr = getTripInfo($qTag["tagID"]);
    if(count($tripAr) <=0) continue;
    
    if($tripAr["w_chQty"] <= 0) 
        $tripAr["w_chQty"] = CHL_QTY;
    
    if($qTag["totalTons"] >= $tripAr["w_chQty"]) {
        //CloseTag
        if(adjustEndTime($qTag["tagID"],$qTag["tagName"])) {
            foreach($elemAr as $elem) {
                $qTag[$elem . "_tol_limit"] = checkTolLimit($elem,$qTag[$elem]);
            }//foreach
            
            
            if(count($tripAr) > 0) {
                $qTag = array_merge($tripAr, $qTag);
                
                $qTag = varCleanUp($qTag);
                
                $outputData["evntQCTripResultConfirm"] = $qTag;                
            }
        }
    }//if
}
var_dump($outputData);
echo json_encode($outputData); exit();

$time = date("Y-m-d H:i:s");
// Check to see if any tags have finished 
$tagsArray = retrieveAllObjectsFromMYSQL($db, 'tagID', QUEUED_TAG_INDEX_TABLE, 'rtaTagObj', 'status', "queued' AND tagName NOT LIKE 'AutoTag_%' AND LocalendTime <= '$time");

if (empty($tagsArray)) {
    echo "No tags to process<br/>\n";
} else {

    foreach ($tagsArray as $tagDataIndx => $tagDataObj) {
        
        $tagDataObj->processTag($db);
    }
}
echo "exiting rtaController<br/>\n";

