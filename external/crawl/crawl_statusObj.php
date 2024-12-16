<?php

//-----------------------------------------------------------------------------------------
// System status object classes
//----------------------------------------------------------------------------------------
// SABIA Proprietary
// Copyright (c) 2007, 2009 by SABIA, Inc.  All rights reserved.
//
//! \file
//! Comprehensive analyzer system status class hierarchy.  Includes logic for querying
//! all system and per-detector status items, and utilities for building status
//! output tables.
//
require_once '/var/www/html/statusitem.php';
require_once '/var/www/html/timeutils.php';
require_once '/var/www/html/pageTools.php';
require_once '/var/www/html/licInfo.php';
require_once '/var/www/html/analysisObj.php';
require_once '/var/www/html/RAIDChk.php';

//------------------------------------------------------------------------------
// emitSmallStatusSummary - emits a very small status HTML table
//------------------------------------------------------------------------------
//! Emits a tiny status summary table for use in the logo pane etc.
//
function emitSmallStatusSummary() {
    $sst = new SystemStatus();
    $sst->getStatus();
    $recording = $sst->isTaggedSampleActive();
    if ($recording) {
        $sampleTag = $sst->tagSampleID;
        $operatorTag = $sst->tagSampleOperator;
    }
    echo "<table>";
    echo "<tr>\n";
    echo "<td align=center>Analyzer Status</td>\n";
    if ($recording) {
        echo "<td align=center>Sample ID</td>\n";
        echo "<td align=center>Operator ID</td>\n";
    }
    echo "<td align=center>Updated</td>\n";
    echo "</tr>\n";

    // content row
    echo "<tr>";
    $excp = $sst->getExceptionList();

    if (statusExceptionLevelExceeds($excp, STATUS_EXCEPTION_INFO)) {
        echo "<td><small><font color=red>CHECK SYSTEM STATUS</font></small></td>";
    } else {
        echo "<td><small><font color=blue>OK</font></small></td>\n";
    }

    if ($recording) {
        echo "<td align=center><small>$sampleTag</small></td>\n";
        echo "<td align=center><small>$operatorTag</small></td>\n";
    }

    // Emit update time string
    $timeStr = date('m/d/y H:i:s T', time());
    echo "<td align=center><small>$timeStr</small></td>\n";
    echo "</tr>";
    echo "</table>";
}

// emitSmallStatusSummary()
//------------------------------------------------------------------------------
// insertStatusOutputEntry - adds an item to a status list table
//------------------------------------------------------------------------------
//! Utility to store a key/value/class tuple into an output table array.
//! Used to build a status table whose entries will be color-highlighted according
//! to their exception state.
//
function insertStatusOutputEntry(
&$array, //!< Status array being filled (IN/OUT)
        $name, //!< Key name of the item (IN)
        $value, //!< Item value string (IN)
        $class = ""                 //!< CSS class to hilight value with (IN)
) {
    if (!is_array($array))
        $array = array();

    $aCount = count($array);
    $array[$aCount]['name'] = $name;
    $array[$aCount]['value'] = $value;
    $array[$aCount]['class'] = $class;
}

//------------------------------------------------------------------------------
// emitStatusTable - emits an HTML table showing a set of status items
//------------------------------------------------------------------------------
//! Utility to emit an HTML table with the contents of a status array that was
//! built with insertStatusOutputEntry.
//! The CSS class for each item's value can be separately specified.
//
function emitStatusTable(
$outArray, //!< Array of [label] [value] [class] tuples to display in a table row.
        $detArray, //!<Array of Detector Information
        $tableAttribs = "", //!< Attributes to apply to the HTML table element.
        $titleClass = "", //!< CSS class applied to special 'title' rows.
        $nameClass = "", //!< CSS class applied to 'name' column items.
        $dbCon = null     //!<Database connection 
) {
    //echo "<table $tableAttribs>\n";
    // Emit the array into the HTML table
    foreach ($outArray as $ent) {
        $name = $ent['name'];
        $value = $ent['value'];
        $class = $ent['class'];
        // Special formatting applied to certain items
        if ($name == "title") {
            if ($value == "PLC Subsystem Status") {
                //echo "<tr><td colspan=2 $tClass>";
                showDetInformation($dbCon, $detArray, $tableAttribs, $titleClass, $nameClass);
                //echo "</td></tr>\n";
            }
            $tClass = empty($titleClass) ? "" : "class=\"$titleClass\"";
            //echo "<tr><td colspan=2 $tClass>$value</td></tr>\n";
        } else {
            $nClass = empty($nameClass) ? "" : "class=\"$nameClass\"";
            //echo "<tr><td $nClass>$name</td>\n";

            $cls = empty($class) ? "" : "class=\"$class\"";
            //echo "<td align=left $cls>$value</td></tr>\n";
        }
    }
}

