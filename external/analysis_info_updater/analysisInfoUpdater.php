#!/bin/sh
<?php
//add this to mastercron.sh
//$php $webroot/Helios/external/analysis_info_updater/analysisInfoUpdater.php  >> /var/www/html//Helios/tmp/runlog/analysis-info-cron.html


chdir( dirname ( __FILE__ ) ); 
error_reporting(0);
//error_reporting(E_ALL);

define('__SERVER__', 'live'); 
//define('__SERVER__', 'testserver_166'); 

$debugFlag = 0;

chdir( dirname ( __FILE__ ) ); 
define("PRODUCT_NAME", "helios"); /* IMPORTANT for helios */     // define either rtma or helios

if (__SERVER__ != 'live') {
//166 for test
// /var/www/html/1dev/HELIOS1/pawan/helios1/external/crawl
    define('__WWW_PATH__', '/var/www/html/v2.0/');
//    define('__HELIOS_DB__', '../');
//    define('__WWW_PATH__', '../../../../../../v2.0/');
    define('__HELIOS_DB__', '../../../../../../');    
} else {
//for live
    define('__WWW_PATH__', '../../../');
    define('__HELIOS_DB__', '../../protected/config/');
}

require_once (__WWW_PATH__.'defines.php');
require_once (__WWW_PATH__.'statusitem.php');
require_once (__WWW_PATH__.'timeutils.php');
require_once (__WWW_PATH__.'pageTools.php');
require_once (__WWW_PATH__.'licInfo.php');
require_once (__WWW_PATH__.'analysisObj.php');
require_once (__WWW_PATH__.'RAIDChk.php');

require_once 'crawl_statusObj.php';

$heliosdb_connection = require (__HELIOS_DB__.'db.php');
global $heliosdb_connection;

main();
exit(0);

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
        insertStatusOutputEntry($outArray, "ERROR: Current analysis", "FAIL - " . $sst->analysisErrorStrs[0], $excClass[STATUS_EXCEPTION_WARN]);
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


function main() {
    echo "enter analyzer info updater <br/>\n";
    $dbCon = connectDB(); //for helios

    if (!$dbCon)
        exit(1);

    if ($result = $dbCon->query("SELECT DATABASE()")) {
        $row = $result->fetch_row();
        echo "using DB to write rtma data : " . $row[0] . " </br>\n";
        $result->close();
    }
    //Write Analyzer Status information
    analysis_status_to_db($dbCon);

    disconnectDB($dbCon);
    echo "exiting analyzer info updater <br/>\n";

}
