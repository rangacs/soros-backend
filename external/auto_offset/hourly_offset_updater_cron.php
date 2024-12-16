<?php

//ob_start();    // buffer any output until the end
//----------------------------------------------------------------------------------------
// Class definition and functions for the analysis object
//----------------------------------------------------------------------------------------
// SABIA Proprietary
// Copyright (c) 2019 by SABIA, Inc.
//
//! \file
//! add this to cron or mastercron.h to run hourly
//!  php -f /var/www/html/Helios/external/auto_offset/hourly_offset_updater_cron.php  > /var/www/html/Helios/tmp/runlog/`date +\%Y\%m\%d`-cron.log 2>&1
//! */1 * * * * php -f /var/www/html/helios1/external/auto_offset/hourly_offset_updater_cron.php > /var/www/html/helios1/tmp/runlog/`date +\%Y\%m\%d`-cron.log 2>&1
//! day log
//! */1 * * * * php -f /var/www/html/helios1/external/auto_offset/hourly_offset_updater_cron.php >> /var/www/html/helios1/tmp/runlog/`date +\%Y-\%m-\%d`-cron.log 2>&1
//! 
//! 
//! 
//! 
//
define('PRODUCT_NAME_VERSION', 'helios1');
//define('PRODUCT_NAME_VERSION', 'helios2');


error_reporting(0);

chdir(dirname(__FILE__));
require_once '../common/log4php/Logger.php';
require_once '../common/sabiaLogger.php';
require_once '../common/utils.php';
require_once '../../../configfile.php';
require_once '../../../defines.php';

//GLOBAL Declare
global $log;
global $db;
global $params;

Logger::configure('hourly_offset_updater_cron_logproperties.xml');
$db = require '../../protected/config/db.php';
$params = require '../../protected/config/params.php';

main();
echo date('h:i:s a') . " : exiting !\n";
exit(0);

class OffsetUpdater {

    private $calibFile;
    private $content;
    private $SiO2_gain;
    private $SiO2_offset;
    private $Fe2O3_gain;
    private $Fe2O3_offset;
    private $Al2O3_gain;
    private $Al2O3_offset;
    private $CaO_gain;
    private $CaO_offset;
    private $run_type;
    private $SiO2_updated;
    private $Fe2O3_updated;
    private $Al2O3_updated;
    private $CaO_updated;
    public $log;
    public $con;
    public $cfg;

    public function __construct($log = null, $con = null) {
        $this->log = $log;
        $this->con = $con;

        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILENAME;
        $cfg->load($calAdjust);
        $cfg->setPath("/USRCAL");
        $this->calibFile = $cfg->readEntry("lib_file");

        $cfg2 = new ConfigFile();
        $cfg2->load($this->calibFile);
        $this->cfg = $cfg2;
        $this->content = '';
        $this->run_type = "AHC";

        return;
    }

