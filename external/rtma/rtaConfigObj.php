<?php
//--------------------------------------------------------------------------
// rtaController.php - controls the RTA data collection process
//--------------------------------------------------------------------------

define( "DEBUG", true );
//define( "USER_DB_NAME", "sabiaUserDB");
define( "RTA_PRODUCT_NAME", "helios");	/*IMPORTANT for helios*/ 		  // define either rtma or helios
//define( "RTA_PRODUCT_NAME", "rtma");									  // define either rtma or helios
define( "RTA_DB_NAME", "sabia_v1_m2_1");  /*IMPORTANT for helios*/   // if helios, this will be defined in db.php else for RTMA this define will work, name of rta configuration database
define( "RTA_CONF", "/usr/local/sabia-ck/rta.conf");                      // Location of rta.conf file
define( "RTA_MASTER_CONFIG_TABLE", "rta_config_master" );                 // Name of the master RTA configuration table which holds all controls and locations for RTA configurations
define( "RTA_PHYSICAL_TABLE_NAME", "rta_physical_config" );               // Name of the RTA configuration table for physical data (i.e. physical detectors)
define( "RTA_DERIVED_TABLE_NAME", "rta_derived_config" );                 // Name of the RTA configuration table for derived data (filtered and source decay compensated)
define( "RTA_AVERAGED_TABLE_NAME", "rta_averaged_config" );               // Name of the RTA configuratino table for averaged data (i.e. averaged detectors and servers)
define( "RTA_AVERAGE_GROUPIDS", "rta_average_groupIDs" );                 // Name of the RTA config table that groups configurations to be averaged
define( "RTA_REGEN_JOB_TABLE", "rta_job_table");
define( "QUEUED_TAG_INDEX_TABLE", "rta_tag_index_queued");                // Name of the RTA tag index table for queued tags
define( "COMPLETED_TAG_INDEX_TABLE", "rta_tag_index_completed");          // Name of the RTA tag index table for completed tags
define( "TAG_GROUP_TABLE", "tag_group");          // Name of the RTA tag group table


//-------------------------------------------------------------------------------------------------
//! Real-Time Analysis master/base class
//-------------------------------------------------------------------------------------------------
class rtaMasterConfig
{
    var $rtaMasterID;                            //!< configuration ID for all types of RTA configurations
    var $DB_ID_string;                           //!< unique name for RTA data table
    var $rtaConfigTable;                         //!< exact name of the config table name (currently 'rta_physical_config', 'rta_derived_config','rta_averaged_config')
    var $DBwriteActive;                          //!< Toggle to determine if a particular configuration should be written to the database. Options are 1 for "true" or "write", and 0 for no writing
    var $data_type;                              //!< Type of data to be stored by RTA engine (raw_spectrum, aligned_spectrum, raw_coef,
                                                 //!< analysis, alignment, or 3in1_spectrum

    var $accessLevel;                            //!< References the sabiaUserDB database access, so not all configurations will be viewable by operators 
    var $write_frequency;                        //!< how often should the data be written. Compared with DB_counter. minimum is c++ rtad daemon write time
    var $DB_counter;                             //!< Counter to determine if analyses for a given database/timespan should be written to the database. 
                                                 //!< Real-time values will write after counter hits 1, for less real-time it will occur every time 
                                                 //!< (represents 5s as set in c++ rtad), to write every 60s is 12

    var $timeConversionMassflowWeight;           //!< Conversion factor for total mass after multiplying the massflow rate by good data seconds.
                                                 //!< The conversion factor needs to be to convert from seconds into whatever time unit the
                                                 //!< massflow rate is in.  Default is how many seconds are in an hour

    var $UserComments;                           //!< user defined text field for any comments that they might like to store
    var $endTic;                                 //!< end time of the current RTA request (same for each time rtaController is called)
    var $rtaConf;                                //!< rta.conf ConfigFile object


    //---------------------------------------------------------------------------------------------
    //! processConfig() - RTA config reader/writer, creates table if does not exist, checks to see if requests should be made, calls rtaProcess
    //---------------------------------------------------------------------------------------------
    function processConfig($db)
    {
        // Create MySQL rta data table if it does not exist
        $databaseName = getDBName();

        if (!$this->createRTAtable($db, $databaseName))
            return "create failure";

        // Check to see if it's time to run the analysis
        $counterStatus = $this->rtaCounterCheck($db);
        if ($counterStatus !== true){
            echo "not running rtaProcess, its not time to run analysis<br/>\n";
            return $counterStatus;  // if it is not time to run analysis or the analysis failed, return status message
        }

        // If we get here it's time to perform an analysis
        $rtaProcCall = $this->rtaProcess($db);
        if ($rtaProcCall == false)
            return "rtaProc failure<br/>\n";

        // If we get here we successfully gathered the data and performed the analysis
        $this->DB_counter = 1;   // restet the counter to 1
        if ($this->rtaCounterUpdate($db) == 'counter state failure') 
            return "counter state failure<br/>\n";
        else
            return "success<br/>\n";

    } // end of processConfig()

    function findCurrentCalibration(){
/*      //for test
        $usercalib['SiO2'] = -0.15;
        $usercalib['Al2O3'] = -0.2;
        $usercalib['Fe2O3'] = -0.1;
        $usercalib['CaO'] = -0.4;
*/
        $cfg = new ConfigFile();
        $calAdjust = CAL_ADJUST_FILENAME;

        $cfg->load($calAdjust);
        $cfg->setPath("/USRCAL");

        $calibFile = $cfg->readEntry("lib_file");

        if (!file_exists($calibFile)){
            if (DEBUG)
				echo ("ERROR: unable to read $calibFile");
            return null;
        }
        $caliConfig = new ConfigFile();
        $caliConfig->load($calibFile);

        $caliConfig->setPath("/" . 'SiO2');
        $usercalib['SiO2']['offset'] = $caliConfig->readEntry("offset", 0.0);
        $usercalib['SiO2']['gain'] = $caliConfig->readEntry("gain", 1);
        
        $caliConfig->setPath("/" . 'Al2O3');
        $usercalib['Al2O3']['offset'] = $caliConfig->readEntry("offset", 0.0);
        $usercalib['Al2O3']['gain'] = $caliConfig->readEntry("gain", 1);
        
        $caliConfig->setPath("/" . 'Fe2O3');
        $usercalib['Fe2O3']['offset'] = $caliConfig->readEntry("offset", 0.0);
        $usercalib['Fe2O3']['gain'] = $caliConfig->readEntry("gain", 1);
        
        $caliConfig->setPath("/" . 'CaO');
        $usercalib['CaO']['offset'] = $caliConfig->readEntry("offset", 0.0);
        $usercalib['CaO']['gain'] = $caliConfig->readEntry("gain", 1);        
        
        return $usercalib;
    }

    function updateActualValue($db, $tablename) {
        $usercalib = $this->findCurrentCalibration();
        //traverse throught the array and add
        
        $actualtablename = $tablename . "_real";

        if(!$usercalib){
            if (DEBUG)
	    echo ("ERROR: not updating values to $actualtablename, due to unavaiability of user calibration!");
            return false;
        }
		
        //TODO: use gain 
        
        $optadd =          "`SiO2`= `SiO2` - '" . $usercalib['SiO2']['offset'] . "'";
        $optadd = $optadd .",`Al2O3`= `Al2O3` - '" . $usercalib['Al2O3']['offset'] . "'";
        $optadd = $optadd .",`Fe2O3`= `Fe2O3` - '" . $usercalib['Fe2O3']['offset'] . "'";
        $optadd = $optadd .",`CaO`= `CaO` - '" . $usercalib['CaO']['offset'] . "'";

        $last_id = $db->insert_id;
        $selectQry = "INSERT INTO $actualtablename SELECT * FROM $tablename WHERE `dataID` = $last_id";
        $dbDataResponse = $db->query($selectQry);
        if (!$dbDataResponse) {
            if (DEBUG)
                echo "ERROR: Unable to insert into $actualtablename.\n";
            return false;
        }
        $last_id = $db->insert_id;
        $selectQry = "UPDATE $actualtablename SET " . $optadd . "  WHERE `dataID` = $last_id";
        $dbDataResponse = $db->query($selectQry);
        if (!$dbDataResponse) {
            if (DEBUG)
                echo "ERROR: Unable to update actual values in $actualtablename.\n";
            return false;
        }
        return true;
    }

    //---------------------------------------------------------------------------------------------
    // rtaProcess()
    //---------------------------------------------------------------------------------------------
    function rtaProcess($db, $tablename = NULL)
    {
        $result = true;
        // This enables us to specify to write the data to a different RTA data table (currently for the RTA regen program)
        if (!isset($tablename))
            $tablename = $this->data_type . "_" . $this->DB_ID_string;

		//$this->rtaConfigTable = RTA_AVERAGED_TABLE_NAME;
		
        switch ($this->rtaConfigTable)
        {
            case RTA_PHYSICAL_TABLE_NAME:
                $result = $this->gatherPhysicalData($db, $tablename);
                break;
            case RTA_DERIVED_TABLE_NAME:
                $result = $this->calcDerivedData($db, $tablename);
                break;
            case RTA_AVERAGED_TABLE_NAME:
                $result = $this->calcAveragedData($db, $tablename);
                if ($result) {
                    if (DEBUG)
                        echo "added A1A2 Blend\n";
                    //$this->updateActualValue($db, $tablename);
                    // we can reduce the calibration here
                }else{
                    echo "A1A2_Blend not updated <br/>\n";
                }
                    
                break;
        }
        return $result;
    }  // end of rtaProcess()


    //---------------------------------------------------------------------------------------------
    // writeAnalysisData() - writes calibrated analysis results to database for either XML data from physical configs or DB from derived/averaged results.
    //---------------------------------------------------------------------------------------------
    // Note:  Analysis results come combined from the analyzer
    function writeAnalysisData(
            $db,                                 // [Mysqli object] Mysqli database connection
            $tablename,                          // [string] name of RTA data table
            $column_names,                       // [enumerated array] name of existing column/variable names in RTA table
            $xml = null,                         // [DOM Document / XML] analysisRqst response in XML format containing combinedResultsArray for analyses
            $dbRslts = null                      // [associative array of rtaMath objects] array containing rtaMath Objects with average calculations performed
            )
    {
        if (isset($xml))
        {
            $calResultsXML = $xml->getElementsByTagName('calResults')->item(0); //[DOMElement] with all calresults
            $detNode = $this->getDetectorNode($calResultsXML); // [DOMNodeList][DOMElement which is a subinterval][childNodes which are variable info]
            // $detNode will contains childNodes with each childNode containing a DisplayResult object for each variable defined in calibrate.php

            // Create an array of variable names which we'll use to create any nonexistent columns in the RTA table
            foreach ($detNode->item(0)->childNodes as $varNode)
                $varNames[] = $varNode->nodeName;   // enumerated arrar of variable names

            $combinedResultsArray = $this->calcAverages($varNames, $detNode);
            $source = 'xml';
        }
        elseif (isset($dbRslts))
        {
            foreach ($dbRslts as $key => $rtaMathObj)
                $varNames[] = $key;
            $combinedResultsArray = $dbRslts;
            $source = 'database';
        }

        //Write to database
        if (!$this->createNonExistentColumns($db, $tablename, $column_names, $varNames))
        {
            echo "Unable to create new columns for new variables in RTA table $tablename.  $db->error";
            return false;
        }

        if (!$this->insertAnalysisResultsIntoDatabase ($db, $tablename, $combinedResultsArray, $source))
        {
            echo "Unable to insert analyses results into RTA table $tablename. $db->error";
            return false;
        }

        return true;
    } // end of writeAnalysisData()


