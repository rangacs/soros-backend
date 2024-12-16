#!/bin/sh
<?php

define("PRODUCT_NAME", "helios"); /* IMPORTANT for helios */     // define either rtma or helios

$debugFlag = 0;
//error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
error_reporting(E_ALL);
//error_reporting(1);
//-----------------------------------------------------------------------------------------
// Analyzer system status page
//----------------------------------------------------------------------------------------
// SABIA Proprietary
// Copyright (c) 2002,2007,2009 by SABIA, Inc.
//
//! \file
//! Analyzer status display page.  Displays system and per-detector status in a table.
//! \todo It would be good to replace this page with a javascript page that refreshes
//! less obtrusively.
//!
require_once '/var/www/html/defines.php';
require_once '/var/www/html/configfile.php';
require_once 'crawl_statusObj.php';

//date_default_timezone_set('Asia/Shanghai');
//------------------------------------------------------------------------------
// status_pg_main - renders system status page
//------------------------------------------------------------------------------
//! Emits system status page.
//for helios
$heliosdb_connection = require '/var/www/html/helios1/protected/config/db.php';
global $heliosdb_connection;

function analysis_status_to_db($dbCon) {

    $anConf = new ConfigFile();
    $anConf->load(ANALYZER_CONF);
    $sst = new SystemStatus();
    $statSuccess = $sst->getStatus();

    // td modifier classes from stylesheet to apply to cells according to their exception states
    $excClass = array("", "statusInfo", "statusWarn", "statusErr");

    insertStatusOutputEntry($outArray, "title", "Analyzer State");

    $dfm = 'd M Y H:i:s T';
    $tm = time();
    $timeStr = date($dfm, $tm);
    $timeStrGMT = gmdate($dfm, $tm);
    // Summary params
    insertStatusOutputEntry($outArray, "Analyzer Time (Local)", $timeStr);
    insertStatusOutputEntry($outArray, "Analyzer Time (GMT)", $timeStrGMT);
    insertStatusOutputEntry($outArray, "Detectors", $sst->numConfiguredDetectors);

    if ($sst->isTaggedSampleActive())
        $tagstr = "Recording: " . $sst->tagSampleID;
    else
        $tagstr = "Idle";
    insertStatusOutputEntry($outArray, "Tag Sample State", $tagstr);

    if (!$statSuccess) {
        insertStatusOutputEntry($outArray, "Current analysis", "FAIL - " . $sst->analysisErrorStrs[0], $excClass[STATUS_EXCEPTION_WARN]);
    } else {
        insertStatusOutputEntry($outArray, "Current analysis", "OK");
    }

    //Array to hold detectors information
    $detArray = array();

    // Per-detector params (some only valid if the status object returned success from its analyze operation)
    foreach ($sst->detectorIDArray as $detID) {
        $tempDetArray = array();

        $detstat = $sst->detectorStatus[$detID];
        insertStatusOutputEntry($tempDetArray, "title", $detstat->detectorName->getValue());
        // Results that don't depend on having a successful analysis
        //ABHI2172014
        //$detstat->pmtControlMode->addToOutputArray( $tempDetArray, $excClass );

        $detstat->pmtControlState->addToOutputArray($tempDetArray, $excClass);
        $detstat->detectorID->addToOutputArray($tempDetArray, $excClass);
        $detstat->daemonRunning->addToOutputArray($tempDetArray, $excClass);
        $detstat->useResults->addToOutputArray($tempDetArray, $excClass);
        $detstat->analysisResponseSet->addToOutputArray($tempDetArray, $excClass);
        $detstat->alignmentResponseSet->addToOutputArray($tempDetArray, $excClass);
        $detstat->analyzeCmdIssued->addToOutputArray($tempDetArray, $excClass);

        if ($statSuccess) {
            $detstat->detectorTemp->addToOutputArray($tempDetArray, $excClass, 1);
            $detstat->countRateCpm->addToOutputArray($tempDetArray, $excClass, 0);
            $detstat->countRateCpmAlignment->addToOutputArray($tempDetArray, $excClass, 0);

            // customize keywords for alignedOK boolean
            insertStatusOutputEntry($tempDetArray, $detstat->alignedOK->getItemName_ic(), $detstat->alignedOK->getValue() ? "Aligned" : "Not aligned", $excClass[$detstat->alignedOK->getExceptionState()]);

            $detstat->goodDataSecs->addToOutputArray($tempDetArray, $excClass, 0);
            $detstat->h_fwhm->addToOutputArray($tempDetArray, $excClass, 2);
            $detstat->h_rawChan->addToOutputArray($tempDetArray, $excClass, 3);

            //ABH2172014
            /*
              $detstat->alignmentGain->addToOutputArray( $tempDetArray, $excClass, 4 );
              $detstat->alignmentOffset->addToOutputArray( $tempDetArray, $excClass, 4 );
              $detstat->pmtVoltage->addToOutputArray( $tempDetArray, $excClass, 3 );
             */
            $detstat->HVReadback->addToOutputArray($tempDetArray, $excClass, 0);
            $detstat->alignmentChisqr->addToOutputArray($tempDetArray, $excClass, 3);
            $detstat->analysisChisqr->addToOutputArray($tempDetArray, $excClass, 3);

            //ABH2172014
            /*
              $detstat->coarseDAC->addToOutputArray( $tempDetArray, $excClass, 0 );
              $detstat->fineDAC->addToOutputArray( $tempDetArray, $excClass, 0 );
             */
        }//if success
        array_push($detArray, $tempDetArray);
    }

    insertStatusOutputEntry($outArray, "title", "PLC Subsystem Status");
    $plcstat = $sst->plcStatus;
    $plcstat->plcdInstalled->addToOutputArray($outArray, $excClass);
    if ($sst->plcStatus->bPlcdInstalled) {
        // Note that plcStatus->plcdInstalled is a non-bounded keyword, and daemonRunning is a boundedBoolean item
        // that only displays if PLCD is installed and/or running
        $plcstat->daemonRunning->addToOutputArray($outArray, $excClass);
        $plcstat->plcdStateSummary->addToOutputArray($outArray, $excClass);
        // Only output other items if daemonRunning is "Running"
        if ($plcstat->daemonRunning->getValue() == "Running") {
            $plcstat->beltSpeed->addToOutputArray($outArray, $excClass, 0);
            $plcstat->massFlow->addToOutputArray($outArray, $excClass, 0);
        }
    }


    // -----------------------------------------------------------------------------------
    // System Parameters
    // -----------------------------------------------------------------------------------

    insertStatusOutputEntry($outArray, "title", "System Parameters");

    // System SW version info
    $phpVersion = phpversion();
    $apacheVersion = `/usr/sbin/httpd -v`;
    $RHVersion = trim(file_get_contents("/etc/redhat-release"));

    // Get raid status summary lines and colorize evil ones
    $raidGood = getShortRaidStatus($statlines);
    $raidSummary = "";
    $raidClass = $excClass[STATUS_EXCEPTION_NONE];
    if (!empty($statlines)) {
        foreach ($statlines as $statline) {
            if (( preg_match('/DEGRADED/', $statline) != 0 ) or ( preg_match('/INOPERABLE/', $statline) != 0 )) {
                $raidClass = $excClass[STATUS_EXCEPTION_ERROR];
            }
            $raidSummary .= $statline . "<br>";
        }
    } else {
        $raidSummary = "No RAID subsystem";
    }
    insertStatusOutputEntry($outArray, "RAID Summary", $raidSummary, $raidClass);

    $RAIDStatusArray = getDetailedRAIDStatus();
    if ($RAIDStatusArray === FALSE) {
        $raidDetails = "No RAID subsystem";
    } else if (!empty($RAIDStatusArray)) {
        $rsstr = "";
        $first = true;
        foreach ($RAIDStatusArray as $rs) {
            if (!$first)
                $rsstr .= "\n";
            else
                $first = false;
            $rsstr .= $rs;
        }
        // emit RAID health details in fixed pitch font
        $raidDetails = "<pre>" . $rsstr . "</pre>";
    }
    else {
        $raidDetails = "Unknown";
    }

    insertStatusOutputEntry($outArray, "RAID Details", $raidDetails, $raidClass);

    // Get disk usage stats
    //! \todo Put disk usage analysis / checking into statusObj code
    $df = `df -h`;
    $df = "<pre>" . $df . "</pre>";
    insertStatusOutputEntry($outArray, "Disk Usage", $df);
    insertStatusOutputEntry($outArray, "PHP Version", $phpVersion);
    insertStatusOutputEntry($outArray, "Apache Version", $apacheVersion);
    insertStatusOutputEntry($outArray, "OS Version", $RHVersion);

    //
    //  Display license status items
    //
    insertStatusOutputEntry($outArray, "title", "License Status");

    $sst->licenseFilePath->addToOutputArray($outArray, $excClass);
    $sst->licenseEndDateStr->addToOutputArray($outArray, $excClass);
    $sst->licenseDaysLeft->addToOutputArray($outArray, $excClass);
    $sst->licenseServerType->addToOutputArray($outArray, $excClass);

    $tableAttribs = "cellpadding=3 border=2 bordercolor=\"#0000FF\"";
    $titleClass = "statusTabHdr";
    $nameClass = "statusTabKey";
    emitStatusTable($outArray, $detArray, $tableAttribs, $titleClass, $nameClass, $dbCon);
}