    public function read_user_calibration() {

        $ret_value = false;
        $content = "";

        //TODO: time valid till to be updated
        $now = date('Y-m-d H:i:s');
        $sQuery = "SELECT * FROM `calibration_hourly` where `consumed` = 0 and `cal_id` = 1 && `valid_till` > '$now'";






        $result = mysqli_query($this->con, $sQuery);
        if ($result && $result->num_rows) {
            while ($row = $result->fetch_array()) {

                if ($row['SiO2_offset'] != "-9999") {
                    $content .= "[SiO2]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=" . $row['SiO2_gain'] . PHP_EOL;
                    $content .= "offset=" . $row['SiO2_offset'] . PHP_EOL;
                    $gain = $row['SiO2_gain'];
                    $offset = $row['SiO2_offset'];
                    $this->SiO2_updated = 1;
                    $this->log->trace("Found SiO2_gain offset to be updated");
                } else {
                    $this->cfg->setPath("/" . "SiO2");
                    $offset = $this->cfg->readEntry("offset", 0.0);
                    $gain = $this->cfg->readEntry("gain", 1);
                    $content .= "[SiO2]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=" . $gain . PHP_EOL;
                    $content .= "offset=" . $offset . PHP_EOL;
                    $this->SiO2_updated = 0;
                    $this->log->trace("Kept old offset for SiO2");
                }
                $this->SiO2_gain = $gain;
                $this->SiO2_offset = $offset;

                if ($row['Fe2O3_offset'] != "-9999") {
                    $content .= "[Fe2O3]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=" . $row['Fe2O3_gain'] . PHP_EOL;
                    $content .= "offset=" . $row['Fe2O3_offset'] . PHP_EOL;
                    $gain = $row['Fe2O3_gain'];
                    $offset = $row['Fe2O3_offset'];
                    $this->Fe2O3_updated = 1;
                    $this->log->trace("Found Fe2O3_gain offset to be updated");
                } else {
                    $this->cfg->setPath("/Fe2O3");
                    $offset = $this->cfg->readEntry("offset", 0.0);
                    $gain = $this->cfg->readEntry("gain", 1);
                    $content .= "[Fe2O3]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=" . $gain . PHP_EOL;
                    $content .= "offset=" . $offset . PHP_EOL;
                    $this->Fe2O3_updated = 0;
                    $this->log->trace("Kept old offset for Fe2O3");
                }
                $this->Fe2O3_gain = $gain;
                $this->Fe2O3_offset = $offset;
                if ($row['Al2O3_offset'] != "-9999") {
                    $content .= "[Al2O3]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=" . $row['Al2O3_gain'] . PHP_EOL;
                    $content .= "offset=" . $row['Al2O3_offset'] . PHP_EOL;
                    $gain = $row['Al2O3_gain'];
                    $offset = $row['Al2O3_offset'];
                    $this->Al2O3_updated = 1;
                    $this->log->trace("Found Al2O3_gain offset to be updated");
                } else {
                    $this->cfg->setPath("/" . "Al2O3");
                    $offset = $this->cfg->readEntry("offset", 0.0);
                    $gain = $this->cfg->readEntry("gain", 1);
                    $content .= "[Al2O3]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=" . $gain . PHP_EOL;
                    $content .= "offset=" . $offset . PHP_EOL;
                    $this->Al2O3_updated = 0;
                    $this->log->trace("Kept old offset for Al2O3");
                }
                $this->Al2O3_gain = $gain;
                $this->Al2O3_offset = $offset;

                if ($row['CaO_offset'] != "-9999") {
                    $content .= "[CaO]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=" . $row['CaO_gain'] . PHP_EOL;
                    $content .= "offset=" . $row['CaO_offset'] . PHP_EOL;
                    $gain = $row['CaO_gain'];
                    $offset = $row['CaO_offset'];
                    $this->CaO_updated = 1;
                    $this->log->trace("Found CaO_offset offset to be updated");
                } else {
                    $this->cfg->setPath("/" . "CaO");
                    $offset = $this->cfg->readEntry("offset", 0.0);
                    $gain = $this->cfg->readEntry("gain", 1);
                    $content .= "[CaO]" . PHP_EOL;
                    $content .= "type=output" . PHP_EOL;
                    $content .= "gain=" . $gain . PHP_EOL;
                    $content .= "offset=" . $offset . PHP_EOL;
                    $this->CaO_updated = 0;
                    $this->log->trace("Kept old offset for CaO");
                }
                $this->CaO_gain = $gain;
                $this->CaO_offset = $offset;

                $this->content = $content;
                $ret_value = true;
            }
        } else {
            $this->log->debug("Query returned no results, Query is: $sQuery");
        }

        $this->log->trace("read_user_calibration() exit");
        return $ret_value;
    }

    public function save_user_calib_file() {
        $ret_value = false;
        $fp = fopen($this->calibFile, 'w');

        $paramstr = $GLOBALS['params'];
        $isWritableDefaultCfg = $paramstr['writeCalib'];
        if ($isWritableDefaultCfg == 0)
            $this->log->error("$this->calibFile not updated, writeCalib is 0 in params.h");
        else {
            $result = fwrite($fp, $this->content);
            if ($result) {
                $ret_value = true;
                $infostr = preg_replace('/([\r\n\t])/', ' ', $this->content);
                $this->log->error("wrote $infostr");
                $this->log->debug("$this->calibFile updated");
            } else
                $this->log->error("$this->calibFile not updated, permissions issue");
        }
        fclose($fp);
        $this->log->trace("save_user_calib_file() exit");
        return $ret_value;
    }