function showDetInformation($dbCon, $detsArray, $tableAttribs, $titleClass, $nameClass) {

    //TODO
    $rmSettingsAr = array(
        "SYS_STATUS" => "ALL",
        "MAT_STATUS" => "ps -e | grep run*",
        "DB_STATUS" => 1,
        "AN_RESULTS_STATUS" => "Analysis_Response",
        "DET_1_STATUS" => "Detector_Temp",
        "DET_2_STATUS" => "Detector_Temp"
    );

    $tClass = empty($titleClass) ? "" : "class=\"$titleClass\"";

    $singleDetArray = array();
    $namesArray = array();

    // Emit the array into the HTML table
    foreach ($detsArray as $detArray) {
        foreach ($detArray as $vals) {
            $name = $vals['name'];
            $value = $vals['value'];
            $class = $vals['class'];
            if (in_array($name, $namesArray)) {
                $singleDetArray[$name] = $singleDetArray[$name] . ":" . $value . "#" . $class;
            } else {
                array_push($namesArray, $name);
                $singleDetArray[$name] = $value . "#" . $class;
            }
        }
    }
    //[title] => Detector 1#:Detector 2# [PMT Control State] => LOCKED#:ADJUSTING# 
    $val_1_Str = '';
    $val_2_Str = '';
    $colStr = '';
    foreach ($singleDetArray as $id => $detVals) {
        if ($id == "PMT Control State")
            continue;
        $nClass = empty($nameClass) ? "" : "class=\"$nameClass\"";
        $id = str_replace(" ", "_", $id);
        $colStr .= "`" . $id . "`,";
        $valsArray = explode(":", $detVals);
        $valClass = ' class="statusTabValue"';
        $detId = 0;


        if (count($valsArray) > 0) {
            foreach ($valsArray as $vals) {
                list($value, $class) = explode("#", $vals);
                if ($id == "Analyze Cmd") {
                    $cls = empty($class) ? "style=\"background-color:#eeeeff;font-size:8px\"" : "class=\"$class\"";
                    $value = str_replace("\"", "'", $value);
                } else
                    $cls = empty($class) ? "style=\"background-color:#eeeeff;\"" : "class=\"$class\"";

                if ($detId == 0)
                    $val_1_Str .= "'" . $value . "',";
                else
                    $val_2_Str .= "'" . $value . "',";

                $detId++;
            }
        }
        else {
            list($value, $class) = explode("#", $vals);
            $cls = empty($class) ? "style=\"background-color:#eeeeff;\"" : "class=\"$class\"";
            $valStr .= "'" . $value . "',";
        }
    }

    $colStr_s = substr($colStr, 0, -1);
    $valStr_1_s = substr($val_1_Str, 0, -1);
    $valStr_2_s = substr($val_2_Str, 0, -1);

    updateDetInfoToDb($dbCon, $colStr_s, $valStr_1_s, $valStr_2_s, $rmSettingsAr);
}

function updateDetInfoToDb($dbCon, $colStr_s, $valStr_1_s, $valStr_2_s, $rmSettingsAr) {
    $err = "";
    //TODO not being used $rmSettingsAr
    $ins1 = "INSERT INTO analysis_status_info (info_id,$colStr_s) VALUES (NULL,$valStr_1_s)";
    $ins2 = "INSERT INTO analysis_status_info (info_id,$colStr_s) VALUES (NULL,$valStr_2_s)";

    $result = mysqli_query($dbCon, $ins1);

    if ($result)
        $err .= "DB analysis_status_info Det-1 updated";
    else
        $err .= "DB analysis_status_info Det-1 Update Failed  !" . date("Y-m-d H:i:s") . "\n";


    $result = mysqli_query($dbCon, $ins2);
    if ($result)
        $err .= " and Det-2 updated successfully at " . date("Y-m-d H:i:s") . "\n";
    else
        $err .= "DB analysis_status_info Det-2 Db Update Failed  !" . date("Y-m-d H:i:s") . "\n";
    
    echo $err;

    file_put_contents("/usr/local/sabia-ck/det_info.status", $err, LOCK_EX);
}

//------------------------------------------------------------------------------
// PlcStatus - PLC status information class
//------------------------------------------------------------------------------
//! PLC status class.
//
class PlcStatus {

    // Metadata
    var $bHaveStatus;                  //!< True if status has been filled in.
    // Data furnished by containing object
    var $anConf;                       //!< Loaded ConfigFile for analyzer.conf
    var $uiConf;                       //!< Loaded ConfigFile for ui.conf
    var $daemonStatus;                 //!< Array of daemon status entries from getDaemonStatus()
    // Metadata status items
    var $plcdInstalled;                //!< Keyword showing PLCD install state Yes/No/Unknown
    var $bPlcdInstalled;               //!< TRUE if PLCD is known to be installed, FALSE otherwise
    var $daemonRunning;                //!< Shows plcd daemon installation/running state.
    // Live status items from plcd.conf
    var $massFlow;                     //!< Belt scale (mass flow tph) reading.
    var $beltSpeed;                    //!< Belt speed reading.
    var $plcMoistureIn;                //!< External moisture meter reading.
    var $lastShiftResetTime;           //!< Time of most recent PLC shift reset input (time_t)
    var $plcdStartupTime;              //!< Time that plcd was started (time_t)
    var $materialType;                 //!< Material type input 1 = rock, 2 = coal, etc.
    // Internal data
    // plcd state acceptable values.  Unknown, not running, and stale status file are errors.
    var $plcdStateWhitelist = array('Running', 'Not installed', 'Current status file');

