#!/usr/bin/php
<?php

//------------------------------------------------------------------------------
// plcd_analysis.php - interface for PLCD to PHP analysis engine
//------------------------------------------------------------------------------
// SABIA Proprietary.
// Copyright (c) 2009 by SABIA, Inc.  All rights reserved.
//
//! \file
//! Analysis request interface for use with legacy 1.X PLCD.  This is a little
//! like analysisRqst.php except that this file expected to be exec'd directly
//! by PLCD as a subprocess, instead of being invoked as a web service.
//!
//! The outputs from this are structured exactly like those from the legacy
//! 'cmdlinecalmult.php' and are written to stdout in this form:
//!
//!     [RESULTS]
//!     Ash: 12.938
//!     Moisture: 23.357
//!     SO2: 1.035
//!
//! <h2>Usage</h2>
//! \verbatim
//! /usr/bin/php -f plcd_analysis.php <timespan_secs>
//! \endverbatim
//!

require_once 'defines.php';
require_once 'analysisObj.php';
require_once 'analysisresult.php';
require_once 'displayresult.php';
require_once 'rawmix_result.php';
error_reporting(0);


$debug = 0; //switch for debug

// Validate command line args
if ( $argc != 2 )
{
    printf( "ERROR: wrong number of args (%d)!\n", $argc );
    printf( "argc = %d, PHP version = %s\n", $argc, phpversion() );
    printf( "  usage:  ./plcd_analysis.php <timespan>\n" );
    return -1;
}

$timespan = $argv[1];

if ( !is_numeric( $timespan ) )
{
    printf( "ERROR: timespan arg (%s) must be numeric!\n", $timespan );
    return -1;
}

$timespan = (integer)$timespan;

// Disallow timespan of zero since analysisObj will replace that with a default interval
if ( $timespan == 0 )
{
    printf( "ERROR: timespan cannot be zero!\n" );
    return -1;
}

if($debug)
	echo "before calling analyze\n";

$aObj = new analysisObj( 0, $timespan, $bulkAvg = $timespan, $incr = $timespan );
$aObj->forceEchoTic = "0";  // prevent stray echo output

if($debug)
	echo "after calling analyze\n";
// This obtains calibrated results averaged across all active detectors in combinedResultsArray
$success = $aObj->getCalibratedResults();


if($debug)
	echo "after getCalibratedResults\n";


if ( !empty( $aObj->errStr ) )
{
    printf( "ERROR: analysis failed!  Error text: %s\n", $aObj->errStr );
    return -1;
}

// Iterate the output DisplayResult array (combinedResultsArray[DET_ID_AVERAGE][0]) and emit results.
if ( empty( $aObj->combinedResultsArray[DET_ID_AVERAGE][0] ) )
{
    printf( "ERROR: no results returned from analysis!\n" );
    return -1;
}

printf( "[RESULTS]\n" );
foreach( $aObj->combinedResultsArray[DET_ID_AVERAGE][0] as $dispObj )
{
    printf( "comp: %s %12.3f\n",
            $dispObj->GetDispName(),
            $dispObj->GetValue() );
}

//
// send total tons
//
$disp_list = $aObj->combinedResultsArray[DET_ID_AVERAGE][0];

// get total tons for any/all display list items
foreach( $disp_list as $dispItem )
   $totalTons = $dispItem->GetAvgMassFlowTph() / 3600 * $dispItem->GetTimespan();

printf( "comp: %s %12.3f\n",
        "Tons",
        $totalTons);
//
//add rawmix inputs
//
if($debug)
	echo "before calling getRawMixFromDB\n";

$returnArray = getRawMixFromDB();
//var_dump($returnArray);

foreach( $returnArray as $result )
	printf( "comp: %s %s\n",$result["rawmix_rslt_name"], $result["rawmix_rslt_value"] );

return 0;
?>
