<?php

error_reporting(E_ALL);
$db = require '../../protected/config/db.php';
define("TMPDIR", "/var/www/html/tmp/");

//make true for testing
//$test = true;
/*
  $dbLocal = array(
  'db' => array(
  'connectionString' => 'mysql:host=localhost;dbname=sabia_helios_v1_db',
  'emulatePrepare' => true,
  'username' => 'root',
  'password' => 'mysqlRoot',
  'charset' => 'utf8',
  ),
  );
 */
global $db;
global $test;

function connectDB() {
    //$dbstr = $GLOBALS['dbLocal']['db'];
    $dbstr = $GLOBALS['db'];

    $dbhostAr = explode('=', $dbstr['connectionString']);
    $dbtypeAr = explode(':', $dbhostAr[0]);

    $dbtype = $dbtypeAr[0];
    $dbhost = $dbtypeAr[1];
    $dbName = $dbhostAr[2];

    $dbhost = "localhost"; //temp
    $dbusername = $dbstr['username'];
    $dbpassword = $dbstr['password'];
    // $dbName = 'sabia_helios_db_2_w';

    $con = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbName);
    // Check connection
    if (mysqli_connect_errno()) {
        echo("Failed to connect to MySQL: " . mysqli_connect_error());
        exit(1);
    }

    // echo("Connected to DB : $dbName <br/>" . mysqli_connect_error());

    return $con;
}

function disconnectDB($con) {
    mysqli_close($con);
//    $log->traceSkipDB("disconnectDB exit");
}