    //---------------------------------------------------------------------------------------------
    // insertAnalysisResultsIntoDatabase() - inserts calibrated ('analysis') results into RTA data tables from an associative Array of rtaMathObjects
    //---------------------------------------------------------------------------------------------
    function insertAnalysisResultsIntoDatabase($db, $tablename, $combinedResultsArray, $source)
    {
        $insertQry = "INSERT INTO $tablename SET\n";
        $s=0;
        foreach ($combinedResultsArray as $rtaMathObj)
        {
            if (!$rtaMathObj || $rtaMathObj->dbWrite === 0 || !isset($rtaMathObj->results['average']))
                continue;

            if ($rtaMathObj->varName == 'dataID')
                continue;

            // non-existent columns have already been added to the database table by createNonExistentColumns()
            $insertQry .= $rtaMathObj->varName . " = '" . $rtaMathObj->results['average'] . "',\n";
            // we'll take the first record to get massflow, total tons, and good dataseconds
            if ($s === 0 && $source == 'xml')
            {
                // Average massflow and good data seconds
                $countMassFlow = count($rtaMathObj->massflowVector);
                if ($countMassFlow != 0)
                    $avgMassFlowTph = array_sum($rtaMathObj->massflowVector) / count($rtaMathObj->massflowVector);
                $countGDS = count($rtaMathObj->goodDataSecondsVector);
                if ($countGDS != 0)
                    $avgGDS = array_sum($rtaMathObj->goodDataSecondsVector) / count($rtaMathObj->goodDataSecondsVector);

                // Calculate the massflow rate through all the analyzers cumuluatively
                $insertQry .= "avgMassFlowTph = '$avgMassFlowTph',\n";
                $insertQry .= "goodDataSecs = '$avgGDS',\n";
                $insertQry .= "totalTons = '" . $avgMassFlowTph * $this->write_frequency * $this->timeConversionMassflowWeight  . "',\n";
                $s++;
            }
        }
        if ($this->rtaConfigTable != RTA_AVERAGED_TABLE_NAME)
        {
            $date_format = "Y-m-d H:i:s";
            $insertQry .= "endTic = '$this->endTic',\n";
            $insertQry .= "GMTendTime = '" . gmdate($date_format, $this->endTic) . "',\n";
            $insertQry .= "LocalendTime = '". date($date_format, $this->endTic) . "',\n";

            if  ($this->rtaConfigTable == RTA_PHYSICAL_TABLE_NAME)
                $start_timespan = $this->analysis_timespan;
            elseif ($this->rtaConfigTable == RTA_DERIVED_TABLE_NAME)
            {
                if ($this->filter_type == 'Moving_Average')
                    $start_timespan = $this->moving_avg_filter_sample_timespan;
                elseif ($this->filter_type == 'Kalman')
                    $start_timespan = $this->kalman_filter_sample_timespan;
            }
            $startTic = $this->endTic - $start_timespan;
            $insertQry .= "startTic = '$startTic',\n";
            $insertQry .= "LocalstartTime = '". date($date_format, $startTic) . "',\n";
        }
        $insertQry .= "rtaMasterID = '" . $this->rtaMasterID . "',\n";
        $insertQry = substr($insertQry, 0, -2);
        
        /* 
        $etime = date("Y-m-d H:i:s");
        $myfile = fopen("rtdlog.txt", "a");
        $fbuftxt = "insertData $etime => $insertQry \n";
        fwrite($myfile, $fbuftxt);
        fclose($myfile);
        */        
        
		//echo $insertQry;
        if (!$db->query($insertQry)){   // are not retrieving data so we simply check to see if we wrote or not
            echo "unable to insert record into table : ".$tablename."</br>";
            if(DEBUG)
                echo "insert query is : ".$insertQry ."</br>";
            return false;
        }
        return true;
    } // end of insertAnalysisResultsFromDOM()



    //---------------------------------------------------------------------------------------------
    // createNonExistentColumns - create any columns that do not exist in a given RTA 'analysis' table
    //                            (usually variables or raw coef that were not part of generic table creation)
    //---------------------------------------------------------------------------------------------
    function createNonExistentColumns(
            $db,                                 // Mysqli database connection
            $tablename,                          // [string] RTA data table name
            $column_names,                       // [enumerated array] current column names in RTA data table
            $varNames                            // [enumerated array] name of variables to match against column names
            )
    {
        foreach ($varNames as $name)
        {
            if (in_array($name, $column_names)) // if the variable is in the table continue
                continue;
            if (!isset ($create_qry_string))
                $create_qry_string = "ALTER TABLE $tablename \n";
            $create_qry_string .= "ADD $name float (8,3) NULL DEFAULT NULL,\n";
        }
        if (isset ($create_qry_string))
            $create_qry_string = substr($create_qry_string,0,-2); // delete the last new line and comma
        else
            return true;   // There are no columns that need to be added to the table

        if (!$db->query($create_qry_string))
        {
            echo "Unable to create new columns in database table.  $db->error";
            return false;
        }
        return true;
    } // end of createNonExistentColumns()


    //---------------------------------------------------------------------------------------------
    // createRTAtable() - calls a MySQL stored function to create a table if it does not exist
    //---------------------------------------------------------------------------------------------
    function createRTAtable(
        $db, $databaseName                                     // MySQL database connection, $databaseName added for helios support
        )
    {
        if ($this->data_type == 'raw_spectrum' || $this->data_type == 'aligned_spectrum')
            $data_type = 'spectrumSingle';
        else
            $data_type = $this->data_type;

        $tableName = $this->data_type . "_" . $this->DB_ID_string;
        // Check to see if table exists
        $tableExist = mysql_table_check($db, $databaseName, $tableName);
        if (!$tableExist)
        {
            // Table doesn't exist so we create it
            $procedureQry = "CALL create_rta_table('$tableName','$data_type')";
            $result = $db->query($procedureQry);   // since this is a procedure that we're calling there is no return value
        }
        return true;
    } // end of createRTATable()


    //---------------------------------------------------------------------------------------------
    // retrieveConfigClasses() - takes the base rtaMasterConfig class and pulls associated sub class (physical, derived, or averaged)
    //---------------------------------------------------------------------------------------------
    // INPUTS:  populated parent class
    // OUTPUTS:  either a database populated physical, derived, or averaged RTA config object
    function retrieveConfigClass($db)
    {
        $masterCfgTable = RTA_MASTER_CONFIG_TABLE;
        $innerJoinQry = "SELECT * FROM $masterCfgTable, $this->rtaConfigTable WHERE $masterCfgTable.rtaMasterID=$this->rtaMasterID AND $this->rtaConfigTable.rtaMasterID = $this->rtaMasterID";
        $dbResponse = $db->query($innerJoinQry);
        if ($dbResponse === false || $dbResponse->num_rows == 0)
        {
            echo "Unable to match the sub-class with the master class.   $db->error";
            return false;
        }

        if ($this->rtaConfigTable == RTA_PHYSICAL_TABLE_NAME)
            $rtaCfg = new rtaPhysicalConfig;
        elseif ($this->rtaConfigTable == RTA_DERIVED_TABLE_NAME)
            $rtaCfg = new rtaDerivedConfig;
        elseif ($this->rtaConfigTable == RTA_AVERAGED_TABLE_NAME)
            $rtaCfg = new rtaAveragedConfig;

       foreach ($dbResponse->fetch_assoc() as $attribute => $value)
           $rtaCfg->$attribute = $value;
       return $rtaCfg; 
       
    } // end of retrieveConfigClasses()


    //---------------------------------------------------------------------------------------------
    // rtaCounterUpdate() - updates the counter for the given RTA configuration
    //---------------------------------------------------------------------------------------------
    function rtaCounterUpdate($db)
    {
        $update_counter_qry = "UPDATE " . RTA_MASTER_CONFIG_TABLE . " SET DB_counter = " . $this->DB_counter . " WHERE rtaMasterID = " . $this->rtaMasterID;
        $update_result = $db->query($update_counter_qry);
        if (!$update_result)  // update queries return true if successful and false if not
            return "counter state failure";
        else
            return "increment success";

    }  // end of rtaCounterUpdate()


    //---------------------------------------------------------------------------------------------
    // rtaCounterCheck() - compares the current count with the write frequency to determine if a RTA configuration should be analyzed
    //---------------------------------------------------------------------------------------------
    function rtaCounterCheck($db)
    {
        // Now that we know that the configuration is active and the table has been created, we now check counter
        $rtad_write_time = $this->rtaConf->readEntry ("write_time");  // C++ rtad write time interval (default is 5 seconds)
        $current_count = $this->DB_counter;
        $current_counter_time = $current_count * $rtad_write_time;
        
        if (DEBUG)
        {
            //$myfile = fopen("log.txt", "a");           
            //$txt = "Current Time Counter:  $current_counter_time   Config Write Frequency:$this->write_frequency <br/>\n";
            //$txt = date("Y-m-d H:i:s")." Rta Controller executed ".PHP_EOL;
            //fwrite($myfile, $txt);
        }	
        
        if ($current_counter_time < $this->write_frequency)
        {
            // It's not quite time to write to the database yet so we'll add one to the counter
            $this->DB_counter++;
            $counterResult = $this->rtaCounterUpdate($db);
            return $counterResult;  // returns status message upon completion (fail or success)
        }
        return true;
    }  // end of rtaCounterCheck()

} // end of rtaMasterConfig class definition