    //! Constructor.
    function PlcStatus(
    $anConf, //!< Loaded configfile object for analyzer.conf
            $uiConf, //!< Loaded configfile object for ui.conf
            $daemonStatus                  //!< Array of daemon status entries from ::getDaemonStatus()
    ) {
        // Object metadata
        $bHaveStatus = FALSE;

        $this->anConf = $anConf;
        $this->uiConf = $uiConf;
        $this->daemonStatus = $daemonStatus;

        // Read some limits from ui.conf values
        $this->uiConf->setPath("/" . UI_CONF_STATUS_LIMITS_SECT);

        $beltspdlb = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_BELTSPEED_LBOUND, null);
        $beltspdub = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_BELTSPEED_UBOUND, null);
        $massflowlb = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_MASSFLOW_LBOUND, null);
        $massflowub = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_MASSFLOW_UBOUND, null);

        $this->plcdInstalled = new StatusItem(
                'plcdInstalled', 'PLCD installed', 'PLCD Installed', 'PLCD installed', NULL);
        $this->bPlcdInstalled = FALSE;

        // Live status
        $this->daemonRunning = new StatusItem(
                'plcdRunning', 'PLCD running', 'PLCD Running', 'PLCD running', new BoundedBoolean(TRUE, STATUS_EXCEPTION_WARN));

        $this->plcdStateSummary = new StatusItem(
                'plcdSummary', 'PLCD state', 'PLCD State', 'PLCD state', new BoundedKeyword($this->plcdStateWhitelist, NULL, STATUS_EXCEPTION_ERROR));

        $this->beltSpeed = new StatusItem(
                'beltSpeed', 'Belt speed', 'Belt Speed', 'belt speed', new BoundedNum($beltspdlb, $beltspdub, STATUS_EXCEPTION_INFO), NULL, "fpm");
        $this->massFlow = new StatusItem(
                'massFlow', 'Mass flow', 'Mass Flow', 'mass flow', new BoundedNum($massflowlb, $massflowub, STATUS_EXCEPTION_INFO), NULL, "tph");
        $this->plcMoistureIn = new StatusItem(
                'plcMoistureIn', 'External moisture input', 'External Moisture Input', 'external moisture input', new BoundedNum(NULL, NULL, STATUS_EXCEPTION_WARN), NULL, "%");
        $this->lastShiftResetTime = new StatusItem(
                'lastShiftResetTime', 'Last shift reset', 'Last Shift Reset', 'last shift reset', NULL);
        $this->plcdStartupTime = new StatusItem(
                'plcdStartupTime', 'PLCD startup time', 'PLCD Startup Time', 'PLCD startup time', NULL);
        $this->materialType = new StatusItem(
                'materialType', 'Material type', 'Material Type', 'material type', NULL);
    }

    //! Fetches the status items from live analyzer data.
    function getStatus() {
        // Determine installation/running states
        if (empty($this->daemonStatus)) {
            $this->plcdInstalled->setValue("Unknown");
            $this->plcdStateSummary->setValue("Unknown");
            $this->bPlcdInstalled = FALSE;
            $this->daemonRunning->setValue(FALSE);
        } else if (!isset($this->daemonStatus['plcd'])) {
            $this->plcdInstalled->setValue("Unknown");
            $this->plcdStateSummary->setValue("Unknown");
            $this->bPlcdInstalled = FALSE;
            $this->daemonRunning->setValue(FALSE);
        } else {
            switch ($this->daemonStatus['plcd']) {
                case 0:
                    $this->plcdInstalled->setValue("Yes");
                    $this->plcdStateSummary->setValue("Not running");
                    $this->bPlcdInstalled = TRUE;
                    $this->daemonRunning->setValue(FALSE);
                    break;
                case 1:
                    $this->plcdInstalled->setValue("Yes");
                    $this->plcdStateSummary->setValue("Running");
                    $this->bPlcdInstalled = TRUE;
                    $this->daemonRunning->setValue(TRUE);
                    break;
                case 2:
                    $this->plcdInstalled->setValue("Not installed");
                    $this->plcdStateSummary->setValue("Not installed");
                    $this->bPlcdInstalled = FALSE;
                    $this->daemonRunning->setValue(FALSE);
                    break;
                default:
                    $this->plcdInstalled->setValue("Unknown");
                    $this->plcdStateSummary->setValue("Unknown");
                    $this->bPlcdInstalled = FALSE;
                    $this->daemonRunning->setValue(FALSE);
                    break;
            }
        }

        //-----------------------------------------------------------------------------
        // Configuration items from plcd.conf
        //-----------------------------------------------------------------------------
        //-----------------------------------------------------------------------------
        // Status items from plcd.status
        //-----------------------------------------------------------------------------
        // We try (somewhat) to handle situations where the plcd daemon has been
        // run manually from the command line.
        if (file_exists(PLCD_DOT_STATUS_FILENAME) && fileIsUpToDate(PLCD_DOT_STATUS_FILENAME, 60)) {
            // If PLCD is running it must be installed somehow, RPM or otherwise
            if ($this->plcdInstalled->getValue() != "Yes")
                $this->plcdInstalled->setValue("Yes (non-RPM)");
            $this->plcdStateSummary->setValue("Current status file");
            $this->bPlcdInstalled = TRUE;

            // Use ConfigFile class to read all of these at a whack
            $plcdStat = new ConfigFile();
            $plcdStat->load(PLCD_DOT_STATUS_FILENAME);
            $plcdStat->setPath("/" . PLCD_STATUS_SECT);

            $this->beltSpeed->setValue($plcdStat->readEntry("belt_speed_fpm", 0));
            $this->massFlow->setValue($plcdStat->readEntry("mass_flow_tph", 0));
            $this->plcMoistureIn->setValue($plcdStat->readEntry("moisture_meter_val", 0));
            $this->lastShiftResetTime->setValue($plcdStat->readEntry("last_shift_reset_time", 0));
            $this->plcdStartupTime->setValue($plcdStat->readEntry("plcd_start_time", 0));
            $this->materialType->setValue($plcdStat->readEntry("material_type", 0));
        }
        else {
            $this->plcdStateSummary->setValue("Stale status file");

            $this->beltSpeed->setValue(0);
            $this->massFlow->setValue(0);
            $this->plcMoistureIn->setValue(0);
            $this->lastShiftResetTime->setValue(0);
            $this->plcdStartupTime->setValue(0);
            $this->materialType->setValue(0);
        }

        $this->bHaveStatus = TRUE;
        return TRUE;
    }

//getStatus
    //! Returns PLCD state as a keyword
    function getPlcdStateKeyword() {
        if (empty($this->daemonStatus))
            return "Unknown";
        if (!isset($this->daemonStatus['plcd']))
            return "Unknown";
        switch ($this->daemonStatus['plcd']) {
            case 0:
                return "Not running";
            case 1:
                return "Running";
            case 2:
                return "Not installed";
            default:
                return "Unknown";
        }
    }

    //------------------------------------------------------------------------------------
    // getExceptionList - builds list of StatusItemExceptions
    //------------------------------------------------------------------------------------
    //! Adds exceptions for out-of-range parameters to the given list of
    //! StatusItemException objects.
    function getExceptionList(
    &$excpList                      //! List of exceptions that we add to (IN/OUT)
    ) {
        if (!isset($excpList))
            $excpList = array();

        // check daemon status:
        //   Bail with no further checking if state is "Not installed".
        //   exception if state is "Not running" or "Unknown"
        $plcdk = $this->getPlcdStateKeyword();
        if ($plcdk == "Not installed") {
            
        } else if ($plcdk == "Not running") {
            $excpList[] = new StatusItemException("PLCD State", "PLCD is installed but not running", STATUS_EXCEPTION_WARN);
        } else if ($plcdk == "Running") {
            // Run StatusItem installed range checkers when state is "Running"
            $this->beltSpeed->checkBounds($excpList);
            $this->massFlow->checkBounds($excpList);
        } else {
            $excpList[] = new StatusItemException("PLCD State", "Internal error: PLCD state is unknown", STATUS_EXCEPTION_WARN);
        }
    }

}