function deleteDir($dirPath) {
    if (!is_dir($dirPath)) {
        //echo("$dirPath must be a directory");
        return;
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function findNoOfRows($con, $db, $table, $where) {

    if ($where)
        $sQuery = "SELECT * FROM $db.$table where $where";
    else
        $sQuery = "SELECT * FROM $db.$table";

    if (0)
        echo($sQuery);

    $result = mysqli_query($con, $sQuery);
    if ($result && $result->num_rows)
        return $result->num_rows;
    else
        return 0;
}

function heliosDataDownloadUsingDate($con, $db, $start_date, $end_date, $tmpDir, &$dataFlag) {

    $zipfilearray = array();

    $sQuery = "SELECT * FROM rm_helios_download_tables_list where active = 1";
    if (0)
        echo($sQuery);

    $nodata = "";
    $result = mysqli_query($con, $sQuery);
    if ($result && $result->num_rows) {
        while ($row = $result->fetch_array()) {
            $tmpfile = $tmpDir . "/" . $row['TableName'] . ".sql";

            if (file_exists($tmpfile))
                unlink($tmpfile);

            //check number of rows
            if ($row['dateCol'] == "NA")
                $where = " LIMIT " . $row['RunPerHour'];
            else
                $where = $row['dateCol'] . " > \"" . $start_date . "\" AND " . $row['dateCol'] . " < \"" . $end_date . '"';
            $rows = findNoOfRows($con, $db, $row['TableName'], $where);

            $output = null;
            if ($rows > 0) {
//                $commandWithoutFile = "mysqldump -u root --password='mysqlRoot' " . $db . " --tables " . $row['TableName'] . " --where '" . $row['dateCol'] . " > \"" . $start_date . "\" AND " . $row['dateCol'] . " < \"" . $end_date . "\"';
                $command = "mysqldump -u root --password='mysqlRoot' " . $db . " --tables " . $row['TableName'] . " --where '" . $row['dateCol'] . " > \"" . $start_date . "\" AND " . $row['dateCol'] . " < \"" . $end_date . "\"' > " . $tmpfile;
                //echo ($command);
                $output = shell_exec($command);
                $zipfilearray[] = $tmpfile;
                //set to true when data is found
                $dataFlag = true;
                // echo $output; 
            }else{
                $nodata .="No Data in table: ".$row['TableName']." for ".$where."\n"; 
            }
        }
    } else {

        //echo("Query returned no results, Query is: $sQuery\n");
    }

    if ($nodata != "") {
        $tmpfile = $tmpDir . "/" . "nodata.txt";
        $myfile = file_put_contents($tmpfile, $nodata . PHP_EOL, FILE_APPEND | LOCK_EX);
        $zipfilearray[] = $tmpfile;
    }

    
    return $zipfilearray;
}

function zipAndDownload($zipFileName, $zipfilearray) {

    $Obj_zip = new ZipArchive();
    $dirname = "/temp/123";
    if ($Obj_zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

        foreach ($zipfilearray as $file) {
            $path = pathinfo($file);
            $relativeFile = $path['basename'];
            $dirname = $path['dirname'];
            $ret = $Obj_zip->addFile($file, $relativeFile);
        }
    }//if
    //close the zip file
    $ret = $Obj_zip->close();
    //delete folder along with all files
    if ($dirname != "/temp/123")
        deleteDir($path['dirname']);

    if (file_exists($zipFileName))
        return $zipFileName;
    else
        return false;
}

function main() {
    // $start_date = isset($_GET['startdate']) ? $_GET['startdate'] : '';
    // $end_date = isset($_GET['enddate']) ? $_GET['enddate'] : '';
    //  $start_date = "2018-11-11 08:30";
    //  $end_date = "2018-11-11 08:32";

    if ($GLOBALS['test'])
        $durationStr = '1';
    else
        $durationStr = isset($_POST['duration']) ? $_POST['duration'] : '';

//    $db = DB_NAME;
    $dbstr = $GLOBALS['db'];
    $dbhostAr = explode('=', $dbstr['connectionString']);
    $dbtypeAr = explode(':', $dbhostAr[0]);

    $dbtype = $dbtypeAr[0];
    $dbhost = $dbtypeAr[1];
    $db = $dbhostAr[2];

    $date = date('Y-m-d');
    $randomNo = rand(4, 9999);
    $tmpDir = TMPDIR;
    $tmpDir = $tmpDir . $date . "-" . $randomNo;


    if (file_exists($zipFileName))
        unlink($zipFileName);

    deleteDir($tmpDir);
    mkdir($tmpDir);
    chmod("$tmpDir", 0755);

    $con = connectDB();
//    $con = mysqli_connect("localhost", "root", "mysqlRoot") or die(mysql_error());
//    mysqli_select_db($con, $db) or die(mysql_error());
//find start and end date if its duration.
    $duration = (float) $durationStr;
    if ($duration) {
        //find current date
        if ($GLOBALS['test'])
            $end_date = "2018-12-11 08:42";
        else
            $end_date = "now";

        $endTimeTick = strtotime("$end_date");
        $startTimeTick = $endTimeTick - ($duration * 60 * 60);
        $start_date = date('Y-m-d H:i', $startTimeTick);
        $end_date = date('Y-m-d H:i', $endTimeTick);
    }

    $dataFlag = false;
    $zipfilearray = heliosDataDownloadUsingDate($con, $db, $start_date, $end_date, $tmpDir, $dataFlag);
    
    disconnectDB($con);

    if(!$dataFlag)
        $zipFileName = $tmpDir . "-NODATA.zip";
    else 
        $zipFileName = $tmpDir . ".zip";
        

    $fileS = false;

    if (!empty($zipfilearray))
        $fileS = zipAndDownload($zipFileName, $zipfilearray);

    //deleteDir($tmpDir);

    if (!file_exists($fileS)) {
        echo("No Data Found");
        // alert("No Data Found");
    } else {

        $type = filetype($fileS);
// Get a date and timestamp
        $today = date("F j, Y, g:i a");
        $time = time();
        $path = pathinfo($fileS);
//echo $path['dirname'];
        $filename = $path['basename'];
        //      echo "Filename to download " . $filename;


        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($fileS));

        readfile($fileS);

        //delete the zip file
        if (file_exists($fileS))
            unlink($fileS);
    }
}

if ($test) {
    main();
} else {


    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['download'])) {
        //     
        main();
    } else {
        $current_timedate = date('Y-m-d H:i');
        echo ' 
           <html>

            <head>
                <style id="compiled-css" type="text/css">
                    td {
                        border: 1px #DDD solid;
                        padding: 5px;
                        cursor: pointer;
                    }

                    .selected {
                        background-color: brown;
                        color: #FFF;
                    }
                </style>
                <script type="text/javascript">
                    function makeDownloaderVisible() {
                        var x = document.getElementById("downloadModule");
                        var login = document.getElementById("login_form");
                        if (x.style.display === "none") {
                            x.style.display = "block";
                            login.style.display = "none";
                        } else {
                            x.style.display = "none";
                            login.style.display = "block";
                        }
                    }

                    function check(form) /*function to check userid & password*/ {
                        /*the following code checkes whether the entered userid and password are matching*/
                        if (form.pswrd.value == "OvalOval@11") {
                            makeDownloaderVisible();
                        } else {
                            alert("Error Password or Username") /*displays error message*/
                        }
                    }

                    $(window).load(function() {

                        $("#table tr").click(function() {
                            $(this).addClass("selected").siblings().removeClass("selected");
                            var value = $(this).find("td:first").html();
                            alert(value);
                        });

                        $(".ok").on("click", function(e) {
                            alert($("#table tr.selected td:first").html());
                        });

                    });
                </script>
            </head>

            <body>
                <div id="login_form" style="display:block">
                    <form name="login">
                        <br/>
                        Password
                        <br/>
                        <input type="password" name="pswrd" />
                        <br/>
                        <br/>
                        <input type="button" onclick="check(this.form)" value="Login" />
                    </form>
                </div>

                <div id="downloadModule" style="display:none">
                    <br/>
                    Quick Download
                    <form action="index.php" method="post">
                        Current Server Time: ' . $current_timedate . '</br>
                        <select name="duration">
                            <option value="0.25"> 15 min </option>
                            <option value="1"> 1 hour </option>
                            <option value="2"> 2 hours </option>
                            <option value="4"> 4 hours </option>
                            <option value="12"> 12 hours </option>
                            <option value="24"> 24 hours </option>
                            <option value="168"> 1 Week </option>
                            <option value="720"> 1 Month </option>
                            <option value="4392"> 6 Months </option>
                            <option value="8784"> 12 Months </option>
                        </select>
                        <button type="submit" name="download">Download</button>
                    </form>
                </div>
                <!--
                                 <hr style="border: 2px solid grey;" />
                                 <div>
                                 <h4>Custom Download</h4>
                                 <form action="index.php" method="post">

                                    <select name="from">
                                         <option value = "">Tables</option>
                                         <option value = "predefined">Predefined</option>
                                         <option value = "specific_table">Specific Table</option>
                                     </select>

                                     <select name="from">
                                         <option value = "">From</option>
                                         <option value = "from_current">Current Date Time</option>
                                         <option value = "from_specific">Specific Date Time</option>
                                     </select>

                                     <select name="to">
                                         <option value = "">To</option>
                                         <option value = "to_duration">Duration</option>
                                         <option value = "to_specifi">Specific Date Time</option>
                                     </select>
                                     <br/>
                                     <br/>
                                     <button type="submit" name="download">Download</button>
                                 </form> 
                                 </div>
                                 <hr style="border: 2px solid grey;" />
                                 <!--
                                 <div>
                                 <h4>Configuration</h4>

                                 <table id="table">
                                     <tr>
                                 table 1
                                     </tr>
                                     <tr>
                                 table 2
                                     </tr>
                                     <tr>
                                 table 3
                                     </tr>
                                 </table>
                                 <input type="button" name="OK" class="ok" value="OK"/>
                                 </div>
                                 -->
            </body>

            </html>
';
    }
}