//-------------------------------------------------------------------------------------------------
//! Real-Time Analysis (RTA) Database base class for physical detectors 
//-------------------------------------------------------------------------------------------------
class rtaPhysicalConfig extends rtaMasterConfig 
{
   // Vars used to set up database connections, config parameters, and initial 'analyze' parameters
    var $rta_ID_physical;                        //!< Real-time database ID, auto-incremented number by database, primary key
    var $rtaMasterID;                            //!< record ID of this configuration in the master config table
    var $IPaddress;                              //!< IP address for the analyzer that this RTA configuration belongs to
    var $goodDataSecondsWeight_physicalCfg;      //!< Toggle for good data second weighting of a single analyzer's physical data results.  Usually if the span is not the same as the subinterval
    var $massflowWeight_physicalCfg;             //!< Toggle for tonnage weighting of a single analyzer's physical data results. Usually if the span is not the same as the subinterval

    var $analysis_timespan;                      //!< Timespan for requested analysis in seconds and the timespan of the bulk average period 
                                                 //!< (only 1 bulk average period). How much time NOT how much spectrum is used,
                                                 //!< must be multiple of binning interval (60s).  This may be the the 'unweighted moving average'
                                                 //!< quantity.  In simplified terms a timespan of 180s means a 3 minute moving average.  It is recommended 
                                                 //!< that the Moving Average filter do the exact same thing as having a longer timespan with multiple subintervals.
                                                 //!< This parameter has 'analyze' do the averaging  
                                                 //!< but with all types of data (including spectral, calibrated results, raw coef, filtered data, etc.). 
                                                 //!< Technically you could have both in effect as the Moving Average Filter does not know anything about the original data source.

    var $averaging_subinterval_secs;             //!< Subinterval length in seconds.  This is how much spectrum is used for a single analysis
                                                 //!< The recommended length is the same value set in analyzer.conf, but we are allowing the
                                                 //!< real-time feature to have full access to create the data any way it wants.
                                                 //!< For by-minute analyses, set this parameter to 60, 120, 180,... timespan.  The timespan
                                                 //!< is divided into subintervals based on the subinterval_secs length.  The results of eac 
                                                 //!< are averaged together to give a single result that is written to the database.  If this
                                                 //!< equals timespan then all the spectrum will be summed up, and only 1 subinterval is run.

    var $detectorID;                             //!< Detector ID (string).  Legal values are: (Detector N) | (empty) | "Average"\n
                                                 //!< where (detectorID) is the name of a detector, e.g. "Detector 1" or "Detector 2", the default is
                                                 //!< "Detector 1".  This is only if individual detector values are desired.  A separate 
                                                 //!< database has to be set up for each detector.  It is recommended that the "Average" be done in the rta_averaging_config
    var $detector_physical_ID;                   //!< Takes the detectorID which is human readable and converts it to 'datad1', 'datad2', or 'average'


    //---------------------------------------------------------------------------------------------
    // gatherPhysicalData() - gets data from remote (or localhost) servers via analysisRqst.php in XML format
    //---------------------------------------------------------------------------------------------
    function gatherPhysicalData($db, $tablename)
    {
        $stime = date("Y-m-d H:i:s");
        $url = $this->prepareRemoteURL();

        $response = file_get_contents($url);  // returns a XML node for the desired detector with childNodes containing a DisplayResult Object Node

        if(!$response){
            echo "ERROR: ip may not be proper, no response received from configured ip on rta_physical_config"."</br>\n";
            if(DEBUG)
                echo "URL is : ". $url."</br>\n";
            return false;
        }
        
        /*
        $etime = date("Y-m-d H:i:s");
        $myfile = fopen("rtdlog.txt", "a");
        $fbuftxt = "$stime => $url being used $etime \n";
        fwrite($myfile, $fbuftxt);
        fclose($myfile);
        */
        
        $xml = new DOMDocument();
        $xml->loadXML($response);
        $status = $xml->getElementsByTagName('bGood')->item(0)->nodeValue;

        if ($status != 1)
        {
            echo "Analysis Error.  " . $xml->getElementsByTagName('errStrs')->nodeValue;
            return false;
        }

        $column_names = mysql_column_name_select($db, $tablename);
        if (!$column_names)
            return false;

        if ($this->data_type == 'analysis')
        {
            if (!$this->writeAnalysisData($db, $tablename, $column_names, $xml))
                return false;
        }
        elseif ($this->data_type == 'raw_coef')
        {
            if (!$this->writeRawCoefData($db, $tablename, $column_names, $xml))
                return false;
        }
        else
            return false;
        return true; 
 
    } // end of gatherPhysicalData()


    //---------------------------------------------------------------------------------------------
    // writeRawCoefData - writes raw coefficient results to database for either XML data from physical configs or DB from derived/averaged results
    //---------------------------------------------------------------------------------------------
    function writeRawCoefData(
            $db,                                 // [Mysqli object] Mysqli database connection
            $tablename,                          // [string] name of RTA data table
            $column_names,                       // [enumerated array] name of existing column/variable names in RTA table
            $xml = null,                         // [DOM Document / XML] analysisRqst response in XML format containing combinedResultsArray for analyses
            $dbRslts = null                      // []
            )
    {
        //NOTE:  THis function call does not work properly

        if (isset($xml))
        {
            $parsedResultsXML = $xml->getElementsByTagName('rawcoefResults')->item(0);
            $detNode = $this->getDetectorNode($parsedResultsXML);
            // $detNode contains an AnalysisResults Object which has average alignment data, and a rsltElements Array containing a AnalysisCompResult for each raw coefficient 
            $rsltElements = $detNode->item(0)->getElementsByTagName('rsltElements')->item(0);
            foreach ($rsltElements->childNodes as $varNode)
            {
                $varNames[] = $varNode->tagName . "_coeff";
                $varNames[] = $varNode->tagName . "_sigma";
                $varNames[] = $varNode->tagName . "_rcorr";
                $varNames[] = $varNode->tagName . "_area";
            }

            //Write to database
            if (!$this->createNonExistentColumns($db, $tablename, $column_names, $varNames))
            {
                echo "Unable to create new columns for new variables in RTA table $tablename.  $db->error";
                return false;
            }

            $combinedResultsArray = $this->calcAverages($varNames, $detNode);
            if (!$this->insertRawCoefResultsFromDOM ($db, $xml, $detNode, $tablename))
            {
                echo "Unable to insert analyses results into RTA table $tablename. $db->error";
                return false;
            }


        }
        elseif (isset($dbRslts))
        {

        }
        return true;
    } // end of writeRawCoefData() 
   
 
    //---------------------------------------------------------------------------------------------
    // calcAverages() - moves enumerated XML nodes into an array of rtaMath objects with averages calculated
    //-------------------------------------------------------------------------------------------- 
    function calcAverages($varNamesArray, $detXMLNode)
    {
        // Loop through each variable name and create rtaMath objects containing data
        foreach ($varNamesArray as $varName)
        {
            $combinedArray[$varName] = new rtaMath($varName);
            $nodeValue = array();
            $nodeMassflow = array();
            $nodeGDS = array();
            foreach ($detXMLNode as $subintNode)
            {
                $varInfo = $subintNode->getElementsByTagName($varName)->item(0);  // There is only one variable per subinterval so we return first item
                $nodeValue[] = $varInfo->getElementsByTagName('value')->item(0)->nodeValue;
                $nodeMassflow[] = $varInfo->getElementsByTagName('avgMassFlowTph')->item(0)->nodeValue;
                $nodeGDS[] = $varInfo->getElementsByTagName('goodDataSecs')->item(0)->nodeValue;
            }
            $combinedArray[$varName]->dataVector = $nodeValue;
            $combinedArray[$varName]->massflowVector = $nodeMassflow;
            $combinedArray[$varName]->goodDataSecondsVector = $nodeGDS;

            $combinedArray[$varName]->massflowWeight = $this->massflowWeight_physicalCfg;
            $combinedArray[$varName]->goodDataSecondsWeight = $this->goodDataSecondsWeight_physicalCfg;
            $combinedArray[$varName]->conversionFactor = $this->timeConversionMassflowWeight;
            $combinedArray[$varName]->average = 1;
            $combinedArray[$varName]->summation = 1;
            $combinedArray[$varName]->dbWrite = 1;
            $combinedArray[$varName]->tabulate();
        }           
        return $combinedArray; 
    } // end of calcAverages()


    //---------------------------------------------------------------------------------------------
    // getDetectorNode - takes a given node and extracts the appropriate detector node requested by the RTA configuration
    //---------------------------------------------------------------------------------------------
    function getDetectorNode(
            $xmlParentNode                       // [DOMElement object] xml node which contains one child node for each detector
            )
    {
        // find the detector ID node that matches what is requested (can be datad1, datad2 (or other physical detector), or average)
        $detNodeArray = $xmlParentNode->getElementsByTagName($this->detector_physical_ID);  //returns an array of XML nodes, one for each subinterval

        return $detNodeArray;
    }  // end of getDetetorNode()
            

    //---------------------------------------------------------------------------------------------
    // prepareRemoteURL - prepares a remote analysis request URL
    //---------------------------------------------------------------------------------------------
    function prepareRemoteURL()
    {
        if (isset ($this->endTic))
        {
            $endTime = gmdate("Y-m-d\TH:i:s", $this->endTic);
        }
        else
        {
            $endTic = time();
            $endTime = 0;
        }
        $url = "http://$this->IPaddress/analysisRqstUri.php";
        if ($this->data_type == 'analysis')
            $url .= "?rqst=calRslts&subintRslts=1";
        elseif ($this->data_type == 'alignment')
            $url .= "?rqst=alignDat&alignRslts=1&combRslts=0";
        elseif ($this->data_type == 'raw_coef')
            $url .= "?rqst=rawCoef&combRslts=0&parsedRslts=1";
        elseif ($this->data_type == '3in1_spectrum')
            $url .= "?rqst=3in1Spect&seriesType0=3in1&combRslts=0&alignRslts=1";
        elseif ($this->data_type == 'aligned_spectrum')
            $url .= "?rqst=algnSpect&seriesType0=aligned&combRslts=0&alignRslts=1";
        elseif ($this->data_type == 'raw_spectrum')
            $url .= "?rqst=rawSpect&seriesType0=raw&combRslts=0&alignRslts=1";

        $url .= "&dest=client";
        $url .= "&creply=xml";
        $url .= "&tGMT=1";  // means all time args are in GMT
        $url .= "&tEndGmt=$endTime";
        $url .= "&tSpan=" . $this->analysis_timespan;
        $url .= "&alignInt=60";
        $url .= "&bulkAvg=" . $this->analysis_timespan;
        $url .= "&subintSecs=" . $this->averaging_subinterval_secs;
        $url .= "&subintMode=1";
        $url .= "&tphweight=" . $this->massflowWeight_physicalCfg;  // toggle to tell if a single server's analyses should be tonnage weighted before combining all servers together

        if ($this->detectorID == 'Average')
            $url .= "&detID=average";
        elseif ($this->detectorID == 'Detector 1')
            $url .= "&detID=datad1";
        elseif ($this->detectorID == 'Detector 2')
            $url .= "&detID=datad2";

        $url .= "&perform=analysisRqst";
        return $url;

    } // end of prepareRemoteURL()


}  // end of rtaPhysicalConfig class