// analysis_status_to_db

function write_plcd_to_db($dbCon) {
    global $debugFlag;
    $PLCD_STATUS = "/usr/local/sabia-ck/plcd.status";

    if ($debugFlag)
        echo "We are in write_plcd_to_db;";

    $rawMixParams = array(
        "MASTER_CONTROL_MODE" => "RawMixParam13", /* CONTROL MODE			 */
        "src_1_m" => "RawMixParam1", /* 1 Feeder Stpnt		 */
        "src_1_sp" => "RawMixParam2", /* 1 Feeder Rate		 */
        "src_1_r" => "RawMixParam3", /*  1  Feeder Running	 */
        "src_2_m" => "RawMixParam14", /* 2 Feeder Stpnt		 */
        "src_2_sp" => "RawMixParam15", /* 2 Feeder Rate		 */
        "src_2_r" => "RawMixParam16", /*  2 Feeder Running	 */
        "src_3_m" => "RawMixParam7", /* 3 Feeder Stpnt		 */
        "src_3_sp" => "RawMixParam8", /* 3 Feeder Rate		 */
        "src_3_r" => "RawMixParam9", /* 3 Feeder Running		 */
        "src_4_m" => "RawMixParam17", /* 4  Feeder Stpnt		 */
        "src_4_sp" => "RawMixParam18", /* 4  Feeder Rate		 */
        "src_4_r" => "RawMixParam19", /* 4 Feeder Running		 */
        "src_5_m" => "RawMixParam10", /* 4  Feeder Stpnt		 */
        "src_5_sp" => "RawMixParam11", /* 4  Feeder Rate		 */
        "src_5_r" => "RawMixParam12", /* 4 Feeder Running		 */
        "src_6_m" => "RawMixParam4", /* 4  Feeder Stpnt		 */
        "src_6_sp" => "RawMixParam5", /* 4  Feeder Rate		 */
        "src_6_r" => "RawMixParam6", /* 4 Feeder Running		 */
    );
    $rmSettingsArray = array(
        "src_1_r" => "SRC_1_STATUS", /*  Feeder Running		 */
        "src_2_r" => "SRC_2_STATUS", /*  Feeder Running		 */
        "src_3_r" => "SRC_3_STATUS", /*  Feeder Running		 */
        "src_4_r" => "SRC_4_STATUS", /*  Feeder Running		 */
        "src_5_r" => "SRC_5_STATUS", /*  Feeder Running		 */
        "src_6_r" => "SRC_6_STATUS", /*  Feeder Running		 */
            //		"MASTER_CONTROL_MODE"  => "MASTER_CONTROL_MODE", /* Master Contorl Mode */				
    );
    //belt_speed_fpm /* Belt Scale Input	*/
    //mass_flow_tph /* Belt Speed Input	*/                           
    $bSpeed = readSabiaFile($PLCD_STATUS, "plcd_status", "belt_speed_fpm");
    $mFlow = readSabiaFile($PLCD_STATUS, "plcd_status", "mass_flow_tph");

    $statusArray[strtoupper("belt_speed_fpm")] = $bSpeed;
    $statusArray[strtoupper("mass_flow_tph")] = $mFlow;

    if ($debugFlag)
        echo $bSpeed . "<br/>";
    if ($debugFlag)
        echo $mFlow . "<br/>";

    $arrlength = count($rawMixParams);
    $colsStr = "";
    $valsStr = "";
    $valsArray = array();
    $spvalsArray = array();
    $sVal = 0;
    $pVal = 0;

    foreach ($rawMixParams as $col => $val) {


        if ($col == "MASTER_CONTROL_MODE") {
            $tVal = readSabiaFile($PLCD_STATUS, "RawMix", $val);
            $statusArray[$col] = (int) ABS($tVal);
            $statusArray["MASTER_CONTROL_MODE"] = (int) ABS($tVal);
            continue;
        }
        $colsStr .= "`{$col}`,";

        $tVal = readSabiaFile($PLCD_STATUS, "RawMix", $val);

        if ($tVal >= 65520)
            $tVal = 0;

        $valsStr .= "'" . ROUND(ABS($tVal), 2) . "',";

        if ((substr($col, -2) == "_r") && (isset($rmSettingsArray[$col]))) {

            $statusArray[$rmSettingsArray[$col]] = ABS($tVal);
        } elseif ((substr($col, -2) == "_m")) {
            $sVal += $tVal;

            array_push($valsArray, $tVal);
        } elseif ((substr($col, -3) == "_sp")) {
            //$spVal += $tVal;

            array_push($spvalsArray, $tVal);
        }
    }

    foreach ($valsArray as $i => $v) {
        $t = $valsArray[$i];
        $valsArray[$i] = round((($t / $sVal) * 100), 2);
    }

    $colsStr = substr($colsStr, 0, -1);
    $valsStr = substr($valsStr, 0, -1);

    $pquery = "SELECT product_id FROM rm_product_profile where default_profile = 1";
    $pId = 0;
    $result = mysqli_query($dbCon, $pquery);

    if (($result)) {
        if ($row = $result->fetch_array()) {
            $pId = $row["product_id"];
        }
    }
    $setPointFeedRatesCorrFlag = 0;
    $spCorQuery = "SELECT * FROM `rm_settings` WHERE varName = 'setPointFeedRateCorrection'";
    if ($debugFlag)
        echo $spCorQuery . "\n";
    $rresult = mysqli_query($dbCon, $spCorQuery);
    if ($rrow = $rresult->fetch_array()) {
        $setPointFeedRatesCorrFlag = $rrow["varValue"];
    } else
        $setPointFeedRatesCorrFlag = 0;
    $dateTime = date("Y-m-d H:i:s");
    //Insert into feeder inputs
    $inQuery = "INSERT INTO `rm_source_feeder_inputs` (`src_id_fk`, {$colsStr}, `LocalendTime`) VALUES(1,{$valsStr},'{$dateTime}')";

    if ($debugFlag)
        echo $inQuery;

    $result = mysqli_query($dbCon, $inQuery);
    $srcId = 0;

    //Update rm_settings
    foreach ($statusArray as $col => $val) {
        $upQuery = "UPDATE `rm_settings` set `varValue` ='{$val}' WHERE varName = '{$col}'";
        if ($debugFlag)
            echo $upQuery . "\n";
        $result = mysqli_query($dbCon, $upQuery);

        if (($pos = strpos($col, "STATUS")) > 0) {
            $srcId++;
            if ($setPointFeedRatesCorrFlag)
                $upsrcQuery = "UPDATE `rm_source` set `src_status_mode` ={$val}, src_measured_feedrate=" . $spvalsArray[$srcId - 1] . ", src_actual_feedrate=" . $valsArray[$srcId - 1] . " WHERE src_id = {$srcId} AND product_id=$pId ";
            else
                $upsrcQuery = "UPDATE `rm_source` set `src_status_mode` ={$val}, src_measured_feedrate=" . $valsArray[$srcId - 1] . ", src_actual_feedrate=" . $valsArray[$srcId - 1] . " WHERE src_id = {$srcId} AND product_id=$pId ";
            if ($debugFlag)
                echo $upsrcQuery . "\n";
            $result = mysqli_query($dbCon, $upsrcQuery);
        }
    }
    echo "PLCD, Rawmix info written to DB rm_source_feeder_inputs, rm_settings, rm_source\n";

    include_once "cronClass.php";
    $nLine = "*/XXX * * * * php /var/www/html/RHEA/rhea.php XXX \n";

    $newCron = new Crontab();

    $autoQuery = "SELECT * From rm_settings where varName like 'CRON%' ";
    $result = mysqli_query($dbCon, $autoQuery);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $cronAr[$row["varName"]] = $row["varValue"];
        }
    }

    if ($cronAr["CRON_CHANGE_FLAG"] == 1 && in_array($cronAr["CRON_RUN_TIME"], array(1, 3, 5, 10, 15))) {
        $newCron->removeAllJobs();

        $nLine = str_replace("XXX", $cronAr["CRON_RUN_TIME"], $nLine);
        $newCron->addJob($nLine);
        echo "Adding New Jobs to Crontab \n";
        $cArray = $newCron->getJobs();

        $autoQuery = "UPDATE rm_settings SET varValue=0 WHERE varName = 'CRON_CHANGE_FLAG' ";
        $result = mysqli_query($dbCon, $autoQuery);

        //$newCron->addJob("*/2 * * * * php /var/www/html/RHEA/rstarve.php 2                >/dev/null 2>&1");
        $newCron->addJob("*/1 * * * * php /var/www/html/crawl_status.php 1");
        $newCron->addJob("*/1 * * * * /usr/local/sabia-ck/bin/mastercron.sh 1");
        $newCron->addJob("*/5 * * * * /usr/local/sabia-ck/bin/mastercron.sh 5");
        $newCron->addJob("3 */1 * * * /usr/local/sabia-ck/bin/mastercron.sh hourly");
        $newCron->addJob("30 0 * * * /usr/local/sabia-ck/bin/mastercron.sh daily");
        $newCron->addJob("0 1 * * 7 /usr/local/sabia-ck/bin/mastercron.sh weekly");
    }
}

function getDBName() {

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

/*
  $dbhost 	= "localhost";
  $dbusername = "root";
  $dbpassword = "mysqlRoot" ;
  $dbName 	= "sabia_helios_v1_m2_0";

  $dbCon=mysqli_connect($dbhost,$dbusername,$dbpassword,$dbName);

  // Check connection
  if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
  }
 */
// Create an database connection object using the username, host, and password for a particular user
if (PRODUCT_NAME != "helios") {
    $dbName = "sabia_helios_v1_m2_0";
    $dbCon = database_connect('localhost', 'root', 'mysqlRoot', $dbName);
} else
    $dbCon = connectDB(); //for helios

if (!$dbCon)
    exit(1);


//echo "DB Connection Successful!";

//Write Analyzer Status information
analysis_status_to_db($dbCon);

//Write PLCD Information to DB
write_plcd_to_db($dbCon);


//write_feeds_to_plcd($dbCon);

mysqli_close($dbCon);
// No newline after PHP close tag!
?>