/*
  -- Adminer 4.5.0 MySQL dump

  SET NAMES utf8;
  SET time_zone = '+00:00';
  SET foreign_key_checks = 0;
  SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

  CREATE TABLE `rm_helios_download_tables_list` (
  `TableName` varchar(40) NOT NULL,
  `RunPerHour` int(11) NOT NULL,
  `dateCol` varchar(40) NOT NULL,
  `active` tinyint(4) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  INSERT INTO `rm_helios_download_tables_list` (`TableName`, `RunPerHour`, `dateCol`, `active`) VALUES
  ('rm_ctrl_output_feedrates',	10,	'updated',	1),
  ('analysis_A1_A2_Blend',	60,	'LocalendTime',	1),
  ('analysis_Analyzer1',	60,	'LocalendTime',	1),
  ('analysis_Analyzer2',	60,	'LocalendTime',	1),
  ('rm_inputoutputdump',	10,	'rm_updated',	1),
  ('rm_user_submitted_dump',	10,	'updated',	1),
  ('rm_log_messages',	1000,	'msg_updated',	1),
  ('rm_source_feeder_inputs',	60,	'LocalendTime',	1),
  ('rm_set_points_log',	60,	'sp_updated',	1),
  ('rm_pop_messages',	500,	'timestamp',	1),
  ('calibration_log_messages',	100,	'updated',	1),
  ('calibration_log_messages',	500,	'updated',	1),
  ('rm_runlog',	60,	'rm_updated',	1),
  ('rm_source',	1,	'NA',	1),
  ('rm_set_points',	1,	'NA',	1),
  ('rm_spoints_to_feeders',	1,	'NA',	1);

  -- 2019-02-15 05:03:50
 */
?>