// PlcStatus class
//-----------------------------------------------------------------------------------------
// DetectorStatus - per-detector status class
//-----------------------------------------------------------------------------------------
//! Per-detector status class.
class DetectorStatus {

    // Metadata
    var $bHaveStatus;                  //!< True if status has been filled in
    var $uiConf;                       //!< Loaded ConfigFile object for ui.conf
    var $anConf;                       //!< Loaded ConfigFile object for analyzer.conf
    // StatusItem objects
    var $daemonRunning;                //!< True if detector's datad is running
    // Configuration data (based on analyzer.conf)
    var $detectorID;                   //!< Detector ID, e.g. 'datad1' - section name from analyzer.conf.
    var $detectorName;                 //!< Detector display name, e.g. 'Detector 1'
    var $useResults;                   //!< True if analyzer.conf says to use this detector's results
    var $analysisResponseSet;          //!< Name of configured analysis response set.
    var $alignmentResponseSet;         //!< Name of configured alignment response set.
    var $pmtControlMode;               //!< PMT control mode (fixed, fine-track, deadband)
    var $globalOffsetConfig;           //!< Global offset target value. (BoundedNum)
    // Live status data pulled from alignment result
    var $analyzeCmdIssued;             //!< Command string from the analysisObj we run.
    var $countRateCpmAlignment;        //!< Processed count rate from alignment result (BoundedNum)
    var $h_fwhm;                       //!< H peak FWHM in percent (BoundedNum)
    var $alignedOK;                    //!< True if most recent binning interval aligned OK
    var $goodDataSecs;                 //!< Good data seconds in binning interval (BoundedNum)
    var $h_rawChan;                    //!< Raw H peak channel (BoundedNum)
    var $alignmentGain;                //!< Alignment gain (BoundedNum)
    var $alignmentOffset;              //!< Alignment offset (BoundedNum)
    var $alignmentChisqr;              //!< Alignment solution chisqr (BoundedNum)
    var $globalOffset;                 //!< Global offset solution (BoundedNum)
    var $beltSpeed;                    //!< Belt speed reading logged from plcd (BoundedNum)
    var $massFlow;                     //!< Belt scale reading logged from plcd (BoundedNum)
    var $plcMoistureIn;                //!< External moisture reading furnished via PLCD (BoundedNum)
    var $detectorTemp;                 //!< Detector temperature reading from DCU, calibrated
    var $HVReadback;                   //!< PMT HV readback reading from DCU, calibrated
    // Vars obtained from datadN.status
    var $countRateCpm;                 //!< Raw count rate in cps from datad hardware (BoundedNum)
    var $pmtControlState;              //!< PMT control state( LOCKED, SEARCH, ADJUST )
    var $pmtVoltage;                   //!< PMT voltage (BoundedNum)
    var $coarseDAC;                    //!< Coarse DAC value (BoundedNum)
    var $fineDAC;                      //!< Fine DAC value (BoundedNum)

    //! Constructor.

