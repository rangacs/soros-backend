<?php

$debugFlag = 0;
error_reporting(0);
//error_reporting(E_ALL);

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
require_once 'pageParseTools.php';
/*
require_once 'defines.php';
require_once 'configfile.php';
require_once 'parserslt.php';
require_once 'pageTools.php';
require_once 'pageParseTools.php';
require_once 'RAIDChk.php';
require_once 'headSection.php';
require_once 'bodySection.php';
require_once 'crawl_statusObj.php';
require_once 'licInfo.php';
*/
//date_default_timezone_set('America/Denver');
//------------------------------------------------------------------------------
// status_pg_main - renders system status page
//------------------------------------------------------------------------------
//! Emits system status page.
function write_plcd_to_db($dbCon) {
	global $debugFlag;
	$PLCD_STATUS = "/usr/local/sabia-ck/plcd.status";
	
	if($debugFlag)
		echo "We are in write_plcd_to_db;";
			
	$rawMixParams=array(
		"MASTER_CONTROL_MODE"=>  "RawMixParam51",		 /* CONTROL MODE		*/
		"src_1_m"  	 		 =>  "RawMixParam53",            /* 1 Feeder Stpnt		*/
		"src_1_sp" 	 		 =>  "RawMixParam54",             /* 1 Feeder Rate		*/
		"src_1_r"  	 		 =>  "RawMixParam55",            /*  1  Feeder Running		*/

		"src_2_m"  	 		 =>  "RawMixParam26",             /* 2 Feeder Stpnt		*/
		"src_2_sp" 	 		 =>  "RawMixParam27",             /* 2 Feeder Rate		*/
		"src_2_r"  	 		 =>  "RawMixParam28",            /*  2 Feeder Running		*/

		"src_3_m"  	 		 =>  "RawMixParam41",             /* 3 Feeder Stpnt		*/
		"src_3_sp" 	 		 =>  "RawMixParam42",             /* 3 Feeder Rate		*/
		"src_3_r"  	 		 =>  "RawMixParam43",             /* 3 Feeder Running		*/

		"src_4_m"  	 		 =>  "RawMixParam36",             /* 4  Feeder Stpnt		*/
		"src_4_sp" 	 		 =>  "RawMixParam37",             /* 4  Feeder Rate		*/
		"src_4_r"  	 		 =>  "RawMixParam38",            /* 4 Feeder Running		*/

		"src_5_m"  	 		 =>  "RawMixParam46",             /* 3 Feeder Stpnt		*/
		"src_5_sp" 	 		 =>  "RawMixParam47",             /* 3 Feeder Rate		*/
		"src_5_r"  	 		 =>  "RawMixParam48",             /* 3 Feeder Running		*/
	);
	$rmSettingsArray = array(
			"src_1_r"   =>  "SRC_1_STATUS",            /*  Feeder Running		*/
			"src_2_r"   =>  "SRC_2_STATUS",            /*  Feeder Running	*/
			"src_3_r"   =>  "SRC_3_STATUS",            /*   Feeder Running		*/
			"src_4_r"   =>  "SRC_4_STATUS",            /*  Feeder Running		*/
			"src_5_r"   =>  "SRC_5_STATUS",            /*  Feeder Running		*/
	//		"MASTER_CONTROL_MODE"  => "MASTER_CONTROL_MODE", /* Master Contorl Mode */				
	);
	//belt_speed_fpm /* Belt Scale Input	*/
	//mass_flow_tph /* Belt Speed Input	*/                           
	$bSpeed 	= readSabiaFile($PLCD_STATUS, "plcd_status", "belt_speed_fpm");		
	$mFlow 		= readSabiaFile($PLCD_STATUS, "plcd_status", "mass_flow_tph");		

	$statusArray[strtoupper("belt_speed_fpm")] = $bSpeed;
	$statusArray[strtoupper("mass_flow_tph")] = $mFlow;
	
	if($debugFlag)
		echo $bSpeed . "<br/>";
	if($debugFlag)
		echo $mFlow . "<br/>";
	
	$arrlength=count($rawMixParams);
	$colsStr = "";
	$valsStr = ""; 
	$valsArray  = array();
	$spvalsArray = array();
	$sVal = 0;
	$pVal = 0;
		
	foreach($rawMixParams as $col=>$val) {

	
		if($col == "MASTER_CONTROL_MODE"){
			$tVal   = readSabiaFile($PLCD_STATUS, "RawMix", $val);
                        $statusArray[$col] = (int)ABS($tVal);
                        $statusArray["MASTER_CONTROL_MODE"] = (int)ABS($tVal);
			continue;
		}
		$colsStr .= "`{$col}`,";

		$tVal 	= readSabiaFile($PLCD_STATUS, "RawMix", $val);		
		
		if($tVal >= 65520)
			$tVal =0;		
		
		 $valsStr .= "'".ROUND(ABS($tVal),2) ."',";
		
		if ((substr($col,-2) == "_r") && (isset($rmSettingsArray[$col])) ) {

			$statusArray[$rmSettingsArray[$col]] = ABS($tVal);
		}elseif ((substr($col,-2) == "_m")) {
			$sVal += $tVal;
			
			array_push($valsArray,$tVal);
		}elseif ((substr($col,-3) == "_sp")) {
			//$spVal += $tVal;
			
			array_push($spvalsArray,$tVal);
		}		
	}

	foreach($valsArray as $i=>$v) {
		$t = $valsArray[$i];
                if($sVal)
                    $valsArray[$i] = round((($t / $sVal) * 100), 2);
                else
                    $valsArray[$i] = 0;
	}
	
	$colsStr = substr($colsStr,0,-1);
	$valsStr = substr($valsStr,0,-1);

	$pquery = "SELECT product_id FROM rm_product_profile where default_profile = 1";
	$pId = 0;
    $result = mysqli_query($dbCon,$pquery);
	
    if(($result)) {
	    if($row = $result->fetch_array()) {
			$pId = $row["product_id"];
	    }
    }	
	$setPointFeedRatesCorrFlag = 0;
	$spCorQuery = "SELECT * FROM `rm_settings` WHERE varName = 'setPointFeedRateCorrection'";
	if($debugFlag)
		echo 	$spCorQuery ."\n";
	$rresult = mysqli_query($dbCon,$spCorQuery);
	if($rrow = $rresult->fetch_array()) {
		$setPointFeedRatesCorrFlag = $rrow["varValue"];
	}else
		$setPointFeedRatesCorrFlag = 0;
	$dateTime = date("Y-m-d H:i:s");	
	//Insert into feeder inputs
	$inQuery = "INSERT INTO `rm_source_feeder_inputs` (`src_id_fk`, {$colsStr}, `LocalendTime`) VALUES(1,{$valsStr},'{$dateTime}')";

	if($debugFlag)
		echo 	$inQuery;

	 $result = mysqli_query($dbCon,$inQuery);
	 $srcId  = 0;
	 
	 //Update rm_settings
	foreach($statusArray as $col=>$val) {
		$upQuery = "UPDATE `rm_settings` set `varValue` ='{$val}' WHERE varName = '{$col}'";
		if($debugFlag)
			echo 	$upQuery ."\n";
		$result = mysqli_query($dbCon,$upQuery);
		
		if (($pos = strpos($col, "STATUS")) > 0) {
			$srcId++;
			if($setPointFeedRatesCorrFlag)
				$upsrcQuery = "UPDATE `rm_source` set `src_status_mode` ={$val}, src_measured_feedrate=". $spvalsArray[$srcId-1].", src_actual_feedrate=". $valsArray[$srcId-1]." WHERE src_id = {$srcId} AND product_id=$pId ";
			else
				$upsrcQuery = "UPDATE `rm_source` set `src_status_mode` ={$val}, src_measured_feedrate=". $valsArray[$srcId-1].", src_actual_feedrate=". $valsArray[$srcId-1]." WHERE src_id = {$srcId} AND product_id=$pId ";
			if($debugFlag)
				echo 	$upsrcQuery ."\n";
			$result = mysqli_query($dbCon,$upsrcQuery);
		}		
	}
	echo "PLCD Information succesffully written to DB\n";	
		
	include_once "cronClass.php";
	$nLine = "*/XXX * * * * php /var/www/html/RHEA/rhea.php XXX \n";

	$newCron = new Crontab();
		
	$autoQuery = "SELECT * From rm_settings where varName like 'CRON%' ";
	$result = mysqli_query($dbCon,$autoQuery);
	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc()){
			extract($row);
			$cronAr[$row["varName"]] = $row["varValue"];

		}
	}
	
	if($cronAr["CRON_CHANGE_FLAG"] == 1 && in_array($cronAr["CRON_RUN_TIME"],array(1,3,5,10,15)) )
	{
                $newCron->removeAllJobs();

		$nLine = str_replace("XXX",$cronAr["CRON_RUN_TIME"],$nLine);
		$newCron->addJob($nLine);
		echo "Adding New Jobs to Crontab \n";
                $cArray = $newCron->getJobs();

		$autoQuery = "UPDATE rm_settings SET varValue=0 WHERE varName = 'CRON_CHANGE_FLAG' ";
		$result = mysqli_query($dbCon,$autoQuery);

                //$newCron->addJob("*/2 * * * * php /var/www/html/RHEA/rstarve.php 2                >/dev/null 2>&1");
                $newCron->addJob("*/1 * * * * php /var/www/html/crawl_status.php >> /var/www/html/Helios/tmp/runlog/rawmix-cron.html");
                $newCron->addJob("*/1 * * * * /usr/local/sabia-ck/bin/mastercron.sh 1");
                $newCron->addJob("*/5 * * * * /usr/local/sabia-ck/bin/mastercron.sh 5");
                $newCron->addJob("3 */1 * * * /usr/local/sabia-ck/bin/mastercron.sh hourly");
                $newCron->addJob("30 0 * * * /usr/local/sabia-ck/bin/mastercron.sh daily");
                $newCron->addJob("0 1 * * 7 /usr/local/sabia-ck/bin/mastercron.sh weekly");
        }	
}

$dbhost 	= "localhost";
$dbusername = "root";
$dbpassword = "mysqlRoot" ;
$dbName 	= "sabia_helios_v1_m2_1";

$dbCon=mysqli_connect($dbhost,$dbusername,$dbpassword,$dbName);
// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}

echo "DB Connection Successful!\n";


//Write PLCD Information to DB
write_plcd_to_db($dbCon);


//write_feeds_to_plcd($dbCon);

mysqli_close($dbCon);
// No newline after PHP close tag!
?>