//----------------------------------------------------------------------------------------------
//! Real-Time Analysis (RTA) Database extended class for derived data (filtered, source decay compensated, etc.)  
//----------------------------------------------------------------------------------------------
class rtaDerivedConfig extends rtaMasterConfig
{
    // General Filter settings
    var $rta_ID_derived;                         //!< record ID of the RTA configuration derived from the physical data
    var $rtaMasterID;                            //!< record ID of this configuration in the master config table
    var $data_source_rtaMasterID;                //!< record ID from the master config table the RTA configuration (usually a physical data source). 
    var $filter_type;                            //!< A comma separated list of filters that will implemented.
                                                 //!< Determines what filters if/will be used to calculate data.

    var $massflowWeight_derivedCfg;              //!< Toggle for massflow for filtering of data records
    var $goodDataSecondsWeight_derivedCfg;       //!< Toggle for good data second weighting for filtering

    var $percentageGoodDataRequired;             //!< Percentage of how many good data seconds are required to indicate a good analysis with sufficient good data

    // Moving Average Filter specific settings
    var $moving_avg_filter_sample_timespan;      //!< How many seconds (i.e. physical data records or subintervals) will be averaged (a write_frequency of 5s 
                                                 //!< with a moving_avg_sample_timespan of 60s means 12 records averaged)
                                                 //!< to create a single result. More samples means more averaging but less responsiveness

    // Kalman Filter specific settings
    var $kalman_filter_sample_timespan;          //!< How many seconds (i.e. physical data records) will be used in using the kalman filter (a write_frequency of 5s with a kalman_sample_timespan of 60s means 12 records will be averaged)
                                                 //!< filter to predict and thus filter data
    var $kalman_gain_Q;                          //!< Kalman Filter gain Q
    var $kalman_gain_R;                          //!< Kalman Filter gain R

    // Source Decay Compensation Settings
    var $source_decay_comp;                      //!< Source decay compensation toggle
    var $source_decay_ref_date;                  //!< Source decay reference date. Should be the date of the last calibration using raw coefficients, 
                                                 //!< i.e. when the actual counts were collected and calibrated to


//-------------------------------------------------------------------------------------------------
//! calcDerviedData() - takes physical data and applies various filters and calculations
//-------------------------------------------------------------------------------------------------
function calcDerivedData($db, $tablename)
{
    if ($this->filter_type == 'Moving_Average' || $this->filter_type == 'Kalman')
    {
        $masterCfg = new rtaMasterConfig;
        if (!populateObjFromMYSQL($db, $masterCfg, $this->data_source_rtaMasterID, 'rtaMasterID', RTA_MASTER_CONFIG_TABLE))
            return false;
        $dataSource = $masterCfg->retrieveConfigClass($db);
        if (!$dataSource)
            return false;
        $dataSource->endTic = $this->endTic;

        // IMPORTANT:  The start and end time designate only the database records LocalendTime, so spectral data that was used to calculate the first sample will come from before the start time
        if ($this->filter_type == 'Moving_Average')
            $startTic = $this->endTic - $this->moving_avg_filter_sample_timespan;  
        elseif ($this->filter_type == 'Kalman')
            $startTic = $this->endTic - $this->kalman_filter_sample_timespan;

        $startTime = date("Y-m-d H:i:s", $startTic);
        $endTime = date("Y-m-d H:i:s", $this->endTic);
        $dataSourceTable = $dataSource->data_type  . "_" . $dataSource->DB_ID_string;
        $selectQry = "SELECT * FROM $dataSourceTable WHERE LocalstartTime >= '$startTime' AND LocalendTime <= '$endTime'";
        $dbResponse = $db->query($selectQry);
        if (!$dbResponse || $dbResponse->num_rows == 0)
        {
            echo "Unable to retrieve data for calculating derived data.\n";
            return false;
        }
        // Move all data, massflow, and GDS from RTA data table results to rtaMath objects for subsequent calculations
        for ($i=0; $i<$dbResponse->num_rows; $i++)
        {
            $resultRow = $dbResponse->fetch_assoc();
            if ($i==0)
            {
                foreach ($resultRow as $varName => $valueIGNORE)
                {
                    $rtaMathArray[$varName] = new rtaMath($varName);
                    $rtaMathArray[$varName]->conversionFactor = $this->timeConversionMassflowWeight;
                    $rtaMathArray[$varName]->massflowWeight = $this->massflowWeight_derivedCfg;
                    $rtaMathArray[$varName]->goodDataSecondsWeight = $this->goodDataSecondsWeight_derivedCfg;
                } 
            }
            foreach ($resultRow as $key=>$value)
            {
                $rtaMathArray[$key]->dataVector[$i] = $value;
                $rtaMathArray[$key]->massflowVector[$i] = $resultRow['avgMassFlowTph'];
                $rtaMathArray[$key]->goodDataSecondsVector[$i] = $resultRow['goodDataSecs'];
            }
        }

        foreach ($rtaMathArray as $rtaMathObj)
        {
            $rtaMathObj->toggleCheck(1,0,1,1);
            if ($this->filter_type == 'Kalman')
            {
                // TODO:  Determine if it's appropriate to apply massflow or good data seconds weighting to kalman filtered results
                if ($rtaMathObj->average == 1)
                {
                    // TODO:  THe toggleCheck() is not appropriately handling the special cases of massflow, GDS, or total tons.  The next if statement is just a patch until that gets reworked
                    $varName = $rtaMathObj->varName;
                    if ($varName != 'avgMassFlowTph' && $varName != 'goodDataSecs' && $varName != 'totalTons')
                    {
                        // Apply Kalaman filter if active
                        $kf = new kalmanFilter();
                        $kf->Q = $this->kalman_gain_Q;
                        $kf->R = $this->kalman_gain_R;
                        $kf->P = 0.0;
                        //apply the filter
                        $sampleCount = count($rtaMathObj->dataVector);
                        $filteredResults = obj_clone ($rtaMathObj);
                        $rtaMathObj->dataVector = $kf->doFilter ($filteredResults->dataVector, $sampleCount);
                    }
                }  
            }
            $rtaMathObj->tabulate();
        }
        $totalSpan = $dataSource->averaging_subinterval_secs * $dbResponse->num_rows;
        if ($rtaMathArray['goodDataSecs']->results['sum'] / $totalSpan * 100<= $this->percentageGoodDataRequired )
        {
            foreach ($rtaMathArray as $rtaMathObj)
                $rtaMathObj->results['average'] = 0;
        }
        $derivedTableName = $this->data_type . "_" . $this->DB_ID_string;
        $column_names = mysql_column_name_select($db, $derivedTableName);
        if (!$column_names)
            return false;

        if (!$this->writeAnalysisData($db, $derivedTableName, $column_names, NULL, $rtaMathArray))
            return false;

        return true;

    }
} // end of calcDerivedData()


} // end of rtaDerivedCfgObj class



//----------------------------------------------------------------------------------------------
//! Real-Time Analysis (RTA) Database class for averaging multiple RTA sources (detectors, remote servers, etc.)  
//----------------------------------------------------------------------------------------------
class rtaAveragedConfig extends rtaMasterConfig
{
    var $rta_ID_averaged;                        //!< record ID of the averaged RTA configuration
    var $rtaMasterID;                            //!< record ID of this configuration in the master config table
    var $groupAveragingID;                       //!< group average ID which connects to the core RTA engine referenced in the rta_averaged_config
    var $massflowWeight_averagedCfg;             //!< Toggle for setting massflow weighting between data tables (recommended)
    var $goodDataSecondsWeight_averagedCfg;      //!< Toggle for setting good data seconds weighting (recommended)