    function DetectorStatus(
    $detectorID, //!< Detector ID we use.
            $anConf, //!< Loaded configfile object for analyzer.conf
            $uiConf                        //!< Loaded configfile object for ui.conf
    ) {
        $this->anConf = $anConf;
        $this->uiConf = $uiConf;

        // Object metadata
        $this->bHaveStatus = FALSE;

        $this->daemonRunning = new StatusItem(
                'datadRunning', 'Daemon running', 'Daemon Running', 'daemon running', new BoundedBoolean(TRUE, STATUS_EXCEPTION_WARN));

        //
        // Configuration data
        //
        
        $this->detectorID = new StatusItem(
                'detectorID', 'Detector ID', 'Detector ID', 'detector ID', NULL);
        $this->detectorID->setValue($detectorID);

        $this->detectorName = new StatusItem(
                'detectorName', 'Detector name', 'Detector Name', 'detector name', NULL);
        $this->useResults = new StatusItem(
                'useResults', 'Use results', 'Use Results', 'use results', new BoundedBoolean(TRUE, STATUS_EXCEPTION_WARN));
        $this->analysisResponseSet = new StatusItem(
                'analysisResponse', 'Analysis response', 'Analysis Response', 'analysis response', NULL);
        $this->alignmentResponseSet = new StatusItem(
                'alignmentResponse', 'Alignment response', 'Alignment Response', 'alignment response', NULL);
        $ctlModeKwds = array(AN_CONF_DATAD_ACTIVE_PMT_MODE_FINE_TRACK);
        $this->pmtControlMode = new StatusItem(
                'pmtControlMode', 'PMT control mode', 'PMT Control Mode', 'PMT control mode', new BoundedKeyword($ctlModeKwds, NULL, STATUS_EXCEPTION_WARN));

        //
        // Alignment status items
        // Numeric limits are all configurable in ui.conf
        //
        $uiConf->setPath("/" . UI_CONF_STATUS_LIMITS_SECT);
        $crlb = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_COUNTRATE_LBOUND, null);
        $hpkub = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_HFWHM_UBOUND, null);
        $gdslb = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_GDS_LBOUND, null);
        $hrawnom = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_HPEAKRAW_NOMINAL, 99.5);
        $hrawmaxd = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_HPEAKRAW_MAXDIFF, null);
        if (( $hrawmaxd != null ) && ( $hrawnom != null )) {
            $hrawlb = $hrawnom - $hrawmaxd;
            $hrawub = $hrawnom + $hrawmaxd;
        } else {
            $hrawlb = null;
            $hrawub = null;
        }
        $algainmaxd = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_ALIGNGAIN_MAXDIFF, null);
        if ($algainmaxd != null) {
            $algainlb = 1.0 - $algainmaxd;
            $algainub = 1.0 + $algainmaxd;
        } else {
            $algainlb = null;
            $algainub = null;
        }
        $alchisqub = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_ALIGNCHISQR_UBOUND, null);
        $anchisqub = $alchisqub;

        $globoffnom = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_GLOBALOFFSET_NOMINAL, 0);
        $globoffmaxd = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_GLOBALOFFSET_MAXDIFF, null);
        if (( $globoffmaxd != null ) && ( $globoffnom != null )) {
            $globofflb = $globoffnom - $globoffmaxd;
            $globoffub = $globoffnom + $globoffmaxd;
        } else {
            $globofflb = null;
            $globoffub = null;
        }
        $hvcmdlb = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_HVCMD_LBOUND, null);
        $hvcmdub = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_HVCMD_UBOUND, null);
        $beltspdlb = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_BELTSPEED_LBOUND, null);
        $beltspdub = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_BELTSPEED_UBOUND, null);
        $massflowlb = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_MASSFLOW_LBOUND, null);
        $massflowub = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_MASSFLOW_UBOUND, null);
        $dettempnom = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_DETTEMP_NOMINAL, 40.0);
        $dettempmaxd = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_DETTEMP_MAXDIFF, null);
        if (( $dettempmaxd != null ) && ( $dettempnom != null )) {
            $dettemplb = $dettempnom - $dettempmaxd;
            $dettempub = $dettempnom + $dettempmaxd;
        } else {
            $dettemplb = null;
            $dettempub = null;
        }
        $hvrdbklb = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_HVREADBACK_LBOUND, null);
        $hvrdbkub = $uiConf->readEntry(UI_CONF_STATUS_LIMITS_HVREADBACK_UBOUND, null);

        $this->countRateCpm = new StatusItem(
                'countRateCpm', 'Count rate (raw)', 'Count Rate (raw)', 'count rate (raw)', new BoundedNum($crlb, NULL, STATUS_EXCEPTION_ERROR), NULL, "cpm");
        $this->countRateCpmAlignment = new StatusItem(
                'countRateCpmAlignment', 'Count rate (aligned)', 'Count Rate (aligned)', 'count rate (aligned)', new BoundedNum(NULL, NULL, STATUS_EXCEPTION_WARN), NULL, "cpm");
        $this->h_fwhm = new StatusItem(
                'HFWHM', 'H peak FWHM', 'H Peak FWHM', 'H peak FWHM', new BoundedNum(NULL, $hpkub, STATUS_EXCEPTION_WARN), NULL, "%");
        $this->goodDataSecs = new StatusItem(
                'goodDataSecs', 'Good data secs', 'Good Data Secs', 'good data secs', new BoundedNum($gdslb, 60, STATUS_EXCEPTION_WARN));
        $this->h_rawChan = new StatusItem(
                'hRawChan', 'H peak raw channel', 'H Peak Raw Channel', 'H peak raw channel', new BoundedNum($hrawlb, $hrawub, STATUS_EXCEPTION_WARN));
        $this->alignmentGain = new StatusItem(
                'alignmentGain', 'Alignment gain', 'Alignment Gain', 'alignment gain', new BoundedNum($algainlb, $algainub, STATUS_EXCEPTION_WARN));

        $this->alignmentOffset = new StatusItem(
                'alignmentOffset', 'Alignment offset', 'Alignment Offset', 'alignment offset', new BoundedNum(NULL, NULL, STATUS_EXCEPTION_INFO));

        $this->alignmentChisqr = new StatusItem(
                'alignmentChisqr', 'Alignment ChiSqr', 'Alignment ChiSqr', 'alignment ChiSqr', new BoundedNum(NULL, $alchisqub, STATUS_EXCEPTION_INFO));

        $this->analysisChisqr = new StatusItem(
                'analysisChisqr', 'Analysis ChiSqr', 'Analysis ChiSqr', 'analysis ChiSqr', new BoundedNum(NULL, $anchisqub, STATUS_EXCEPTION_INFO));
        $this->globalOffset = new StatusItem(
                'globalOffset', 'Global offset solved', 'Global Offset Solved', 'global offset solved', new BoundedNum($globofflb, $globoffub, STATUS_EXCEPTION_WARN));

        $this->globalOffsetConfig = new StatusItem(
                'globalOffsetConfig', 'Global offset config', 'Global Offset Config', 'global offset config', new BoundedNum($globofflb, $globoffub, STATUS_EXCEPTION_WARN));

        $this->pmtVoltage = new StatusItem(
                'pmtVoltage', 'PMT voltage cmd', 'PMT Voltage Cmd', 'PMT voltage cmd', new BoundedNum($hvcmdlb, $hvcmdub, STATUS_EXCEPTION_WARN), NULL, "V");
        $this->coarseDAC = new StatusItem(
                'coarseDAC', 'Coarse DAC', 'Coarse DAC', 'coarse DAC', NULL);
        $this->fineDAC = new StatusItem(
                'fineDAC', 'Fine DAC', 'Fine DAC', 'fine DAC', NULL);
        $this->beltSpeed = new StatusItem(
                'beltSpeed', 'Belt speed', 'Belt Speed', 'belt speed', new BoundedNum(NULL, NULL, STATUS_EXCEPTION_WARN), NULL, "fpm");
        $this->massFlow = new StatusItem(
                'massFlow', 'Mass flow', 'Mass Flow', 'mass flow', new BoundedNum(NULL, NULL, STATUS_EXCEPTION_WARN), NULL, "tph");
        $this->plcMoistureIn = new StatusItem(
                'plcMoistureIn', 'PLC moisture input', 'PLC Moisture Input', 'PLC moisture input', new BoundedNum(NULL, NULL, STATUS_EXCEPTION_WARN), NULL, "%");
        $this->alignedOK = new StatusItem(
                'alignedOK', 'Aligned', 'Aligned', 'aligned', new BoundedBoolean(TRUE, STATUS_EXCEPTION_WARN));
        // PMT control state.  'SEARCHING' and 'UNKNOWN' are exception states.
        $this->pmtControlState = new StatusItem(
                'pmtControlState', 'PMT control state', 'PMT Control State', 'PMT control state', new BoundedKeyword(array('LOCKED', 'ADJUSTING'), NULL, STATUS_EXCEPTION_WARN));
        $this->analyzeCmdIssued = new StatusItem(
                'analyzeCmd', 'Analyze cmd', 'Analyze Cmd', 'analyze cmd', NULL);
        $this->detectorTemp = new StatusItem(
                'detectorTemp', 'Detector temp', 'Detector Temp', 'detector temp', new BoundedNum($dettemplb, $dettempub, STATUS_EXCEPTION_ERROR), NULL, "C");
        $this->HVReadback = new StatusItem(
                'HVReadback', 'PMT voltage readback', 'PMT Voltage Readback', 'PMT voltage readback', new BoundedNum($hvrdbklb, $hvrdbkub, STATUS_EXCEPTION_WARN), NULL, "V");
    }

