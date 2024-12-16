<?php

define("AP_RAPI_IP_ADDR","192.168.201.34");
define("PUB_AP_RAPI_IP_ADDR","182.71.201.126");
define("DEBUG_FLAG",0);
define("FILE_LOG",0);

function processRFIDTag() {
    global $db;
    //echo "processRFIDTag<br/>";  
    $sqlTagQueued 	  = "select * from rta_tag_index_queued  where tagGroupID= " . getRMSettingsVal("RFID_TAGGRP_ID",RFID_TAGGRP_ID);
    $queuedResult 	  = $db->query($sqlTagQueued);
    if(DEBUG_FLAG) echo  $sqlTagQueued . "<br/>";
    $totalQuedResult = array();

    while ($row = $queuedResult->fetch_assoc()) {
        //var_dump($row);
        
        $startTime 	= $row['LocalstartTime'];
        $endTime 	= $row['LocalendTime'];
        $tagName 	= $row['tagName'];
        $tagID 		= $row['tagID'];
		
		$checkDataExists = "SELECT totalTons, Ash,Moisture,BTU,Sulfur,LocalstartTime, LocalendTime FROM `analysis_A1_A2_Blend` ".
                           "WHERE LocalendTime >= '{$startTime}' AND LocalendTime <=  '{$endTime}' ";
        $chkQResult   = $db->query($checkDataExists);
        if(DEBUG_FLAG)echo $checkDataExists . " ** " . count($chkQResult) . "<br/>";
        if(DEBUG_FLAG) var_dump($chkQResult);
		
		if(($chkQResult->num_rows)>0) {

			$totalQueuedSql = "SELECT round(sum(totalTons),2)as totalTons, round(avg(Ash),2) as Ash, round(avg(Moisture),2) as Moisture,round(avg(BTU),2) as BTU ".
							  ",round(avg(Sulfur),2) as Sulfur,'{$tagName}' as tagName, '{$tagID}' as tagID,LocalstartTime as w_start_timestamp,".
							  " LocalendTime as w_end_timestamp  FROM `analysis_A1_A2_Blend` ".
							  "WHERE LocalendTime >= '{$startTime}' AND LocalendTime <=  '{$endTime}' AND TPH >0";
			$totalQResult   = $db->query($totalQueuedSql);
			if(DEBUG_FLAG)echo $totalQueuedSql . "<br/>";

			$totalQueuedResult[] = $totalQResult->fetch_assoc();
		}else {
			$totalQueuedResult[] =    array('totalTons' => 0,
									  'Ash' => 0,
									  'Moisture' => 0,
									  'BTU' => 0,
									  'Sulfur' => 0,
									  'tagName' => $tagName,
									  'tagID' => $tagID,
									  'w_start_timestamp' => $startTime,
									  'w_end_timestamp' => $endTime);
		}//else
        
    }
    
    return ($totalQueuedResult);    
}


function checkTolLimit($elemName,$elemVal) {
    global $db;
    $withinRange = 1;
    if(DEBUG_FLAG)echo "checkTolLimit $elemName,$elemVal<br/>";  
    $spTolChk 	  = "select * from wcl_rfid_set_points  where sp_name= '{$elemName}' AND sp_status=1 LIMIT 1";
    $spTolChRes 	  = $db->query($spTolChk);

    while ($row = $spTolChRes->fetch_assoc()) {
        //var_dump($row);
        
        $sp_name 	    = $row['sp_name'];
        $sp_value_num   = $row['sp_value_num'];
        $sp_tol 	    = $row['sp_tol'];
        $sp_status 	    = $row['sp_status'];

        if($elemName == $sp_name && ($elemVal >= round($sp_value_num - $sp_tol,2)) && ($elemVal <= round($sp_value_num + $sp_tol,2))) {
            $withinRange = "0";
        }
    }
    
    return ($withinRange);    
}

