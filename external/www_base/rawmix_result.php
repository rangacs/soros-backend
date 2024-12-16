#!/usr/bin/php
<?php
        require_once 'defines.php';
        require_once 'analysisObj.php';
        require_once 'analysisresult.php';

function getRawMixFromDB() {

		$rawMixParams=array(
				"Feeder1_cmd_m" , 	
				"Feeder2_cmd_m" ,   
				"Feeder3_cmd_m" ,   
				"Feeder4_cmd_m" ,   
				"Feeder5_cmd_m" ,   
				"Feeder6_cmd_m" ,   
				"Feeder7_cmd_m" ,		
		);
		
        $anConf = new ConfigFile();
        $anConf->load( ANALYZER_CONF );

	$dbhost 	= "localhost";
	$dbusername 	= "root";
	$dbpassword 	= "mysqlRoot" ;
	$dbName 	= "sabia_helios_v1_m2_0";
	$returnArray = array();
	$masterModeOn = 0;	
	$AUTOModeOn = 0;

        $con=mysqli_connect($dbhost,$dbusername,$dbpassword,$dbName);
        // Check connection
        if (mysqli_connect_errno())
        {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                exit(1);
        }
                //Get the Automatic Mode Values.

                $sSettingsQuery3 = "SELECT varValue FROM `rm_settings` WHERE  `varName` ='AUTOMODE'";
                if(0)
                        echo    $sSettingsQuery3 ."\n";

                $result3 = mysqli_query($con,$sSettingsQuery3);
                if(($result3)) {
                    while($row3 = $result3->fetch_array()) {
                                $AUTOModeOn = (int)$row3["varValue"];
                    }
                }		
		//Get the Master Control Mode Values.

		$sSettingsQuery2 = "SELECT varValue FROM `rm_settings` WHERE  `varName` ='MASTER_CONTROL_MODE'";
		if(0)
			echo 	$sSettingsQuery2 ."\n";
				
		$result2 = mysqli_query($con,$sSettingsQuery2);	
		if(($result2)) {
		    while($row2 = $result2->fetch_array()) {
				$masterModeOn = (int)$row2["varValue"];
		    }
		}				
		
		$noResultsFound = 0;
		$curTime                =  date("Y-m-d H:i:s");
        	$g_currenTime_l = strtotime($curTime) - (30 * 60);
		$g_currenTime_l = date("Y-m-d H:i:s", $g_currenTime_l);
		
		if($masterModeOn) {
			$colsStr = 	"`Feeder1_cmd_m`, `Feeder2_cmd_m`, `Feeder3_cmd_m`, `Feeder4_cmd_m`, `Feeder5_cmd_m`, `Feeder6_cmd_m`, `Feeder7_cmd_m`";
			
			$row = 0;
			$selPlcValsQuery =  "SELECT DISTINCT $colsStr FROM rm_ctrl_output_feedrates WHERE updated BETWEEN '" . $g_currenTime_l .
								"' and '" . $curTime . "' ORDER BY id DESC LIMIT 1";
			$result = mysqli_query($con,$selPlcValsQuery);		 	


			if($resultv = mysqli_fetch_assoc($result)) {
				for($row=0;$row<6;$row++) {

				if(!isset($resultv[$rawMixParams[$row]])){

					$tval = 0;
					}//if !isset
					else {
						$tval = $resultv[$rawMixParams[$row]];
					}//else

				//AS03222015 Danyang needs only int out
				//AS03222015 Danyang needs only int out
				$scaleFactorOtherThanLimeStoneFeeders = 1;
				if($row > 0)
					$scaleFactorOtherThanLimeStoneFeeders = 10;

					//$tval = $tval * 100 * $scaleFactorOtherThanLimeStoneFeeders;

				//Abhi 5-4-2018 changing _m to _a

				$tmpRow = str_replace("_m","_a",$rawMixParams[$row]);  //To change to _a
				//$tmpRow = ($rawMixParams[$row]);                       // To Change to _m

					$returnArray[$row] = array("rawmix_rslt_name"=>$tmpRow,"rawmix_rslt_value"=>$tval); 
			} //for loop 
		}else {
			echo "No output results found;\n";
			$noResultsFound = 1;
		}
		if(0)
			echo 	$selPlcValsQuery ."\n";

	    $newResults = 0;
		$sSettingsQuery = "SELECT varValue FROM `rm_settings` WHERE  `varName` =  'newRawResult'";
		if(0)
			echo 	$sSettingsQuery ."\n";
				
		$result = mysqli_query($con,$sSettingsQuery);	
	    if(($result)) {
		    while($row = $result->fetch_array()) {
		    	$newResults = (int)$row["varValue"];
		    }
	    }
    
		if($newResults) {
			$updQ = "UPDATE `rm_settings` SET  `varValue` =  '0' WHERE  `varName` =  'newRawResult'";
			if(0)
				echo 	$updQ ."\n";
					
			$result = mysqli_query($con,$updQ);	
		}		
		}//if masterModeOn
		
		$returnArray[$row] = array("rawmix_rslt_name"=>"SABIA_Control_MODE","rawmix_rslt_value"=>$AUTOModeOn); 		
		

		/*
		$cntsSql =  "SELECT `Count_Rate_(raw)` as counts_raw, `Count_Rate_(aligned)` as counts_aligned FROM analysis_status_info WHERE 1 ORDER BY LocalEndTime DESC LIMIT 2";
		$cntsReslt = mysqli_query($con,$cntsSql);
		//echo $sptsSql;

		$i =0;
		$k =0;
		$total_counts_raw = 0;
		$total_counts_aligned = 0;
		while($anArr = $cntsReslt->fetch_array()) {
			$counts_raw 	= str_replace(" cpm","",$anArr["counts_raw"]);
			$counts_aligned = str_replace(" cpm","",$anArr["counts_aligned"]);
			
			$counts_raw     = str_replace(",","",$anArr["counts_raw"]);
                        $counts_aligned = str_replace(",","",$anArr["counts_aligned"]);

			$total_counts_raw += (float)$counts_raw;
			$total_counts_aligned += (float)$counts_aligned;
		}
		
		if($total_counts_raw > 0) {
			$total_counts_raw = round(($total_counts_raw / 2),2);
			$row++;
			$returnArray[$row] = array("rawmix_rslt_name"=>"counts_raw","rawmix_rslt_value"=>sprintf("%4.2f",$total_counts_raw));
		}
		if ($total_counts_aligned > 0) {
			$total_counts_aligned = round(($total_counts_aligned / 2),2);
			$row++;
			$returnArray[$row] = array("rawmix_rslt_name"=>"counts_aligned","rawmix_rslt_value"=>sprintf("%4.2f",$total_counts_aligned));
		}
		*/

		mysqli_close($con);
		
		return $returnArray; 			
}

?>