//DetectorStatus constructor
    //------------------------------------------------------------------------------------
    // getStatus - extracts status info for this detector from a loaded AnalysisObj
    //------------------------------------------------------------------------------------
    //! Extracts status info for this detector from configs and alignment data from a
    //! previously run AnalysisObj.  The results are stored in StatusItem member vars.
    //!
    function getStatus(
    $aObj                          //!< Pre-run AnalysisObj with alignment info for all detectors.
    ) {
        // Results from the analysisObj
        $this->analyzeCmdIssued->setValue($aObj->issuedCmds[$this->detectorID->getValue()]);

        $uiConf = new ConfigFile();
        $uiConf->load(UI_CONF);

        //-----------------------------------------------------------------------------
        // Configuration items from analyzer.conf
        //-----------------------------------------------------------------------------
        $anConf = new ConfigFile();
        $anConf->load(ANALYZER_DOT_CONF_FILENAME);
        $detID = $this->detectorID->getValue();
        $anConf->setPath("/" . $detID);

        $this->detectorName->setValue($anConf->readEntry(AN_CONF_DATAD_DISPLAY_NAME, ""));
        $this->pmtControlMode->setValue($anConf->readEntry(AN_CONF_DATAD_ACTIVE_PMT_MODE, ""));
        $this->useResults->setValue(chkForTrue($anConf->readEntry(AN_CONF_DATAD_USE_RESULTS, null)));
        $this->analysisResponseSet->setValue($anConf->readEntry(AN_CONF_DATAD_ANALYSIS_RESPONSE));
        $this->alignmentResponseSet->setValue($anConf->readEntry(AN_CONF_DATAD_ALIGNMENT_RESPONSE));
        $this->globalOffsetConfig->setValue($anConf->readEntry(AN_CONF_DATAD_GLOBAL_OFFSET_EST_CHANS, 0));

        //-----------------------------------------------------------------------------
        // Status items from datadN.status
        //-----------------------------------------------------------------------------
        $datadStatusFile = SABIA_PATH . $this->detectorID->getValue() . ".status";
        // Make sure status file exists and is up to date to avoid getting blank pmtControlState etc.
        // when datad has never run.
        if (file_exists($datadStatusFile) && fileIsUpToDate($datadStatusFile, 60)) {
            $datadStat = new ConfigFile();
            $datadStat->load($datadStatusFile);
            $datadStat->setPath("/" . DATAD_STATUS_SECT);

            $this->pmtControlState->setValue($datadStat->readEntry(DATAD_STATUS_ACTIVE_PMT_STATE, "UNKNOWN"));
            $this->countRateCpm->setValue(SECS_PER_MIN * $datadStat->readEntry(DATAD_STATUS_COUNT_RATE_CPS, 0));
            $this->pmtVoltage->setValue($datadStat->readEntry(DATAD_STATUS_OUTPUT_PMT_VOLTAGE, 0));
            $this->coarseDAC->setValue($datadStat->readEntry(DATAD_STATUS_HV_DAC_SETTING, 0));
            $this->fineDAC->setValue($datadStat->readEntry(DATAD_STATUS_HV_FINE_DAC_SETTING, 0));
        } else {
            $this->pmtControlState->setValue("UNKNOWN");
            $this->countRateCpm->setValue(0);
            $this->pmtVoltage->setValue(0);
            $this->coarseDAC->setValue(0);
            $this->fineDAC->setValue(0);
        }

        //-----------------------------------------------------------------------------
        // Items from AnalysisObj->parsedResultsArray
        //-----------------------------------------------------------------------------
        // These are per-detector AnalysisResult objects indexed as [detID][rawResultIndx]
        if (!empty($aObj->parsedResultsArray[$this->detectorID->getValue()][0])) {
            $this->analysisChisqr->setValue($aObj->parsedResultsArray[$this->detectorID->getValue()][0]->chiSquare);
        }

        //-----------------------------------------------------------------------------
        // items pulled from AnalysisObj->alignResults
        //-----------------------------------------------------------------------------
        // Must have a properly filled out AnalysisObj for the remaining items
        // that need it.  Many of these will not be available if no datad is running.
        if (empty($aObj->alignResults[$this->detectorID->getValue()]))
            return FALSE;
        $nrslts = $aObj->alignResults[$this->detectorID->getValue()]->GetNumResults();
        if ($nrslts <= 0)
            return FALSE;
        $alignRslt = $aObj->alignResults[$this->detectorID->getValue()]->GetAlignmentEntry($nrslts - 1);
        if ($alignRslt === FALSE)
            return FALSE;

        if ($alignRslt->GetDataIntegSecs() != 0)
            $this->countRateCpmAlignment->setValue($alignRslt->GetTotalCounts() / $alignRslt->GetDataIntegSecs() * SECS_PER_MIN);
        else
            $this->countRateCpmAlignment->setValue(0);
        $this->alignedOK->setValue($alignRslt->GetAlignedOK() != 0);
        $this->goodDataSecs->setValue($alignRslt->GetDataIntegSecs());
        $this->h_fwhm->setValue($alignRslt->GetHPeakFWHMPct());
        $this->h_rawChan->setValue($alignRslt->GetHPeakRawChan());

        //ABH2172014
        $this->alignmentGain->setValue($alignRslt->GetAlignGain());
        $this->alignmentOffset->setValue($alignRslt->GetAlignOffset());
        $this->alignmentChisqr->setValue($alignRslt->GetMlrChisqr());

        $this->globalOffset->setValue($alignRslt->GetMlrGlobalOffset());
        $this->detectorTemp->setValue($alignRslt->GetDetectorTemp());
        $this->HVReadback->setValue($alignRslt->GetHVReadback());
        $this->beltSpeed->setValue($alignRslt->GetObaBeltSpeedFpm());
        $this->massFlow->setValue($alignRslt->GetObaMassflowTph());
        $this->plcMoistureIn->setValue($alignRslt->GetObaMoisturePct());

        $this->bHaveStatus = TRUE;
        return TRUE;
    }