    public function update_consumed() {
        $ret_value = false;
        $sQuery = "UPDATE `calibration_hourly` set `consumed` = '1', `SiO2_offset`='-9999', `Al2O3_offset`='-9999', `Fe2O3_offset`='-9999', `CaO_offset`='-9999' where `cal_id` = '1'";
        $result = mysqli_query($this->con, $sQuery);
        if ($result) {
            $ret_value = true;
            $this->log->debug("calibration_hourly consume updated");
        } else {
            $this->log->trace("Query returned no results, Query is: $sQuery");
        }
        $this->log->trace("update_consumed() exit");
        return $ret_value;
    }

    public function update_acsettings($element_name) {
        $ret_value = false;

        $sQuery = "UPDATE `ac_settings` set  last_updated = now() where `element_name` = '$element_name'";
        $result = mysqli_query($this->con, $sQuery);
        if ($result) {
            $ret_value = true;
            $this->log->debug("update_acsettings for $id updated");
        } else {
            $this->log->trace("Query returned no results, Query is: $sQuery");
        }

        $this->log->trace("update_acsettings() exit");
        return $ret_value;
    }

    public function update_calibrationlog() {
        $ret_value = false;

        $sQuery = "INSERT INTO `calibration_log` (`SiO2_gain`, `SiO2_offset`, `Fe2O3_gain`, `Fe2O3_offset`, `Al2O3_gain`, `Al2O3_offset`, `CaO_gain`, `CaO_offset`, `run_type`, `base_record`, `updated_by`, `updated`) VALUES ($this->SiO2_gain, $this->SiO2_offset, $this->Fe2O3_gain, $this->Fe2O3_offset, $this->Al2O3_gain, $this->Al2O3_offset, $this->CaO_gain, $this->CaO_offset, '$this->run_type', '0', '1', now())";
        $result = mysqli_query($this->con, $sQuery);
        if ($result) {
            $ret_value = true;
            $this->log->debug("calibrationlog updated");
        } else {
            $this->log->trace("Query returned no results, Query is: $sQuery");
        }

        if ($this->SiO2_updated)
            $result = $this->update_acsettings("SiO2");
        if ($this->Fe2O3_updated)
            $result = $this->update_acsettings("Fe2O3");
        if ($this->Al2O3_updated)
            $result = $this->update_acsettings("Al2O3");
        if ($this->CaO_updated)
            $result = $this->update_acsettings("CaO");

        $this->log->trace("update_calibrationlog() exit");
        return $ret_value;
    }

}

//------------------------------------------------------------------------------
// main routine
//------------------------------------------------------------------------------
function main() {
    echo date('h:i:s a') . " : hourly offset updater daemon started !\n";
    $log = new sabiaLogger("OffsetUpdater");
    $con = connectDB($log);

    $log->setDbCon($con);
    $log->fatal("***offset updater daemon started !");

    $offset_obj = new OffsetUpdater($log, $con);
    $result = $offset_obj->read_user_calibration();
    if (!$result) {
        $log->error("%consumed is 1 or past valid time, nothing to update, exiting");
        disconnectDB($log, $con);
        return;
    }

    $result = $offset_obj->save_user_calib_file();
    if (!$result) {
        $log->error("%unable to save calib file, abnormal exit");
        return;
    }

    $result = $offset_obj->update_consumed();
    if (!$result) {
        $log->error("%unable to update consumed, abnormal exit");
        disconnectDB($log, $con);
        return;
    }

    $result = $offset_obj->update_calibrationlog();

    disconnectDB($log, $con);
    $log->fatal("### hourly offset updater completed successfully");
    return;
}

?>