     function calcAveragedData($db, $tablename)
    {
        $selectQry = "SELECT rtaMasterID FROM " . RTA_AVERAGE_GROUPIDS . " WHERE groupAveragingID = $this->groupAveragingID";
        $dbResponse = $db->query($selectQry);
        if (!$dbResponse || $dbResponse->num_rows == 0)
            return false;
        // Loop through each rtaMasterID and store the results (single database row) into a combined rtaMath object for each variable
        for ($i=0; $i<$dbResponse->num_rows; $i++)
        {
            $row = $dbResponse->fetch_assoc();
            $rtaMasterID = $row['rtaMasterID'];
            $rtaMasterConfigObj = new rtaMasterConfig;
            if (!populateObjFromMYSQL($db, $rtaMasterConfigObj, $rtaMasterID, 'rtaMasterID', RTA_MASTER_CONFIG_TABLE))
                return false;

            // IMPORTANT:  The start and end time designate only the database records LocalendTime, so spectral data that was used to calculate the first sample will come from before the start time
            $dataSourceTable = $rtaMasterConfigObj->data_type . "_" . $rtaMasterConfigObj->DB_ID_string;
            //TODO: when number of detectors is set to 1
            /*if (1/* number of detectors is 1, ignore * /) {
                echo "no of detectors is set to 1 in analyzer.conf, ignoring detector 2 results"."</br>\n";
                $dataSourceTable = $dataSourceTable = $rtaMasterConfigObj->data_type . "_"."Analyzer1";
            }*/
	    	/* The endtic is different for both Analyzer_1 and Anaylzer_2 compared to this->endTic : Abhinandan 2/20/2013 */	   
            $selectQry = "SELECT * FROM $dataSourceTable WHERE endTic BETWEEN ($this->endTic - 60) AND ($this->endTic) ";
            //$selectQry = "SELECT * FROM $dataSourceTable WHERE endTic ='($this->endTic)'";
            
            $dbDataResponse = $db->query($selectQry);
            
            /*
            $etime = date("Y-m-d H:i:s");
            $myfile = fopen("rtdlog.txt", "a");
            $fbuftxt = "$etime => $selectQry \n";
            fwrite($myfile, $fbuftxt);
            fclose($myfile);
            */
            
            if (!$dbDataResponse || $dbDataResponse->num_rows == 0)
            {
                echo "Unable to retrieve data for calculating averaged data from $dataSourceTable <br/>\n";
                return false;
            }
            // Move all data, massflow, and GDS from RTA data table results to rtaMath objects for subsequent calculations
            $resultRow = $dbDataResponse->fetch_assoc();
            foreach ($resultRow as $key => $value)
            {
                if ($i==0)
                {
                    $rtaMathArray[$key] = new rtaMath($key);
                    $rtaMathArray[$key]->conversionFactor = $this->timeConversionMassflowWeight;
                    $rtaMathArray[$key]->massflowWeight = $this->massflowWeight_averagedCfg;
                    $rtaMathArray[$key]->goodDataSecondsWeight = $this->goodDataSecondsWeight_averagedCfg;
                }
                $rtaMathArray[$key]->dataVector[$i] = $value;
                $rtaMathArray[$key]->massflowVector[$i] = $resultRow['avgMassFlowTph'];
                $rtaMathArray[$key]->goodDataSecondsVector[$i] = $resultRow['goodDataSecs'];
            }
        }

        
     foreach ($rtaMathArray as $rtaMathObj)
     {
//            $rtaMathObj->toggleCheck(1,0,1);
            // Typically toggleCheck() would be run to set whether an average or sum is done, however in this case all the meta-data is stored in the tables and needs to be included
            // thus we will average anything that is a number, including all meta data.  If the meta data is not the same for each RTA configuration you can get decimal values
            // Add meta-data to average table since we don't know what each master config settings are (technically they can be different, but more than likely will be exactly the same)

     		//Abhi9202014714 changed from summation to average
            if ($rtaMathObj->varName == 'totalTons')
                $rtaMathObj->average = 1;
            elseif ($rtaMathObj->varName == 'rtaMasterID')
                continue;
            elseif ($rtaMathObj->varName == 'detectorID')
                continue;
            elseif (is_numeric($rtaMathObj->dataVector[0]))
                $rtaMathObj->average = 1;
            else
            {
                $rtaMathObj->summation = 0;
                $rtaMathObj->average = 0;   // returns the first value (only for dates which should be the same unless the analysis params are different)
            }
            
            $result = $rtaMathObj->tabulate();
            if (!isset($rtaMathObj->results['average']) && isset($rtaMathObj->results['sum']))
                $rtaMathObj->results['average'] = $rtaMathObj->results['sum'];  // NOTE:  This is not an average, but for the later code we're just putting it into this container to input to database
            elseif (!isset($rtaMathObj->results['average']) && !isset($rtaMathObj->results['sum']))  // This occurs if there is no average/sum (usually a string data set) so the first data record is returned
                $rtaMathObj->results['average'] = $rtaMathObj->results['nonCalculated'];
        }
        $averagedTableName = $tablename;
        $column_names = mysql_column_name_select($db, $averagedTableName);
        if (!$column_names)
            return false;       
		
        $this->detectorID = 'Average';
        if (!$this->writeAnalysisData($db, $averagedTableName, $column_names, NULL, $rtaMathArray))
            return false;

        return true;

      }  // end of calcAveragedData()

   } // end of rtaAveragedCfgObj class

//-------------------------------------------------------------------------------------------------
// rtaTagGroupObj - contains the user defined group type
//-------------------------------------------------------------------------------------------------
class rtaTagGroupObj {
    var $tagGroupID;
    var $rtaMasterID;
    var $tagGroupName;
    var $massflowWeight;
    var $goodDataSecondsWeight;


    function populateGroupFromWeb()
    {
        foreach ($this as $attribute => $value)
        {
            $this->$attribute=getPassedVar($attribute);
        }
        return true;
    }  //end of populateGroupFromWeb()

     function tagGroupDataCheck($actionType = 'Edit')
    {
        foreach ($this as $attribute => $value)
        {
            $noCheckForEmpty = array ( 'tagName','tagGroupID');
            // At least one of the string fields must contain some kind of identifying name so we check for that
            if ( in_array ($attribute, $noCheckForEmpty))
            {
                $nonNullCheck = 0;
                foreach ($noCheckForEmpty as $name)
                {
                    if ( empty($this->$name))
                        $nonNullCheck++;
                }
                if ($nonNullCheck == 0)
                {
                    echo "Error.  Either the tag Name or Material Type must contain a value";
                    return false;
                }
            }
            else
            {
                if ($attribute == 'tagGroupID' && $actionType == 'New')
                    continue;    // new tags will not have a tagID yet
               if ( $this->$attribute == NULL || $this->$attribute == "")
                {
                    echo "Error.  $attribute must contain a value.";
                          //return false;
                }
            }
        }
        return true;
    }  //end of tagGroupDataCheck()

     //Inserts a tagGroup
     function insertGroupTag($db)
    {
        $insertQry = 'INSERT ' . TAG_GROUP_TABLE . " SET\n";
        foreach ($this as $attribute => $value)
        {
            $insertQry .= "$attribute='$value',\n";
        }
        $insertQry = substr ($insertQry, 0, -2);

        if (!$db->query($insertQry))
        {
            echo "Error.  Unable to create tag in database.  $db->error";
            return false;
        }

        return true;
    }  // end of insertGroupTag

     //Deletes the tagGroup as well as the relavant tags queued and completed
     function deleteGroupTag($db, $tablename)
    {
        $deleteQry = "DELETE FROM $tablename WHERE tagGroupID = '$this->tagGroupID'";
        if (!$db->query($deleteQry))
        {
            echo "Error.  Unable to delete tagGroup from database.  $db->error";
            return false;
        }
        //since deleting a tag group the associated tags should be deleted too. 
        //! Deleting from the queued table.
        $deleteQry1 = "DELETE FROM rta_tag_index_queued WHERE tagGroupID = '$this->tagGroupID'";

        if (!$db->query($deleteQry1))
        {
            echo "Error.  Unable to delete tag_queued from database.  $db->error";
            return false;
        }
        //!Deleting from a completed table.
        $deleteQry2 = "DELETE FROM rta_tag_index_completed WHERE tagGroupID = '$this->tagGroupID'";

        if (!$db->query($deleteQry2))
        {
            echo "Error.  Unable to delete tag_completed from database.  $db->error";
            return false;
        }



        return true;
    } // end of deleteGroupTag()


      //---------------------------------------------------------------------------------------------
    // retrieveAllQueuedTags() - returns all 'queued' tags (where end time has not finished)
    //---------------------------------------------------------------------------------------------
    function retrieveAllQueuedTags($db)
    {
        $tagList = retrieveAllObjectsFromMYSQL($db, 'tagGroupID', TAG_GROUP_TABLE, 'rtaTagGroupObj' );

        if (!$tagList)
            $tagList = array();
        return $tagList;

    } // end of retrieveAllQueuedTags()

//---------------------------------------------------------------------------------------------
    // drawIndvTagGroupIndexHTML () - draws the HTML code for a single table row for an indvidual tag
    //---------------------------------------------------------------------------------------------
    function drawIndvTagGroupIndexHTML($db, $action=NULL)
    {
print_r($_SESSION['readonly']);
        $pageType = getPassedVar('pageType');
        $id = $this->tagGroupID;
        echo "<tr>";
        echo "<form id='form_$id' action='realTimeDisplay.php?pageType=$pageType' method='POST' target='_self' onSubmit='return validateForm(this)'>";
        echo "<input type='hidden' name='tagGroupID' value='$id'>";
        echo "<input type='hidden' name='perform' value='modifyTag'>";
        if($pageType == 'tagGroupIndex')
        echo "<td><input type='text' name='tagGroupID' readonly = '" . $_SESSION['readonly'] . "' size=2 value='$this->tagGroupID' disabled></td>";
        echo "<td><input type='text' name='tagGroupName' maxlength=40 class='string canBeNull possibleRequired' value='$this->tagGroupName' disabled></td>";

        $rtaConfigsArray = retrieveAllObjectsFromMYSQL($db, 'rtaMasterID', RTA_MASTER_CONFIG_TABLE, 'rtaMasterConfig', 'accessLevel', $_SESSION['loginGroup'], NULL, NULL, '>=');
        if (!$rtaConfigsArray)
        {
            echo "Error.  Unable to retrieve the list of data sources.";
            return false;
        }
        echo "<td>";
        echo "<select name='rtaMasterID' disabled>";
        foreach ($rtaConfigsArray as $rtaConfigObj)
        {
            if ($rtaConfigObj->data_type != 'analysis' || $rtaConfigObj->DBwriteActive != 1)   // Hide all non-analysis RTA config tables and those that are not currently collecting data
                continue;
            echo "<option value='$rtaConfigObj->rtaMasterID'";
 if ($rtaConfigObj->rtaMasterID == $this->rtaMasterID)
                echo "selected";
            echo ">$rtaConfigObj->DB_ID_string</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "<td>";
        echo "<input type='hidden' name='massflowWeight' value='1'>";
	echo "</td>";
	echo "<td>";
	echo "<input type='hidden' name='goodDataSecondsWeight' value='1'";
        echo "</td>";
        if ($action == 'create')
            echo "<td><td><input type='button' value='New Tag Group' style='background-color:#66C285' onclick=editForm(this.form,'New',this)></td></td>";
        else
        {
            echo "<td>";
            $url = "realTimeDisplay.php?perform=updatePageLayout&pageType=tagGroupView&tagGroupName=$this->tagGroupName";
?>
            <!--<input type='button' value='View' onclick= "window.open('<?php echo $url?>','_blank')">-->

<?php
            echo "</td>";
        }
            if ($action == 'create')
                echo "<td></td>";
            else
            {
                echo "<td>";
                echo "<input type='button' value='Delete' onclick=formSubmit(this.form,'Delete','delete')>";
                echo "</td>";
                echo "<td>";
                //echo "<input type='button' value='Sto' style='background-color:#FF6666'  onclick=formSubmit(this.form,'Stop','stop')>";

                echo "</td>";
            }
            echo "<td>";
            if($action == 'create')
            echo "<input type='submit' value='Submit' disabled>";
            echo "</td>";
        echo "</form>";
        echo "</tr>";

        return true;
    } // end of drawIndvTagGroupIndexHTML()

     function retrieveAllTagGroups($db)
    {
        $Query = "SELECT * from tag_group";
        $dbRes = $db->query($Query);

        $tagName = $dbRes->fetch_row();
        $tagGroupName = $tagName[1];
        return $tagGroupName;

    } // end of retrieveAllTagGroups()

} //end of rtaTagGroupObj class

//-------------------------------------------------------------------------------------------------
// rtaTagObj - contains the start/end time for a user defined tag name with associated material type
//-------------------------------------------------------------------------------------------------
class rtaTagObj {
    var $tagID;
    var $rtaMasterID;
    var $status;
    var $tagName;
    var $tagGroupName;
    var $LocalstartTime;
    var $LocalendTime;

