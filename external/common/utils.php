<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function getDBName() {
    $dbstr = $GLOBALS['db'];

    if (PRODUCT_NAME_VERSION == 'helios1')
        $dbhostAr = explode('=', $dbstr['connectionString']);
    else
        $dbhostAr = explode('=', $dbstr['dsn']);

    $dbtypeAr = explode(':',$dbhostAr[0]);
    $dbtype = $dbtypeAr[0];
    $dbhost = $dbtypeAr[1];
    $dbName = $dbhostAr[2];
    return $dbName;
}

function connectDB($log) {
    $dbstr = $GLOBALS['db'];

    if (PRODUCT_NAME_VERSION == 'helios1')
        $dbhostAr = explode('=', $dbstr['connectionString']);
    else
        $dbhostAr = explode('=', $dbstr['dsn']);

    $dbtypeAr = explode(':',$dbhostAr[0]);
    $dbtype = $dbtypeAr[0];
    $dbhost = $dbtypeAr[1];
    $dbName = $dbhostAr[2];
    
    $dbhost = "localhost";//temp
    $dbusername = $dbstr['username'];
    $dbpassword = $dbstr['password'];
   // $dbName = 'sabia_helios_db_2_w';
    
    $con = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbName);
    // Check connection
    if (mysqli_connect_errno()) {
        $log->fatalSkipDB("Failed to connect to MySQL: " . mysqli_connect_error());
        exit(1);
    }

  //  $log->trace("Connected to db $dbName " . mysqli_connect_error());

    return $con;
}

function disconnectDB($log, $con) {
    mysqli_close($con);
//    $log->traceSkipDB("disconnectDB exit");
}

class LabUtility {

    public static function stdDeviation($aValues, $bSample = false) {
        $fMean = array_sum($aValues) / count($aValues);
        $fVariance = 0.0;
        foreach ($aValues as $i) {
            $fVariance += pow($i - $fMean, 2);
        }
        $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
        return (float) sqrt($fVariance);
    }

}




?>