//getStatus
    //------------------------------------------------------------------------------------
    // getExceptionList - builds list of StatusItemExceptions
    //------------------------------------------------------------------------------------
    //! Adds exceptions for out-of-range parameters to the given list of
    //! StatusItemException objects.
    function getExceptionList(
    &$excpList                      //! List of exceptions that we add to (IN/OUT)
    ) {
        if (!isset($excpList))
            $excpList = array();

        // Run StatusItem installed range checkers
        $detName = $this->detectorName->getValue();
        $this->pmtControlMode->checkBounds($excpList, $detName);
        $this->pmtControlState->checkBounds($excpList, $detName);
        $this->daemonRunning->checkBounds($excpList, $detName);
        $this->useResults->checkBounds($excpList, $detName);
        $this->alignedOK->checkBounds($excpList, $detName);
        $this->countRateCpm->checkBounds($excpList, $detName);
        $this->countRateCpmAlignment->checkBounds($excpList, $detName);
        $this->goodDataSecs->checkBounds($excpList, $detName);
        $this->h_fwhm->checkBounds($excpList, $detName);
        $this->h_rawChan->checkBounds($excpList, $detName);
        $this->alignmentGain->checkBounds($excpList, $detName);
        $this->alignmentOffset->checkBounds($excpList, $detName);
        $this->alignmentChisqr->checkBounds($excpList, $detName);
        $this->analysisChisqr->checkBounds($excpList, $detName);
        $this->globalOffset->checkBounds($excpList, $detName);
        $this->globalOffsetConfig->checkBounds($excpList, $detName);
        $this->pmtVoltage->checkBounds($excpList, $detName);
        $this->coarseDAC->checkBounds($excpList, $detName);
        $this->fineDAC->checkBounds($excpList, $detName);
        $this->beltSpeed->checkBounds($excpList, $detName);
        $this->massFlow->checkBounds($excpList, $detName);
        $this->plcMoistureIn->checkBounds($excpList, $detName);
        $this->detectorTemp->checkBounds($excpList, $detName);
        $this->HVReadback->checkBounds($excpList, $detName);
    }

}

//DetectorStatus
//-----------------------------------------------------------------------------------------
// System status object class
//-----------------------------------------------------------------------------------------
//! Top level class for system status.
//!
//! Usage:
//! \beginverbatim
//! $st = new SystemStatus();
//! $st->getStatus();
//! ...grab and format status results as desired for display...or...
//! $excpList = $st->getExceptionList();     // get current list of status errors
//! \endverbatim
//!
//
class SystemStatus {

    // Data
    var $numConfiguredDetectors;       //!< How many detectors are defined in analyzer.conf
    var $detectorIDArray;              //!< Array of detector ID's defined in analyzer.conf
    var $detectorStatus;               //!< Array [detectorID] of DetectorStatus objects
    var $plcStatus;                    //!< PlcStatus object
    var $daemonStatus;                 //!< Array[daemonName] of daemon status entries (not objects)
    var $emailNotificationList;        //!< Comma-separated list of email addresses to notify on errors (maybe will end up in a higher-level notifier object)
    var $statusStartTimetag;           //!< Time value (time_t) at which status collection began
    var $tagSampleID;                  //!< ID tag of tagged sample (tag string 1).  IDLE means none in progress
    var $tagSampleOperator;            //!< Operator ID of current tagged sample (tag string 2)
    var $analysisErrorStrs;            //!< Analysis error strings array from our 1-min analysis
    var $licenseFilePath;              //!< License file path
    var $licenseEndDateStr;            //!< License end date string (UTC). Empty if none exists or indefinite license.
    var $licenseDaysLeft;              //!< Days left on license (integer).  May be negative if license has expired.
    var $licenseServerType;            //!< Licensed server type (production/development)
    // Performance timing vars
    var $statusStartMicrotime;         //!< Result of getmicrotime() at start of status collection.
    var $statusEndMicrotime;           //!< Result of getmicrotime() at end of status collection.
    var $statusElapsedSecs;            //!< Elapsed time to collect status - difference of end and start microtime readings.

    //! Constructor

    function SystemStatus() {

        $this->licenseFilePath = new StatusItem(
                'licenseFilePath', 'License file', 'License File', 'license file', new BoundedKeyword(NULL, array('Missing', 'MISSING'), STATUS_EXCEPTION_ERROR));
        $this->licenseEndDateStr = new StatusItem(
                'licenseEndDateStr', 'End date', 'End Date', 'end date', NULL);
        $this->licenseDaysLeft = new StatusItem(
                'licenseDaysLeft', 'Days remaining', 'Days Remaining', 'days remaining', new BoundedNum(30, NULL, STATUS_EXCEPTION_WARN));
        $this->licenseServerType = new StatusItem(
                'licenseServerType', 'Server type', 'Server Type', 'server type', NULL);
    }