function getTripInfo($tagId) 
{
    global $db;
    $tripInfoAr = array();
    
    if(DEBUG_FLAG)echo "getTripInfo $tagId<br/>";  
    if(!isset($tagId)){
        echo "Problem loading Tag Settings\n<br/>";
    }
    
    $spTolChk 	  = "select * from wcl_truckInfo where w_tripID IN (select DISTINCT wcl_map_trId from wcl_truckTagMap where wcl_map_tagId= '{$tagId}')";
    $spTolChRes   = $db->query($spTolChk);
    if(DEBUG_FLAG)echo $spTolChk;
    
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
    
    unset($qArr["w_trId"]);
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
    if(DEBUG_FLAG)echo "adjustEndTime $tagId, $tagName<br/>";  
    if(!isset($tagId)){
        echo "Problem loading Tag Settings\n<br/>";
    }
    
    $newEndTime = date("Y-m-d H:i:s");		
    $updQuery 	= "UPDATE rta_tag_index_queued ".
                "SET LocalendTime='{$newEndTime}' WHERE tagID='{$tagId}' AND tagName='{$tagName}'";
    if(DEBUG_FLAG)echo ($updQuery);
    $result 	= $db->query($updQuery);
    if(DEBUG_FLAG)echo "UPDATED tag ($tagId, $tagName) to new EndTime = '{$newEndTime}' \n<br/>";
    return 1;
}


function getUnLoadConfirm() {
    $evntUnLoadConfirmJson = "";
    if(DEBUG_FLAG)echo "getUnLoadConfirm <br/>";
	$elemAr = array("Ash","Moisutre","Sulfur","BTU");

	$queuedTagsAr = processRFIDTag();
	if(DEBUG_FLAG)var_dump($queuedTagsAr);
	foreach($queuedTagsAr as $qTag) {
		if(DEBUG_FLAG)var_dump($qTag);
		$tripAr = getTripInfo($qTag["tagID"]);
		if(count($tripAr) <=0) continue;
		
		//ab1122021
		if($tripAr["w_chQty"] <= 0) 
			$tripAr["w_chQty"] = getRMSettingsVal("CHL_QTY", CHL_QTY);
		
		if ( ($qTag["totalTons"] >= ($tripAr["w_chQty"] - 0.5)) || 
			 //PB4 should be closed regardless
		     ($tripAr['w_unload_status'] == "PB-4") )  {
			//CloseTag
			if(adjustEndTime($qTag["tagID"],$qTag["tagName"])) {
				foreach($elemAr as $elem) {
					$qTag[$elem . "_tol_limit"] = checkTolLimit($elem,$qTag[$elem]);
				}//foreach
				
				if(count($tripAr) > 0) {
					
					$qTag["w_end_timestamp"] = date("Y-m-d H:i:s");
					$qTag = array_merge($tripAr, $qTag);
					if(DEBUG_FLAG)var_dump($qTag);
					$qTag = varCleanUp($qTag);
					if(DEBUG_FLAG)var_dump($qTag);
					$outputData[UNLOAD_CONFIRM_JSON_TAG] = $qTag;       

					// evntQCTripResultConfirm
				}
			}
		}//if
	}
	$evntUnLoadConfirmJson = json_encode($outputData); 
    return $evntUnLoadConfirmJson;
}//getUnLoadConfirm

function getRMSettingsVal($key, $defVal) {
    
    global $db;
    $sqlRmSettings = "SELECT varValue from rm_settings where varName='{$key}'";
    $result = $db->query($sqlRmSettings);
    $array = mysqli_fetch_array($result);
    
    return isset($array["varValue"]) ? ($array["varValue"]): $defVal;
}

function sendCurlPostData($evntUnLoadConfirmJson) {
    	global $db;
	if (!is_null($evntUnLoadConfirmJson) && (strlen($evntUnLoadConfirmJson) >0) && (isset($evntUnLoadConfirmJson)) && ($evntUnLoadConfirmJson != "null")) {
		echo "sendCurlPostData : " . $evntUnLoadConfirmJson . "\n\n";
		
		$url1 = getRMSettingsVal("AP_UNLOAD_CONFIRM_URL","http://".AP_RAPI_IP_ADDR.":8080/AutoPlantConnector/rest/qCResultConfirmService/qCResultConfirm");

		$curl = curl_init($url1);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $evntUnLoadConfirmJson);
		curl_setopt($curl, CURLOPT_TIMEOUT,2);

		$json_response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$info = "status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) . "<br/>";

		if ( $status > 300 ) {
			die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		}

		//curl_close($curl);
		if(!empty($json_response)) {
			$response = json_decode($json_response, true);
		
        		$dataDump = "INSERT INTO wcl_data_dump VALUES (NULL,1,1,102,'" . $json_response . "',now())";
		
    			$result = $db->query($dataDump);
			//var_dump($response);   
			
			if(FILE_LOG) {
				$myfile = fopen("rtdlog.txt", "a");
				fwrite($myfile, $json_response);
				fclose($myfile);
			}
		} 
		
	}
}//function 

?>