    //---------------------------------------------------------------------------------------------
    // processTag() - retrieves RTA data, add toggles, apply calculations, write to database
    //---------------------------------------------------------------------------------------------
    function processTag($db)
    {
        // Get rtaMasterConfig to convert the rtaMasterConfig into a tablename
        $rtaCfgObj = new rtaMasterConfig;
        if (!populateObjFromMYSQL( $db, $rtaCfgObj, $this->rtaMasterID, 'rtaMasterID', RTA_MASTER_CONFIG_TABLE))
        {
            echo "Unable to retrieve RTA config object to process tag. $db->error";
            return false;
        }
        $tablename = $rtaCfgObj->data_type . "_" . $rtaCfgObj->DB_ID_string;

        // Now that we have tablename we can get the RTA data.  Returns an array of rtaMath objects:  one for each variable
        $rtaMathArray = $this->retrieveData($db, $tablename, $rtaCfgObj);
		
         if ( empty ($rtaMathArray))
        {
            echo "Unable to gather RTA data to process tag. $db->error";
            
            /* Handle empty array */
            
        $insertQry = "INSERT INTO " . COMPLETED_TAG_INDEX_TABLE . "\n  SET\n";
        $selectqry = "select * from tag_group where tagGroupID = '$this->tagGroupID'";
        $results_arr = $db->query($selectqry);
        while($row = $results_arr->fetch_array())
         {
          $massflowSecondsWeight = $row['massflowWeight'];
          $goodDataSecondsWeight = $row['goodDataSecondsWeight'];
         // $this->tagGroupName = $row['tagGroupName'];
         }
 
        foreach ($this as $attribute => $attrValue)
        {
            if($attribute == 'tagID') continue;
            if ($attribute == 'status')
                $attrValue = 'completed';
            if($attribute == 'tagGroupName')
                continue;
            $insertQry .= "    $attribute = '$attrValue', \n";
        }
        $insertQry .= "     massflowWeight= '$massflowSecondsWeight', \n";
        $insertQry .= "     goodDataSecondsWeight= '$goodDataSecondsWeight', \n";
        $insertQry = substr($insertQry, 0, -3);
        echo "\n". $insertQry. "\n\n" ;

	if ( !$db->query($insertQry))
        {
            echo time() . " Error.  Unable to insert completed tag result into table.  $db->error";
            return false;
        }
        
          $deleteQry = "DELETE FROM " . QUEUED_TAG_INDEX_TABLE . " WHERE tagID = $this->tagID";
        if ( !$db->query($deleteQry))
        {
            echo "Error.  Unable to delete tag from queued table. $db->error";
            return false;
        }
        	return true;
        
        }
       
        $calculatedArray = array();
        foreach($rtaMathArray as $varName => $rtaMathObj)
        {
            $calculate = $rtaMathObj->tabulate();  // returns true if successful or false if not, if no calculation is performed then it stores the data in 'nonCalculated'
            if (!$calculate)
                continue;
            elseif (isset($rtaMathObj->results['average']))   
                $calculatedArray[$varName] = $rtaMathObj->results['average'];
            elseif (isset($rtaMathObj->results['sum']))
                $calculatedArray[$varName] = $rtaMathObj->results['sum'];
            else
                $calculatedArray[$varName] = $rtaMathObj->results['nonCalculated']; // Happens if varName is a string so we just save away the string
        }
        if (!array_key_exists ('totalTons', $calculatedArray))  // This is only here just in case totalTons was not originally in the RTA table (should always be there)
        {
        	
            $mathObj = $rtaMathArray['avgMassFlowTph'];
            if($mathObj)
                $calculatedArray['totalTons'] = $mathObj->getTotalTons($mathObj->massflowVector, $rtaCfgObj->timeConversionMassflowWeight);
        }
        unset($this->tagGroupName);
        if (!$this->databaseWrite($db, $calculatedArray))
        {
            echo "Error.  Unable to write tag to the database. $db->error";
            return false;
        } 
    }  // end of processTag()

    //---------------------------------------------------------------------------------------------
    // retrieveData() - gathers RTA data and stores it in rtaMath classes for each variable
    //---------------------------------------------------------------------------------------------
    function retrieveData($db, $tablename, $rtaCfgObj)
    {
        // Convert times from string format to Unix time and round down to nearest 5 seconds
        $endTic = floor(strtotime($this->LocalendTime)/5)*5;
        $this->LocalendTime = date("Y-m-d H:i:s",$endTic);
        $startTic = floor(strtotime($this->LocalstartTime)/5)*5;
        $this->LocalstartTime = date("Y-m-d H:i:s",$startTic);
        
        //Abhi920841
        // Retrieve data from database
        $dataSelectQry = "SELECT * FROM $tablename WHERE LocalendTime >= '{$this->LocalstartTime}' AND LocalendTime <= '{$this->LocalendTime}' and totalTons > 0";   //LR20200918
        $dbResponse = $db->query($dataSelectQry);
				
        if ($dbResponse->num_rows == 0 || $dbResponse == false)
            return false;
        // Move data to rtaMath class for calculations
        for ($i=0; $i<$dbResponse->num_rows; $i++)
        {
            $dataRecord = $dbResponse->fetch_assoc();
            // Check to see if arry of rtaMath objects has been created (1st pass only)
            if (!isset ($rtaMathArray))
            {
                $rtaMathArray = array();
                foreach ($dataRecord as $varName => $value)
                {
                    $rtaMathArray[$varName] = new rtaMath($varName);
                    // Set general toggles for *all* variables
                    $rtaMathArray[$varName]->goodDataSecondsWeight = $this->goodDataSecondsWeight;
                    $rtaMathArray[$varName]->massflowWeight = $this->massflowWeight;
                    // Some variables should not be weighted so we check for those
                    $rtaMathArray[$varName]->toggleCheck();
                    $rtaMathArray[$varName]->conversionFactor = $rtaCfgObj->timeConversionMassflowWeight;
                }
            }
            // If we reach here all the rtaMath classes have been created and the proper toggles for calculation have been set
            foreach ($dataRecord as $varName=>$value)
            {
                $rtaMathArray[$varName]->dataVector[$i] = $value;
            }
        }
        foreach ($rtaMathArray as $varName => $rtaMathObj)
        {
            $rtaMathObj->massflowVector = $rtaMathArray['avgMassFlowTph']->dataVector;
            $rtaMathObj->goodDataSecondsVector = $rtaMathArray['goodDataSecs']->dataVector;
        }
        return $rtaMathArray;
 
    }  // end of retrieveData()


      function drawIndvTagGroupTotalHTML($db, $action=NULL)
    {
        $pageType = getPassedVar('pageType');
        $id = $this->tagGroupID;
        $array = getItemName($db,'tagGroupIndex');
     
        echo "<table border='1' style=>";
        echo "<tr>";
        echo "<td>";
        print_r($this->tagGroupID);
        echo "</td>";
        foreach($array as $row => $val)
          {
           echo "<td>";
           print_r($this->$val[0]);
           echo "</td>";
          }

        echo "</tr>";
        echo "</table>";
    } 

    //---------------------------------------------------------------------------------------------
    // databaseWrite() - write tabulated tag data to database 
    //---------------------------------------------------------------------------------------------
    function databaseWrite($db, $calculatedArray)
    {
	echo " in databaseWrite \n";
        $column_names = mysql_column_name_select($db, COMPLETED_TAG_INDEX_TABLE);
        if (empty ($column_names))
            return false;

        foreach ($calculatedArray as $varName => $value)
        {
            // Check to see if the variable is already included in the tag table and if not alter table to add columns for variable
            if (!in_array($varName, $column_names))
            {
                if (!isset ($alterTableQry))
                    $alterTableQry = "ALTER TABLE " . COMPLETED_TAG_INDEX_TABLE;
                $alterTableQry .= " ADD $varName float NULL, \n";
            }
        }
        if ( isset ($alterTableQry))
        {
            $alterTableQry = substr($alterTableQry, 0, -3);
            if ( !$db->query($alterTableQry))
            {
                echo "Error.  Unable to alter completed tag index table to allow for new variables. $db->error";
                return false;
            }
        }

        $insertQry = "INSERT INTO " . COMPLETED_TAG_INDEX_TABLE . "\n  SET\n";
        $selectqry = "select * from tag_group where tagGroupID = '$this->tagGroupID'";

	$results_arr = $db->query($selectqry);
        while($row = $results_arr->fetch_array())
         {
          $massflowSecondsWeight = $row['massflowWeight'];
          $goodDataSecondsWeight = $row['goodDataSecondsWeight'];
         }
 
        foreach ($this as $attribute => $attrValue)
        {
            //AB9621
	    if($attribute == "tagID") continue;
	    if ($attribute == 'status')
                $attrValue = 'completed';
            $insertQry .= "    $attribute = '$attrValue', \n";
        } 
        foreach ($calculatedArray as $varName => $value)
        {
	    //AB82221
		if($varName == "tagID") continue;
            $insertQry .= "    $varName = '$value', \n";
        }
        
        $massflowSecondsWeight = ($massflowSecondsWeight >0) ? "1": "0";
        $goodDataSecondsWeight = ($goodDataSecondsWeight >0) ? "1": "0";

	$insertQry .= "     massflowWeight= '$massflowSecondsWeight', \n";
        $insertQry .= "     goodDataSecondsWeight= '$goodDataSecondsWeight', \n";
        $insertQry = substr($insertQry, 0, -3);
        //echo $insertQry;
	if ( !$db->query($insertQry))
        {
            echo time() . " Error.  Unable to insert completed tag result into table.  $db->error";
            return false;
        }

        $deleteQry = "DELETE FROM " . QUEUED_TAG_INDEX_TABLE . " WHERE tagID = $this->tagID";
        if ( !$db->query($deleteQry))
        {
            echo "Error.  Unable to delete tag from queued table. $db->error";
            return false;
        }

       
        return true;
    }  // end of databaseWrite()




    //---------------------------------------------------------------------------------------------
    // retrieveLastInProgressTagID() - returns the most recently completed tagID
    //---------------------------------------------------------------------------------------------
      function retrieveLastInProgressTagID($db)
    {
        $selectQry = "SELECT max(tagID) FROM " . QUEUED_TAG_INDEX_TABLE;
        $dbResponse = $db->query($selectQry);
        if (!$dbResponse || $dbResponse->num_rows == 0)
        {
            echo "Error.  Unable to retrieve the most recent tag.  $db->error";
            return false;
        }
        $tag = $dbResponse->fetch_row();
        $tagID = $tag[0];
        if ($tagID == NULL)   // This happens if there are no tags in the table so it returns null
        {
            echo "Error.  There are no tags in the database.\n";
            return false;
        }
        return $tagID;
    }  // end of retrieveLastInProgressTagID()


