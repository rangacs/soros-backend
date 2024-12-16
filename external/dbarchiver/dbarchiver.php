<?php

//ob_start();    // buffer any output until the end
//----------------------------------------------------------------------------------------
// Class definition and functions for the analysis object
//----------------------------------------------------------------------------------------
// SABIA Proprietary
// Copyright (c) 2003-2007, 2008, 2009 by SABIA, Inc.
//
//! \file
//! add this to cron
//! */5 * * * * php -f /var/www/html/external/dbarchiver/dbarchiver.php >> /var/www/html/Helios/tmp/runlog/`date +\%Y\%m\%d\%H\%M\%S`-cron.log 2>&1
//! */5 * * * * php -f /var/www/html/external/dbarchiver/dbarchiver.php >> /var/www/html/Helios/tmp/runlog/`date +\%Y-\%m-\%d`-cron.log 2>&1
//!

//define('PRODUCT_NAME_VERSION', 'helios1');
define('PRODUCT_NAME_VERSION', 'helios2');


require_once '../common/log4php/Logger.php';
require_once '../common/sabiaLogger.php';
require_once '../common/utils.php';

chdir(dirname(__FILE__));
error_reporting(0);
error_reporting(E_ALL);

//define('__SERVER__', 'live'); 
define('__SERVER__', 'testserver_166');

//define(DEBUG, false);

define("PRODUCT_NAME", "helios"); /* IMPORTANT for helios */     // define either rtma or helios

if (__SERVER__ != 'live') {
//166 for test
// /var/www/html/1dev/HELIOS1/pawan/helios1/external/crawl
    define('__WWW_PATH__', '/var/www/html/v2.0/');
//define('__HELIOS_DB__', '../');
    if (PRODUCT_NAME_VERSION == 'helios1')
        define('__HELIOS_DB__', '/var/www/html/helios1/protected/config/');
//    define('__HELIOS_DB__', '../../protected/config/');
    else
//    define('__HELIOS_DB__', '../../config/');
        define('__HELIOS_DB__', '/var/www/html/helios2/config/');

//    define('__WWW_PATH__', '../../../../../../v2.0/');
//    define('__HELIOS_DB__', '../../../../../../');    
} else {
//for live
    define('__WWW_PATH__', '../../../');
    if (PRODUCT_NAME_VERSION == 'helios1')
        define('__HELIOS_DB__', '../../protected/config/');
    else
        define('__HELIOS_DB__', '../../config/');
}

$db = require (__HELIOS_DB__ . 'db.php');
global $db;

Logger::configure('dbarchiver_logproperties.xml');
global $log;

main();

class dbarchiverClass {

    private $log;
    private $con;

    public function __construct($con, $log) {
        $this->log = $log;
        $this->con = $con;
        return;
    }

    function setDatabases() {
        $this->sourceDb = getDBName();
        $this->backupDb = getDBName() . '_bkp';
    }

    public function __destruct() {
        disconnectDB($this->con);
    }

    function backupTable($table, $dateCol, $endTime) {

        $retVal = false;
        //check if table 
        //check with backup table
        $sQuery = "CREATE TABLE IF NOT EXISTS $this->backupDb.$table like $this->sourceDb.$table";
        if (0)
            $this->log->info($sQuery);
        $result1 = mysqli_query($this->con, $sQuery);
        if (!$result1)
            $this->log->fatal("Query returned no results, Query is: $sQuery  ");

        //find if there are records
        $sQuery = "SELECT COUNT(*) FROM  $this->sourceDb.$table where `$dateCol` <  '$endTime'";
        if (1)
            $this->log->info($sQuery);
        $result2 = mysqli_query($this->con, $sQuery);
        if ($result2 && $result2->num_rows > 0) {
            $nrecordsAr = $result2->fetch_array();
            $nrecords = $nrecordsAr[0];
            if ($nrecords > 0) {
                //we have rows, insert in the backup table
                $sQuery = "INSERT INTO $this->backupDb.$table SELECT * FROM  $this->sourceDb.$table where `$dateCol` <  '$endTime'";
                if (0) {
                    $this->log->info($sQuery);
                    echo ($sQuery);
                }

                $result3 = mysqli_query($this->con, $sQuery);

                if ($result3) {
                    $this->log->info("Inserted " . $nrecords . " records");
                    //echo ("Inserted " . $nrecords . " records");
                    //test we do not want to delete right now
                    $sQuery = "DELETE FROM $this->sourceDb.$table where `$dateCol` <  '$endTime'";
                    if (0)
                        $this->log->info($sQuery);
                    $result4 = mysqli_query($this->con, $sQuery);
                    if (!$result4)
                        $this->log->fatal("Unable to delete Query returned no results, Query is: $sQuery  // " . mysqli_error($this->con));
                    else // if (!$result4)
                        $retVal = true;
                } else //if ($result3)
                    $this->log->fatal("unable to insert, Query returned no results, Query is: $sQuery  ");
            }//if ($nrecords > 0)
            $result2->close();
        }else { //if ($result2 && $result2->num_rows > 0)
            $this->log->error("no records in $this->sourceDb.$table");
        }
        return $retVal;
    }

    function dbarchiver($con) {

        $sQuery = "select * from rm_settings where varName = 'ARCHIVER_BACKUP_DURATION_MONTHS'";

        $result = mysqli_query($con, $sQuery);
        if ($result && $result->num_rows) {
            $row = $result->fetch_array();
            $archiverBackupDuration = $result['varValue'];
            if ($archiverBackupDuration)
                $archiverBackupDuration_days = $archiverBackupDuration * 6;
            else
                $archiverBackupDuration_days = 6 * 30;
            $result->close();
        } else {
            $archiverBackupDuration_days = 6 * 30;
        }
        //for test
        $archiverBackupDuration_days = 30;
        $endTime = date("Y-m-d 00:00:00", time() - (60 * 60 * 24 * $archiverBackupDuration_days));

        $sQuery = "SELECT * FROM rm_helios_download_tables_list where archiver = 1";
        $result = mysqli_query($con, $sQuery);
        if ($result && $result->num_rows) {
            while ($row = $result->fetch_array()) {
                $result1 = $this->backupTable($row['TableName'], $row['dateCol'], $endTime);
            }
            $result->close();
        } else
            $this->log->fatal("Query returned no results, Query is: $sQuery  ");
    }

}

//class

function main() {
    echo "enter db archiver info updater <br/>\n";
    $log = new sabiaLogger();

    $con = connectDB($log); //for helios
    $log->setDbCon($con);
    $log->trace("enter db archiver info updater <br/>!");

    if (!$con)
        exit(1);

    if ($result = $con->query("SELECT DATABASE()")) {
        $row = $result->fetch_row();
        $log->debug("using DB to write dbarchiver data : " . $row[0] . " </br>");
        $result->close();
    }

    $dbArchObj = new dbarchiverClass($con, $log);
    $dbArchObj->setDatabases();
    $dbArchObj->dbarchiver($con);

    echo "exiting db archiver updater <br/>\n";
}

?>