    //-------------------------------------------------------------------------------------
    // getStatus - reads all system status items
    //-------------------------------------------------------------------------------------
    //! Loads member vars with current system status.  This is the Big Kahuna and does all the work
    //! of gathering the status info for the overall system and for each configured detector.
    //! This currently takes 400-500 msec to run since it has to execute a short analysis.
    //!
    //! \returns true if status gathering was successful, false otherwise.
    function getStatus() {
        $anConf = new ConfigFile();
        $anConf->load(ANALYZER_DOT_CONF_FILENAME);
        $anConf->setPath("/" . AN_CONF_SYSTEM_SECT);

        $uiConf = new ConfigFile();
        $uiConf->load(UI_CONF);
        $uiConf->setPath("/");

        $this->numConfiguredDetectors = $anConf->readEntry(AN_CONF_SYSTEM_NUM_DETECTORS, 0);
        $this->statusStartMicrotime = getmicrotime();
        $this->statusStartTimetag = time();
        $anConf->setPath("");
        $this->detectorIDArray = $anConf->findMatchingSubgroups("/^datad[0-9]+/");
        $this->daemonStatus = getDaemonStatus();
        if (!empty($this->detectorIDArray)) {
            $anConf->setPath("/" . $this->detectorIDArray[0]);
            $this->tagSampleID = $anConf->readEntry(AN_CONF_DATAD_IDSTRING_1, "");
            $this->tagSampleOperator = $anConf->readEntry(AN_CONF_DATAD_IDSTRING_2, "");
        } else {
            $this->tagSampleID = "(unknown - no detectors!)";
            $this->tagSampleOperator = "(unknown - no detectors!)";
        }

        //! \todo Get software versions
        //! \todo Get RAID status
        //! \todo Get filesystem status (free disk)
        // Get license subsystem status
        $this->licenseFilePath->setValue(findLicenseFile(SABIA_PATH));
        $this->licenseEndDateStr->setValue("");
        $this->licenseDaysLeft->setValue(0);
        if ($this->licenseFilePath->getValue() != "") {
            $this->licenseEndDateStr->setValue(getLicenseEndDate($this->licenseFilePath->getValue()));
            $this->licenseServerType->setValue(getLicenseServerType($this->licenseFilePath->getValue()));
            if ($this->licenseEndDateStr->getValue() != "") {
                // save the default timezone
                $defaultTimezone = date_default_timezone_get();

                // temporarily set the default timezone
                date_default_timezone_set('UTC');

                // get the date
                $todayTimeStamp = strtotime(date("Y-m-j"));

                // restore the default timezone
                date_default_timezone_set($defaultTimezone);

                $endDateTimeStamp = strtotime($this->licenseEndDateStr->getValue());

                $this->licenseDaysLeft->setValue(floor(($endDateTimeStamp - $todayTimeStamp) / SECS_PER_DAY));
                // force severity from warning up to error if license has expired completely
                if ($this->licenseDaysLeft->getValue() <= 0) {
                    $this->licenseDaysLeft->setSeverity(STATUS_EXCEPTION_ERROR);
                }
            } else {
                $this->licenseEndDateStr->setValue("None");
                $this->licenseDaysLeft->setValue(10000);
            }
        } else {
            // Force "MISSING" value if license file is absent to cause an error state
            $this->licenseFilePath->setValue("MISSING");
        }

        // Create detector status objects (removing previous entries since detector list may change)
        unset($this->detectorStatus);
        foreach ($this->detectorIDArray as $detID) {
            $this->detectorStatus[$detID] = new DetectorStatus($detID, $anConf, $uiConf);
            if (!empty($this->daemonStatus[$detID])) {
                if ($this->daemonStatus[$detID] == 1)
                    $this->detectorStatus[$detID]->daemonRunning->setValue(TRUE);
            }
        }

        // Run a trivial analysis to get info for all detectors
        // Note that rawdata filters are suppressed here
        $aObj = new analysisObj(0, SECS_PER_MIN, SECS_PER_MIN, SECS_PER_MIN);

        $aObj->forceEchoTic = "0";
        $aObj->rawdataFilters->suppressAll();
        $anSuccess = $aObj->getParsedAnalysis();

        // Read per-detector info
        foreach ($this->detectorIDArray as $detID) {
            $this->detectorStatus[$detID]->getStatus($aObj);
        }

        // Get PLC status - currently cannot fail
        $this->plcStatus = new PlcStatus($anConf, $uiConf, $this->daemonStatus);
        $this->plcStatus->getStatus();

        $this->statusEndMicrotime = getmicrotime();
        $this->statusElapsedSecs = $this->statusEndMicrotime - $this->statusStartMicrotime;

        $this->analysisErrorStrs = $aObj->getErrorStrsArr();
        if (!empty($this->analysisErrorStrs))
            $anSuccess = false;

        return $anSuccess;
    }

    //! Returns a list of exception condition objects describing status items that are out of bounds.
    function getExceptionList() {
        $excpList = array();

        $this->licenseFilePath->checkBounds($excpList);
        $this->licenseDaysLeft->checkBounds($excpList);

        //! \todo Complain if PLCD installed but not running, numdets == 0, etc.
        // Check PLC params, but only if PLCD is running.
        // Check detector-specific params
        foreach ($this->detectorIDArray as $detID) {
            $this->detectorStatus[$detID]->getExceptionList($excpList);
        }
        return $excpList;
    }

    //! Tells whether a tagged sample operation is in progress
    function isTaggedSampleActive() {
        return ($this->tagSampleID != IDLE_TAG );
    }

}

// Test code for SystemStatus
if (1 == 0) {
    $stat = new SystemStatus();
    $stat->getStatus();
    print_r($stat);
    $excpList = $stat->getExceptionList();
    echo "Exception list:\n";
    print_r($excpList);
}
?>