    //---------------------------------------------------------------------------------------------
    // retrieveLastCompletedTagID() - returns the most recently completed tagID
    //---------------------------------------------------------------------------------------------
      function retrieveLastCompletedTagID($db)
    {
        $selectQry = "SELECT max(tagID) FROM " . COMPLETED_TAG_INDEX_TABLE;
        $dbResponse = $db->query($selectQry);
        if (!$dbResponse || $dbResponse->num_rows == 0)
        {
            echo "Error.  Unable to retrieve the most recent tag.  $db->error";
            return false;
        }
        $tag = $dbResponse->fetch_row();
        $tagID = $tag[0];
        if ($tagID == NULL)   // This happens if there are no tags in the table so it returns null
        {
            echo "Error.  There are no tags in the database.\n";
            return false;   
        }
        return $tagID;
    }  // end of retrieveLastCompletedTagID()


    //---------------------------------------------------------------------------------------------
    // retrieveAllQueuedTags() - returns all 'queued' tags (where end time has not finished)
    //---------------------------------------------------------------------------------------------
      function retrieveAllQueuedTags($db, $start_time, $end_time, $tagGroupID)
    {
        if ( isset ($start_time) && isset ($end_time))
        {
            $qualifyingString = "queued' AND LocalendTime >= '$start_time' AND LocalendTime <= '$end_time";
        }
        else if (isset($tagGroupID))
        {
            $qualifyingString = "queued' AND tagGroupID = '$tagGroupID";
        }
        else
            $qualifyingString = "queued";

        $tagList = retrieveAllObjectsFromMYSQL($db, 'tagID', QUEUED_TAG_INDEX_TABLE, 'rtaTagObj','status',$qualifyingString, 'DESC');
       
        if (!$tagList)
            $tagList = array();
        return $tagList;

    } // end of retrieveAllQueuedTags()
 

    //---------------------------------------------------------------------------------------------
    // retrieveCompletedTags() - returns completed tags between a user defined start and stop time
    //---------------------------------------------------------------------------------------------
    function retrieveCompletedTags($db, $start_time, $end_time, $numRecords, $tagGroupID,$page)
    {

		if ($start_time == NULL && $end_time == NULL)
            $numRecords = 15;
        if ($end_time == NULL && $start_time != NULL)
            $end_time = date("Y-m-d H:i:s");
        if ($end_time != NULL && $start_time == NULL)
            $numRecords = 15;
			
		if(isset($page))
		{
			if($page == 1)
				$numRecords = "0, $numRecords";
			else
				$numRecords = (($page-1) * $numRecords)+1 . ", $numRecords";
		}
			
        if ( isset ($start_time) && isset ($end_time))
        {
            $qualifyingString = "completed' AND LocalendTime >= '$start_time' AND LocalendTime <= '$end_time' AND validTag = 'yes";
        }
        else if (isset($tagGroupID))
        {
            $qualifyingString = "completed' AND tagGroupID = '$tagGroupID' AND validTag = 'yes";
        }
        else
            $qualifyingString = "completed' AND validTag = 'yes";			
		
        $tagList = retrieveAllObjectsFromMYSQL($db, 'tagID', COMPLETED_TAG_INDEX_TABLE, 'rtaTagObj', 'status', $qualifyingString, 'DESC', $numRecords, '=', 'LocalendTime');
        if($tagList == NULL)
        {
           if ( isset ($start_time) && isset ($end_time))
        {
            $qualifyingString = "completed' AND LocalendTime >= '$start_time' AND LocalendTime <= '$end_time' AND validTag = 'yes";
        }
        else
            $qualifyingString = "completed' AND validTag = 'yes";

        $tagList = retrieveAllObjectsFromMYSQL($db, 'tagID', COMPLETED_TAG_INDEX_TABLE, 'rtaTagObj', 'status', $qualifyingString, 'DESC', $numRecords, '=', 'LocalendTime');
        }

        if (!$tagList)
            $tagList = array();
        return $tagList;

    } // end of retrieveCompletedTags()

    //---------------------------------------------------------------------------------------------
    // retrieveAllInProgressTags() - returns all 'in progress' tags (where start time has passed but end time has not)
    //---------------------------------------------------------------------------------------------
    function retrieveAllInProgressTags($db, $groupID = NULL)
    {
        $now = date ("Y-m-d H:i:s");
        $tagList = retrieveAllTagDataFromMYSQL($db, 'tagID', QUEUED_TAG_INDEX_TABLE, 'rtaTagObj', 'status', "queued' AND LocalstartTime <= '$now", 'DESC',$groupID);

        if (!$tagList)
            $tagList = array();
        return $tagList;

    } // end of retrieveAllInProgressTags()


    //---------------------------------------------------------------------------------------------
    // retrieveAllCompletedTags() - returns all 'completed' tags (where end time has passed)
    //---------------------------------------------------------------------------------------------
    function retrieveAllCompletedTags($db, $groupID = NULL)
    {
        $now = date ("Y-m-d H:i:s");
        $tagList = retrieveAllTagDataFromMYSQL($db, 'tagID', COMPLETED_TAG_INDEX_TABLE, 'rtaTagObj', 'status', "completed' AND validTag='yes", 'DESC', $groupID);
        if (!$tagList)
            $tagList = array();
        return $tagList;

    } // end of retrieveAllInCompletedTags()


    //---------------------------------------------------------------------------------------------
    // drawIndv
    //HTML () - draws the HTML code for a single table row for an indvidual tag
    //---------------------------------------------------------------------------------------------
    function drawIndvTagIndexHTML($db, $action=NULL)
    {
        $pageType = getPassedVar('pageType');
        $id = $this->tagID;

        echo "<tr>";
        echo "<form id='form_$id' action='realTimeDisplay.php?pageType=$pageType' method='POST' target='_self' onSubmit='return validateForm(this)'>";
        echo "<input type='hidden' name='tagID' value='$id'>";
        echo "<input type='hidden' name='perform' value='modifyTag'>";
        echo "<td></td>";
        if($pageType == 'tagIndex')
        echo "<td><input type='text' name='tagID' size = 2 maxlength = 4 value='$this->tagID' disabled></td>";
        echo "<td><input type='text' name='tagName' size = 15 maxlength=40 class='string canBeNull possibleRequired' value='$this->tagName' disabled></td>";

        // Get available Tag Groups
        echo "<td><select name='tagGroupName' class='string possibleRequired' size='1' disabled>";

        //$resultArray = getNameForID($db,$this->tagGroupID);


        $tagGroupArray = retrieveString($db, TAG_GROUP_TABLE, 'tagGroupID');
        if ($tagGroupArray == 'NoData')
        {
            echo "Error.  Unable to retrieve enum for tagGroupName";
        }

        foreach ($tagGroupArray as $tagGroup)
        {
            if ($tagGroup == $this->tagGroupID)
            {
               $resultArray = getNameForID($db,$tagGroup);
               foreach($resultArray as $tagAttr)
               {
                echo "<option value='$tagAttr' selected>$tagAttr</option>";
               }
            }
            else
               {
                $resultArray = getNameForID($db,$tagGroup);
               foreach($resultArray as $tagAttr)
               {
                echo "<option value='$tagAttr'>$tagAttr</option>";
               }

               }
        }
        echo "</select></td>";

        $random = rand();
        echo "<td><input type='text' id='LocalstartTime_$random' name='LocalstartTime' class='date' size='18' value='$this->LocalstartTime' disabled title='enter date and time as: &#013YYYY-MM-DD hh:mm:ss'></td>";
        if (($action != 'index')&& ($action != ''))
        {
            echo "<td>";
            ?>
            <button id=start_time_trigger_<?php echo $random;?> title='Click for date picker' disabled>...</button>

            <script type='text/javascript'>//<![CDATA[
            Sabia.DtPicker.setup({
            inputField       : 'LocalstartTime_<?php echo $random;?>',
            button           : 'start_time_trigger_<?php echo $random;?>'
            });
            //]]></script>
            </td>
            <?php

        }
        echo "<td><input type='text' id='LocalendTime_$random' name='LocalendTime' class='date' size='18' value='$this->LocalendTime' disabled title='enter date and time as: &#013YYYY-MM-DD hh:mm:ss'></td>";
        if (($action != 'index') && ($action != ''))
        {
            echo "<td>";
            ?>
            <button id=end_time_trigger_<?php echo $random;?> title='Click for date picker' disabled>...</button>

            <script type='text/javascript'>//<![CDATA[
            Sabia.DtPicker.setup({
            inputField       : 'LocalendTime_<?php echo $random;?>',
            button           : 'end_time_trigger_<?php echo $random;?>'
            });
            //]]></script>
            </td>
            <?php

        }
        echo "<td />";

        $rtaConfigsArray = retrieveAllObjectsFromMYSQL($db, 'rtaMasterID', RTA_MASTER_CONFIG_TABLE, 'rtaMasterConfig', 'accessLevel', $_SESSION['loginGroup'], NULL, NULL, '>=');
        if (!$rtaConfigsArray)
        {
            echo "Error.  Unable to retrieve the list of data sources.";
            return false;
        }
      
        if ($action == 'create')
            echo "<td><td><td><input type='button' value='New Tag' style='background-color:#66C285' onclick=editForm(this.form,'New',this)></td></td></td>";
        else
        {
            echo "<td>";
            $url = "realTimeDisplay.php?pageType=tagView&tagID=$this->tagID";
            echo "</td>";
        }
        if ($action == 'index')
        {
            echo "<td>";
            echo "<input type='button' value='Delete' onclick=formSubmit(this.form,'tagIndexDeleteTag','delete');>";
            echo "</td>";
        }
        else
        {
            if ($action == 'create')
                echo "<td></td>";
            else
            {

                echo "<td><input type='button' value='Edit' onclick=editForm(this.form,'Edit')>";
                echo "</td>";
                echo "<td>";
                echo "<input type='button' value='Delete' onclick=formSubmit(this.form,'Delete','delete')>";
                echo "</td>";
                echo "<td>";
                echo "<input type='button' value='Stop' style='background-color:#FF6666'  onclick=formSubmit(this.form,'Stop','stop')>";
                echo "</td>";
            }
            echo "<td>";
            echo "<input type='submit' value='Submit' disabled>";
            echo "</td>";
        }
        echo "</form>";
        echo "</tr>";
        return true;
    } // end of drawIndvTagIndexHTML()


      function populateFromWeb()
    {
        foreach ($this as $attribute => $value)
        {
            $this->$attribute=getPassedVar($attribute);
        }
        return true;
    }  //end of populateFromWeb()
 

      function tagDataCheck($actionType = 'Edit')
    {
        foreach ($this as $attribute => $value)
        {
            $noCheckForEmpty = array ( 'tagName','tagGroupID');
            // At least one of the string fields must contain some kind of identifying name so we check for that
            if ( in_array ($attribute, $noCheckForEmpty))
            {
                $nonNullCheck = 0;
                foreach ($noCheckForEmpty as $name)
                {
                    if ( !empty($this->$name))
                    {
                        $nonNullCheck++;
                    }
                }
                if ($nonNullCheck == 0)
                {
                    echo "Error.  Either the tag Name or tagGroupID must contain a value";
                    return false;
                }
            }
            else
            {
                if ($attribute == 'status')
                    $this->$attribute='queued';
                if ($attribute == 'tagID' && $actionType == 'New')
                    continue;    // SK:new tags will not have a tagID yet
            }
        }
        return true;
    }  //end of tagDataCheck()


      function updateTag($db)
    {
        $updateQry = 'UPDATE ' . QUEUED_TAG_INDEX_TABLE . " SET\n";
        $tagGroupName = $this->tagGroupName;
        $selectQry = "SELECT * from tag_group where tagGroupName ='$tagGroupName'";
        $arr = $db->query($selectQry);

         while($row = $arr->fetch_array())
         {
          $this->tagGroupID = $row['tagGroupID'];
          $this->rtaMasterID = $row['rtaMasterID'];
         }
        unset($this->tagGroupName); //SK:remove from the instance as QUEUED_TAG_INDEX_TABLE does not have a field of tagGroupName.
        foreach ($this as $attribute => $value)
        {
            $updateQry .= "$attribute='$value',\n";
        }
        $updateQry = substr ($updateQry, 0, -2);
        $updateQry .= "WHERE tagID = '$this->tagID'";
        if (!$db->query($updateQry))
        {
            echo "Error.  Unable to update tag in database.  $db->error";
            return false;
        }
        return true;
    } // end of updateTag()



      function insertTag($db)
    {
        $insertQry = 'INSERT ' . QUEUED_TAG_INDEX_TABLE . " SET\n";
        $tagGroupName = $this->tagGroupName;

        $selectQry = "SELECT * from tag_group where tagGroupName ='$tagGroupName'";
        $arr = $db->query($selectQry);
        while($row = $arr->fetch_array())
         {
          $tagGroupID = $row['tagGroupID'];
          $this->rtaMasterID = $row['rtaMasterID'];
         }
        unset($this->tagGroupName); //remove from the instance as QUEUED_TAG_INDEX_TABLE does not have a field of tagGroupName.
        
        foreach ($this as $attribute => $value)
        {
            $insertQry .= "$attribute='$value',\n";
        }
        $insertQry .= "tagGroupID ='$tagGroupID',\n"; // SK:tagGroupID is not part of the QueuedTable.

        $insertQry = substr ($insertQry, 0, -2);
        if (!$db->query($insertQry))
        {
            echo "Error.  Unable to create tag in database.  $db->error";
            return false;
        }else {
			$curtime = date("Y-m-d H:i:s");
			$inProgressTagList = retrieveAllTagDataFromMYSQL($db, 'tagID', QUEUED_TAG_INDEX_TABLE, 'rtaTagObj', 'status', "queued' AND LocalstartTime <= '$curtime", 'DESC',$tagGroupID);
			if ( !empty($inProgressTagList))
			{
				$firstTag = 1;
				foreach ($inProgressTagList as $tagObj)
				{
					  if($firstTag){
						$firstTag = 0;
						continue;
					  }
					  $tagId = $tagObj->tagID;
					  $updQry = "Update " . QUEUED_TAG_INDEX_TABLE . " SET LocalendTime='{$curtime}' WHERE tagID=$tagId";
					  if (!$db->query($updQry))
					  {
						echo "Error.  Unable to create tag in database.  $db->error";
						return false;
					  }//if !updquery
				}//foreach
			}//if !empty
        }

        return true;
    }  // end of insertTag


      function deleteTag($db, $tablename)
    {
        $deleteQry = "DELETE FROM $tablename WHERE tagID = '$this->tagID'";
        if (!$db->query($deleteQry))
        {
            echo "Error.  Unable to delete tag from database.  $db->error";
            return false;
        }
        return true;
    } // end of deleteTag()


} // end of rtdTagClass definition


//-------------------------------------------------------------------------------------------------
// RTA recreate/regenerate data class definition
//-------------------------------------------------------------------------------------------------
class rtaRegenObj{
    var $jobID;                                  // auto assigned RTA regenerating task ID
    var $jobStatus;                              // job status toggle, possible controls are: 'started', 'in progress', 'completed', 'aborted', 'paused'
    var $linuxPID;                               // Linux process ID controlling the PHP running the process
    var $start_time;                             // the specified start time for data to be included in the RTA data recreation task
    var $end_time;                               // the specified end time for data to be included in the RTA data recreation task
    var $backupTable;                            // Backup table containing original RTA data
    var $tempTable;                              // Temp table name contonaining the original data that will subsequently be recreated
                                                 //    and afterwards having that ID deleted from this table
    var $regenTable;                             // Table containing data recreated from Temp Table, and after all data has been
                                                 //    recreated then this table will be renamed to the original table (after being dropped)
    var $originalTable;                          // Original RTA table with original data
    var $originalTableID;                        // Original table ID from rta_DB_config table
    var $loopsFinished;                          // Number of times the original table has been checked to see if new data has been added since data recreation began
    var $recordsRemaining;                       // Number of records remaining to be recreated in temp table
    var $recordsTotal;                           // Total number of records that will eventually be recreated
    var $maxID;                                  // The highest autoincremented ID retrieved from the temp table to be recreated
    var $dateAdded;                              // Date/time job was added to job table
    var $dateModified;                           // Date/time job was modified, paused, aborted, resumed, or completed
    var $dateCompletd;                           // Date/time job was completed
 
    //---------------------------------------------------------------------------------------------
    // constructor
    //---------------------------------------------------------------------------------------------
    function rtaRegenObj(
        $data_type = 'analysis',                 // Data type (analysis, spectrum, raw coefficient, etc.)
        $DB_ID_string = NULL                     // Original table name
        )
    {
        $rand = rand(10000,99999);
        $this->jobStatus = 'started';
        $this->backupTable = $data_type . "_" . $DB_ID_string . '_backup_' . $rand;   // generate a random named backup
        $this->tempTable = $data_type . "_" . $DB_ID_string . '_temp_' . $rand;
        $this->regenTable = $data_type . "_" . $DB_ID_string . '_regen_' . $rand;
        $this->dateAdded = date ("Y-m-d H:i:s");
        $this->dateModified = $this->dateAdded;
        $this->loopsFinished = 0;

    } // end of rtaRegenObj() constructor

      function restoreBackup(
        $db                                      // MySQLi database connection to RTA database
        )
    {
        // TODO:  Need to check to make sure that the backup table exists before dropping the original table
        $dropQry = "DROP TABLE IF EXISTS " . $this->tempTable . ", " . $this->regenTable . ", " . $this->originalTable;
        if (!$db->query($dropQry))
        {
            echo "Error.  Unable to delete temporary tables. $db->error.";
            return false;
        }
        $restoreQry = "RENAME TABLE " . $this->backupTable . " TO " . $this->originalTable;
        if (!$db->query($restoreQry))
        {
            echo "Error.  Unable to restore backup.  $db->error.";
            return false;
        }

        return true;
    } // end of restoreBackup()


      function dropTempTables()
    {
        $dropQry = "DROP TABLE IF EXISTS " . $this->tempTable . ", " . $this->regenTable;
        if (!$db->query($dropQry))
        {
            echo "Error.  Unable to drop temporary tables.  $db->error.";
            return false;
        }

        $deleteQry = "DELETE FROM " . RTA_REGEN_JOB_TABLE . " WHERE jobID = " . $this->jobID;
         if (!$db->query($deleteQry))
         {
             echo "Error.  Unable to delete RTA recreate task ID. $db->error.";
             return false;
         }

        return true;

        
    }  // end of dropTempTables()

    
      function retrieveJob( $db, $jobID )
    {
        if (!populateObjFromMYSQL($db, $this, $jobID, 'jobID', RTA_REGEN_JOB_TABLE)) 
        {
            echo "Error.  Unable to retrieve task information. $db->error.";
            return false;
        }

        return true;

    } // end of retrieveJob()


    function runBatch( 
        $db,                                     // Mysqli database connection
        $rtaObj,                                 // rtaConfigObj for the process that we're recreating
        $batchSize                               // number of records retrieved from tempTable and reanalyzed and written to regenTable
        )
    {
        $rtaCfgClone = obj_clone ($rtaObj);
        // New records are in the original table that need to be transferred over to the temp table
        // We'll do this in batch mode instead of one by one, which gives the user the option.  Larger batches should run faster
        $selectQry = "SELECT * FROM $this->tempTable LIMIT $batchSize";
        $mysqli_results_obj = $db->query($selectQry);
        if (!$mysqli_results_obj)
        {
            echo "Error.  Unable to retrieve database records to regenerate results.  $db->error";
            return false;
        }
        $tablename = $this->regenTable;
        $resultCount = $mysqli_results_obj->num_rows;
        for ($i=0; $i<$resultCount; $i++)
        {
            $resultArray[$i] = $mysqli_results_obj->fetch_assoc();

            // Here we are assuming that the records to be recreated have the same parameters as the RTA configuration
            //      the other option is to take the subinterval and other params from the RTA data record and use them to recreate the record
            $rtaCfgClone->endTic = $resultArray[$i]['endTic'];
            // It is time to run an analysis
            $errStr = "";
            $rtaProcCall = $rtaCfgClone->rtaProcess($db, $tablename);
            if ($rtaProcCall == false)
                $errStr .= "rtaProc failure";

            if (!empty($errStr))
            {
                echo "Error recreating data for " . $resultArray[$i]['endTic'] . ".  " . $db->error;
                return false;
            }
            // Now that we wrote to the regenTable we can delete it from the temp table
            $deleteQry = "DELETE FROM $this->tempTable WHERE endTic='" . $resultArray[$i]['endTic'] . "'";
            if (!$db->query($deleteQry))
            {
                echo "Error.  Unable to delete record from temporary table while recreating data.  $db->error";
                return false;
            }
        }
        $mysqli_results_obj->free();
        // Update status information for job
        $updateQry = "UPDATE " . RTA_REGEN_JOB_TABLE . " SET recordsRemaining = (SELECT count(endTic) from $this->tempTable) WHERE jobID='$this->jobID'";
        if (!$db->query($updateQry))
        {
            echo "Error.  Unable able to update status information to job table.  $db->error";
            return false;
        }
        return $resultCount;  // return how many records were run
    }  // end of runBatch() 
  }   // end of rtaRegenObj class definition


?>
