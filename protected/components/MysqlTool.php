<?php
/**
 * Description of MysqlTool
 *
 * @author webtatva
 */
class MysqlTool {

//------------------------------------------------------------------------------
// mysqlTools.php -- low level mysql tools
//------------------------------------------------------------------------------ 
//-------------------------------------------------------------------------------------------------
// retrieveAllObjectsFromMYSQL() - retrieves *all* entries in database table and returns an array of those particular objects
//-------------------------------------------------------------------------------------------------
    function retrieveAllObjectsFromMYSQL(
    $objIDName, //!< Column name for the objectID
            $tablename, //!< Table where the object information resides in database
            $objClass, //!< PHP class name of object
            $parentIDName = NULL, //!< [OPTIONAL] column name of parent object ID if we want to add a WHERE clause to SELECT query
            $parentID = NULL, //!< [OPTIONAL] id of parent object 
            $sortOrder = 'ASC', //!< [OPTIONAL] indicates how the result should be sorted
            $limit = NULL, //!< [OPTIONAL] limit to number of items returned (records come from the first part of data set)
            $whereSymbol = '=', //!< [OPTIONAL] determines what type of identifier in the where clause (=,>,<, etc.)
            $sortObject = NULL, //!< [OPTIONAL] used if the sort column is different from the main attribute column
            $tagGroupID = NULL, // {OPTIONAL] 
            $showPageLayout = NULL                       //! [OPTIONAL] used to check if show Page Layout is set to yes.
    ) {


        $db = Yii::app()->db;
        // Gather all existing
        if ($sortObject == NULL)
            $sortObject = $objIDName;
        $selectQry = "SELECT $objIDName FROM $tablename";
        if ($parentIDName != NULL && $parentID != NULL)
            $selectQry .= " WHERE $parentIDName $whereSymbol '$parentID'";
        if ($showPageLayout != NULL)
            $selectQry .= " AND showPageLayout = '$showPageLayout'";
        $selectQry .= " ORDER BY $sortObject $sortOrder";
        if ($limit != NULL)
            $selectQry .= " LIMIT $limit";


        $db->
                $dbResponse = $db->createComand($selectQry)->query();

        if (empty($dbResponse) || $dbResponse->num_rows == 0)
            return false;
        $outputArray = array();

        for ($i = 0; $i < $dbResponse->num_rows; $i++) {
            $obj = new $objClass;
            $id = $dbResponse->fetch_row();

            populateObjFromMYSQL($db, $obj, $id[0], $objIDName, $tablename);
            if ($obj == false) {
                echo "Error.  There are no $objIDName in $tablename.  $db->error";
                return false;
            }
            $outputArray[$i] = $obj;
        }
        $dbResponse->free();
        return $outputArray;
    }

// end retrieveAllObjectsFromMYSQL()
//---------------------------------------------------------------------------------------------
//! populateObjFromMYSQL() - retrieves a single object from a database and populates parent object
//---------------------------------------------------------------------------------------------
    function populateObjFromMYSQL(
    $parentObject, //!< parent object that will be populated
            $objID, //!< ID for the object being requested
            $objIDName, //!< Column name for the object ID
            $tablename                               //!< Table where the object information resides in database
    ) {
        $db = Yii::$app->db;
        $selectQry = "SELECT * FROM $tablename WHERE $objIDName = '$objID'";

        $dbResponse = $db->createCommand($selectQry)->query();
        if ($dbResponse->num_rows == 0 || !$dbResponse) {
            if (DEBUG)
                echo "Error.  Unable to retrieve $objIDName (ID: $objID) from database.  $db->error.";
            return false;
        }

        foreach ($dbResponse->fetch_assoc() as $attribute => $value) {
            // This requires that the class match the database table exactly otherwise do each attribute individually
            $parentObject->$attribute = $value;
        }
        $dbResponse->free();
        return true;
    }

// end of populateObjFromMYSQL()

    function mysql_delete_string($db, $delete_qry_string) {
        $db_delete_qry = $db->query($delete_qry_string);  // Returns true if sucessful and false if not
        if (!$db_delete_qry) {
            if (DEBUG)
                echo "Deletion Failure.  No rows were deleted from database.\n";
            return false;
        }
        return true;
    }

    function mysql_insert_string($db, $insert_qry_string) {
        $db_insert_qry = $db->query($insert_qry_string);  // Returns true if successful and false if not
//   echo "\n$db->error\n";
        if (!$db_insert_qry) {
            if (DEBUG)
                echo "Insertion Failure.  No rows were inserted into database.\n  $db->error";
            return false;
        }

        return true;
    }

    function database_connect($host, $username, $password, $database) {

        // Create an database connection object using the username, host, and password for a particular user
        $db = new mysqli($host, $username, $password, $database);

        if ($db->connect_error != NULL) {
            echo "Connection Error:  Could not connect to database. $db->connect_error";
            return false;
        }

        if (DEBUG)
            echo "Connected to database successfully\n";

        return $db;
    }

    function mysql_select_multiple($db, $table, $select_qry) {
        $qryObj = $db->query($select_qry);  // returns a MySQLi_result object
        if (!$qryObj) {
            if (DEBUG) {
                echo "$db->error\n";
                echo "Select error: mysql_select not able to read database table: $table.\n";
                foreach ($db as $key => $value)
                    echo "$key = $value\n";
            }
            return false;
        }
        $rows = $qryObj->num_rows;
        $qryArray = array();
        for ($i = 0; $i < $rows; $i++) {
            $tmp = $qryObj->fetch_row();
            $qryArray[$i] = $tmp[0];
        }
        return $qryArray;
    }

//To-Do:  this formula needs to be checked.
    function mysql_select($db, $table, $select_qry) {
        $qryObj = $db->query($select_qry);   // returns a MySQLi_result object
        if (!$qryObj) {
            if (DEBUG) {
                echo "Select error: mysql_select not able to read database table: $table.\n";
                foreach ($db as $key => $value)
                    echo "$key = $value\n";
            }
            return false;
        }

        $qry_array = array();

        //Convert the MySQLi_result object into an array, looping to create an entry for each database row result returned 
        $rows = $qryObj->num_rows;
        for ($i = 0; $i < $rows; $i++) {
            $qry_array[$i] = $qryObj->fetch_assoc();
        }

        if (DEBUG)
            print_r($qry_array);

        return $qry_array;
    }

    function mysql_table_check($databaseName, $tableName) {
        $db = database_connect('localhost', 'rta', 'discoverysu', RTA_DB_NAME);
        if (!$db)
            exit;

        $selectQry = "SELECT does_table_exist('$databaseName', '$tableName')";
        $result = $db->query($selectQry);
        if ($result === false)
            return false;
        $functionResult = $result->fetch_row();
        if ($functionResult[0] != 1)   // returns an enumerated array with one element which is a boolean indicating whether the function call was successful or not
            return false;
        return true;
        $db->close();
    }

    /*
      function mysql_table_check($db, $table)
      {
      // This function is solely to tell us if a table exists
      $select_tables = "select `table_name` from information_schema.tables where `table_schema`='" . RTA_DB_NAME . "' and `table_name`='$table'";
      $table_name_qry = $db->query($select_tables);
      //   if ($db->error != null)
      //      echo "mysql_table_check error:  $db->error\n";
      if ($table_name_qry === false || $table_name_qry->num_rows == 0)
      return false;    // table does not exist or we were unable to read the database
      return true;  // table does exist
      }
     */

    function mysql_column_name_select($db, $table) {
        // This allows us to make changes to the mysql table, and not have to modify this php code
        // The names in the mysql table *MUST* be exactly equal to the analysisObject otherwise the data will not pass since it can't find the column names
        $select_columns = "select column_name from information_schema.columns where table_name='$table'";
        $dbFields = $db->query($select_columns);
        if ($dbFields == false || $dbFields->num_rows == 0) {
            echo "Database read error:  Failed to read $table.  </br>";
            return false;
        }
        $row = $dbFields->num_rows;

        //Convert the MySQLi_result object to a readable array
        for ($i = 0; $i < $row; $i++) {
            $s = $dbFields->fetch_row();
            $column_names[$i] = $s[0];
        }

        $dbFields->close();

//   if ( DEBUG )
//      print_r ($column_names);

        return $column_names;
    }

    function insertCalResultFromDOM($db, $combinedResults, $rtaCfgObj, $tablename, $column_names, $endTic) {
        // used to create an insert query from an array from the multiple server Averaging feature
        $insertQry = "insert into $tablename set\n";

        $s = 0;
        // $combinedResults is a DOMElement with childNodes which are DisplayResult Objects in XML form, there is only 1 bulkaverage for combinedResults
        // since we are not allowing multiple bulk averages for calibrated results
        foreach ($combinedResults as $varNode) {
            if (!in_array($varName->tagName, $column_names)) {  // Create an entry in the table if the variable is not already in the database table
                if (!isset($create_qry_string))
                    $create_qry_string = "ALTER TABLE $tablename \n";
                $create_qry_string .= "ADD $varName float (8,3) NULL DEFAULT NULL,\n";
            }
//var_dump ($varNode->getElementsByTagName('value'));
            $insertQry .= $varNode->tagName . " = '" . $varNode->getElementsByTagName('value') . "',\n";
            // Since each server should be set up for exactly the same output we'll take the first record to get massflow, total tons, and good dataseconds
            // Note:  If a particular server does not have a given variable the multiple server average result will still calculate 
            // but it will not match on total tons and good data seconds, and the result will only be a single server result
            if ($s === 0) {
                // Make sure that the totalTons is in the RTA table
                if (!in_array('totalTons', $column_names)) {
                    if (!isset($create_qry_string))
                        $create_qry_string = "ALTER TABLE $tablename \n";
                    $create_qry_string .= "ADD totalTons float (8,3) NULL DEFAULT NULL,\n";
                }
                // Calculate the massflow rate through all the analyzers cumuluatively
                $insertQry .= "avgMassFlowTph = '" . $varData['avgMassFlowTph'] . "',\n";
//            if (($varData['goodDataSecs']== 0) || ($rtaCfgObj->timeConversionTonnageWeight == 0))
//                $insertQry .= "0',\n";
//            else
//                $insertQry .= $varData['totalTons'] / $varData['goodDataSecs'] / $rtaCfgObj->timeConversionTonnageWeight . "',\n";
                $insertQry .= "goodDataSecs = '" . $varData['goodDataSecs'] . "',\n";
                $insertQry .= "totalTons = '" . $varData['totalTons'] . "',\n";
                $s++;
            }
        }
        // Create new columns for new variables that were created in the calibration
        if (isset($create_qry_string)) {
            // Remove the comma and new line at the end of the string
            $create_qry_string = substr($create_qry_string, 0, -2);

            if (!$db->query($create_qry_string)) {
                echo "Creation Problem.  Problem creating columns for new displayResult. Error:  $db->error\n";
                return false;
            }

            if (DEBUG)
                echo "Database column created.\n";
        }

        $insertQry .= "incrementInterval = '" . $rtaCfgObj->averaging_subinterval_secs . "',\n";
        $insertQry .= "span = '" . $rtaCfgObj->analysis_timespan . "',\n";
        $insertQry .= "bulkAvgPeriod = '" . $rtaCfgObj->analysis_timespan . "',\n";
        $insertQry .= "subintervalMode = '1',\n";
        $insertQry .= "subintervalSecs = '" . $rtaCfgObj->averaging_subinterval_secs . "',\n";
        $insertQry .= "weighted ='1',\n";

        $date_format = "Y-m-d H:i:s";
        $insertQry .= "endTic = '$endTic',\n";
        $insertQry .= "rta_DB_ID = '" . $rtaCfgObj->rta_DB_ID . "',\n";
        $insertQry .= "GMTendTime = '" . gmdate($date_format, $endTic) . "',\n";
        $insertQry .= "LocalendTime = '" . date($date_format, $endTic) . "',\n";
        $startTic = $endTic - $rtaCfgObj->analysis_timespan;
        $insertQry .= "startTic = '$startTic',\n";
        $insertQry .= "LocalstartTime = '" . date($date_format, $startTic) . "',\n";

        $insertQry .= "timefield = " . $endTic . "\n";

        if (DEBUG)
            print_r($insertQry);

        return $insertQry;
    }

// end of insertCalResultFromDOM()

    function mysql_insert_AnalysisResult($aObj, $rtaObj, $db, $table, $column_names, $create_col = false) {
        // Set up the mysql insert query for the general attributes of the analysisObj inside the combinedResultsTable
        $insert_qry_string = "insert into $table set\n";

        $s = 0;
        foreach ($aObj->combinedResultsArray[$aObj->detectorID][0] as $dispObject) {
            if (!in_array($dispObject->disp_name, $column_names)) {   // We need to create an entry in the table if the displaylist object is not in the database table
                if ($create_col == true) {
                    if (!isset($create_qry_string))
                        $create_qry_string = "ALTER TABLE $table \n";
                    $create_qry_string .= "ADD " . $dispObject->disp_name . " float(8,2) NULL DEFAULT NULL,\n";
                }
            }

            $insert_qry_string .= "$dispObject->disp_name = '$dispObject->value',\n";

            // The massflow and good data seconds are the same for every variable so we only take the first one and insert it into the database
            if ($s === 0) {
                $insert_qry_string .= "avgMassFlowTph = '$dispObject->avgMassFlowTph',\n";
                $insert_qry_string .= "goodDataSecs = '$dispObject->goodDataSecs',\n";
                $s = 1;
            }
        }

        // Create new columns for new variables that were created in the calibration
        if (isset($create_qry_string)) {
            // Remove the comma and new line at the end of the string
            $create_qry_string = substr($create_qry_string, 0, -2);

            if (!$db->query($create_qry_string)) {
                echo "Creation Problem.  Problem creating columns for new displayResult. Error:  $db->error\n";
                return false;
            }

            if (DEBUG)
                echo "Database column created for $dispObject->disp_name = $dispObject->value\n";
        }

        foreach ($aObj as $attribute => $aValue) {
            if ($attribute == "timeField")  // we're setting timefield last since we can't have a comma ending the mysql query
                continue;
            if (in_array($attribute, $column_names))
                $insert_qry_string .= "$attribute = '" . $aValue . "',\n";
        }

        $date_format = "Y-m-d H:i:s";
        $insert_qry_string .= "rta_DB_ID = '" . $rtaObj->rta_DB_ID . "',\n";
        $insert_qry_string .= "GMTendTime = '" . gmdate($date_format, $aObj->endTic) . "',\n";
        $insert_qry_string .= "LocalendTime = '" . date($date_format, $aObj->endTic) . "',\n";
        $insert_qry_string .= "LocalstartTime = '" . date($date_format, $aObj->startTic) . "',\n";

        $insert_qry_string .= "timefield = " . $dispObject->timetag . "\n";

        if (DEBUG)
            print_r($insert_qry_string);

        return $insert_qry_string;
    }

    function mysql_insert_AlignmentResult($aObj, $rtaObj, $db, $table, $column_names, $create_col = false) {
        // Set up the mysql insert query for the general attributes of the analysisObj inside the combinedResultsTable
        $insert_qry_string = "insert into $table set\n";

        $detectorID = $aObj->detectorID;

        // This assumes that no new alignment attributes need to be created
        foreach ($aObj->alignResults[$detectorID]->alignEntries[0] as $alignmentAttribute => $alignmentValue) { {
                $insert_qry_string .= "$alignmentAttribute" . " = '" . $alignmentValue . "',\n";
            }
        }

        foreach ($aObj as $attribute => $aValue) {
            if ($attribute == "timeField")  // we're setting timefield last since we can't have a comma ending the mysql query
                continue;
            if (in_array($attribute, $column_names))
                $insert_qry_string .= "$attribute = '" . $aValue . "',\n";
        }
        $date_format = "Y-m-d H:i:s";
        $insert_qry_string .= "rta_DB_ID = '" . $rtaObj->rta_DB_ID . "',\n";
        $insert_qry_string .= "GMTendTime = '" . gmdate($date_format, $aObj->endTic) . "',\n";
        $insert_qry_string .= "LocalendTime = '" . date($date_format, $aObj->endTic) . "',\n";
        $insert_qry_string .= "LocalstartTime = '" . date($date_format, $aObj->startTic) . "',\n";

        $insert_qry_string .= "timefield = " . $aObj->timeField . "\n";

        if (DEBUG)
            print_r($insert_qry_string);

        return $insert_qry_string;
    }

    function mysql_insert_Spectrum($spectrumObj, $rtaObj, $db, $table, $column_names, $create_col = false) {
        // Set up the mysql insert query for the general attributes of the analysisObj inside the combinedResultsTable
        $insert_qry_string = "insert into $table set\n";

        $insert_qry_string .= "span = '" . $spectrumObj->span . "',\n";
        $insert_qry_string .= "detectorID = '" . $spectrumObj->detectorID . "',\n";
        $insert_qry_string .= "subintervalSecs = '" . $spectrumObj->bulkAvg . "',\n";
        $insert_qry_string .= "endTic = '" . $spectrumObj->timeStr . "',\n";
        $startTic = $spectrumObj->timeStr - $spectrumObj->span;
        $date_format = "Y-m-d H:i:s";
        $insert_qry_string .= "rta_DB_ID = '" . $rtaObj->rta_DB_ID . "',\n";
        $insert_qry_string .= "GMTendTime = '" . gmdate($date_format, $spectrumObj->timeStr) . "',\n";
        $insert_qry_string .= "LocalendTime = '" . date($date_format, $spectrumObj->timeStr) . "',\n";
        $insert_qry_string .= "LocalstartTime = '" . date($date_format, $startTic) . "',\n";

        for ($i = 1; $i <= 512; $i++) {
            $chan = str_pad($i, 3, 0, STR_PAD_LEFT);
            $array_chan = $i - 1;
            $insert_qry_string .= "specChannel_$chan = '" . $spectrumObj->yarr[$array_chan] . "',\n";
            if ($rtaObj->data_type == "3in1_spectrum") {
                $insert_qry_string .= "yfitChannel_$chan = '" . $spectrumObj->fitarr[$array_chan] . "',\n";
            }
        }

        $insert_qry_string .= "timefield = " . $spectrumObj->timeStr . "\n";

        if (DEBUG)
            print_r($insert_qry_string);

        return $insert_qry_string;
    }

    function mysql_insert_RawCoefResult($aObj, $rtaObj, $db, $table, $column_names, $create_col = false) {
        // Set up the mysql insert query for the general attributes of the analysisObj inside the combinedResultsTable
        $insert_qry_string = "insert into $table set\n";

        foreach ($aObj->parsedResultsArray[$aObj->detectorID][0] as $attribute => $value) {
            // Checks for all the basic attributes associated with the AnalysisResult Object
            if (in_array($attribute, $column_names)) {   // If the column names match the analysisObj attributes add to insert query
                $insert_qry_string .= "$attribute" . " = '" . $value . "',\n";
            }
            if ($attribute == 'rsltElements') {
                // Create arrays containing all the rawcoef names (to check to see if they exist) and the actual values
                foreach ($value as $rawcoefObj) {
                    $element_rawcoef_name[] = $rawcoefObj->elem_name . "_coeff";
                    $element_sigma_name[] = $rawcoefObj->elem_name . "_sigma";
                    $element_rcorr_name[] = $rawcoefObj->elem_name . "_rcorr";
                    $element_area_name[] = $rawcoefObj->elem_name . "_area";
                    $element_rawcoef[] = $rawcoefObj->coeff;
                    $element_sigma[] = $rawcoefObj->sigma;
                    $element_rcorr[] = $rawcoefObj->rcorr;
                    $element_area[] = $rawcoefObj->area;
                }
                $count = count($element_rawcoef_name);
                for ($i = 0; $i < $count; $i++) {
                    //Check to see if the rawcoef element has been added to the rawcoef MYSQL table
                    if (!in_array($element_rawcoef_name[$i], $column_names)) {
                        if ($create_col == true) {
                            // We assume that if the rawcoef name isn't in the table none of the columns have been created
                            if (!isset($create_qry_string))
                                $create_qry_string = "alter table $table\n";
                            $create_qry_string .= "ADD $element_rawcoef_name[$i] float NULL DEFAULT NULL,\n";
                            $create_qry_string .= "ADD $element_sigma_name[$i] float NULL DEFAULT NULL,\n";
                            $create_qry_string .= "ADD $element_rcorr_name[$i] float NULL DEFAULT NULL,\n";
                            $create_qry_string .= "ADD $element_area_name[$i] float NULL DEFAULT NULL,\n";
                        }
                    }
                    $insert_qry_string .= $element_rawcoef_name[$i] . " = '" . $element_rawcoef[$i] . "',\n";
                    $insert_qry_string .= $element_sigma_name[$i] . " = '" . $element_sigma[$i] . "',\n";
                    $insert_qry_string .= $element_rcorr_name[$i] . " = '" . $element_rcorr[$i] . "',\n";
                    $insert_qry_string .= $element_area_name[$i] . " = '" . $element_area[$i] . "',\n";
                }
            }
        }

        // Add new elements to the database table   
        if (isset($create_qry_string)) {
            //Remove the last comma
            $create_qry_string = substr($create_qry_string, 0, -2);

            if (!$db->query($create_qry_string)) {
                echo "Creation Problem.  Problem creating new columns.  Error:  $db->error\n";
                return false;
            }
            if (DEBUG)
                echo "Database column created for $column_name";
        }

        foreach ($aObj as $attribute => $aValue) {
            if ($attribute == "timeField")  // we're setting timefield last since we can't have a comma ending the mysql query
                continue;
            if (in_array($attribute, $column_names))
                $insert_qry_string .= "$attribute = '" . $aValue . "',\n";
        }
        $date_format = "Y-m-d H:i:s";
        $insert_qry_string .= "rta_DB_ID = '" . $rtaObj->rta_DB_ID . "',\n";
        $insert_qry_string .= "GMTendTime = '" . gmdate($date_format, $aObj->endTic) . "',\n";
        $insert_qry_string .= "LocalendTime = '" . date($date_format, $aObj->endTic) . "',\n";
        $insert_qry_string .= "LocalstartTime = '" . date($date_format, $aObj->startTic) . "',\n";

        $insert_qry_string .= "timeField = " . $aObj->timeField . "\n";

        if (DEBUG)
            print_r($insert_qry_string);

        return $insert_qry_string;
    }

    function retrieveEnum($db, $tablename, $column) {
// Inputs:  database MYSQLi connection, tablename, columnName
// Outputs:  an associative array containing all enum options
        $selectQry = "SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE COLUMN_NAME = '$column' AND TABLE_NAME = '$tablename'";
        $dbRsp = $db->query($selectQry);
        if ($dbRsp->num_rows == 0)
            return false;
        $enumArray = $dbRsp->fetch_row();
        $enumString = substr($enumArray[0], 5); // strip off 'enum('
        $enumString = substr($enumString, 0, -1); // strip off ')'
        $enumString = str_replace("'", "", $enumString);  // strip out all '
        $enumArray = explode(",", $enumString);
        return $enumArray;
    }

// end of retreiveEnum

    function retrieveString($db, $tablename, $column) {
// Inputs:  database MYSQLi connection, tablename, columnName
// Outputs:  an associative array containing all char options
        $selectQry = "SELECT $column from $tablename";
        $dbRsp = $db->query($selectQry);
        $errmsg = 'NoData';

        if ($dbRsp->num_rows == 0) {
            $stringArray = $errmsg;
            return $errmsg;
        }

        for ($i = 0; $i < $dbRsp->num_rows; $i++) {
            while ($row = $dbRsp->fetch_row()) {
                $rows[$i] = $row;
                $stringArray[$i] = $rows[$i][0];
                $i++;
            }
        }

        return $stringArray;
    }

// end of retreiveChar
//gets the tagGroupName from the Tag_group Table for the given tagGroupID
    function getNameForID($db, $tagGroupID) {

        $selectQry = "SELECT tagGroupName from tag_group where tagGroupID = '" . $tagGroupID . "'";

        $dbres = $db->query($selectQry);
        $result = $dbres->fetch_row();

        return $result;
    }

//gets the tagGroupID from the Tag_group Table for the given tagGroupName
    function getIDForName($db, $tagGroupName) {

        $selectQry = "SELECT tagGroupID from tag_group where tagGroupName = '" . $tagGroupName . "'";

        $dbres = $db->query($selectQry);
        $result = $dbres->fetch_row();

        return $result;
    }

//gets the first tagGroupID from the Tag_group Table.
    function getFirstID($db) {

        $selectQry = "SELECT tagGroupID from tag_group";

        $dbres = $db->query($selectQry);
        $result = $dbres->fetch_row();

        return $result;
    }

    function getGroupIDForTagID($db, $tagID) {

        $selectQry = "SELECT tagGroupID from rta_tag_index_queued where tagID = '" . $tagID . "' ";

        $dbres = $db->query($selectQry);
        $result = $dbres->fetch_row();

        return $result[0];
    }

    function getFirstQueuedTagIDForGroupID($db, $groupID, $timeStamp) {
        $selectQry = "SELECT tagID from rta_tag_index_queued where tagGroupID = '" . $groupID . "' AND LocalendTime > '{$timeStamp}' ";
        $dbres = $db->query($selectQry);
        if ($dbres->num_rows == 0) {
            return false;
        } else
            return true;
        return true;
    }

    function getFirstTagIDForGroupID($db, $groupID) {

        $selectQry = "SELECT tagID from rta_tag_index_queued where tagGroupID = '" . $groupID . "' ORDER BY tagID DESC";

        $dbres = $db->query($selectQry);
        if ($dbres->num_rows == 0) {
            $selectQry = "SELECT tagID from rta_tag_index_completed where tagGroupID = '" . $groupID . "' ORDER BY tagID DESC";

            $dbres = $db->query($selectQry);
        }
        $result = $dbres->fetch_row();

        return $result[0];
    }

    function getrtaMasterIDForGroupID($db, $groupID) {
        $selectQry = "SELECT rtaMasterID from tag_group where tagGroupID = '" . $groupID . "' ";
        $dbres = $db->query($selectQry);
        if ($dbres->num_rows == 0) {
            return 0;
        } else
            $result = $dbres->fetch_row();
        return $result[0];
    }

    function getAllGroups($db) {

        $selectQry = "SELECT *  from tag_group ORDER BY tagGroupID DESC";

        $result = array();
        $dbres = $db->query($selectQry);
        while ($res = $dbres->fetch_assoc()) {
            $result[] = $res;
        }

        return $result;
    }

    function getShiftTime($db, $pageType) {
        $array = array();
        $selectQry = "SELECT a.pageLayoutDesc FROM rtd_page_layouts a, rtd_display_objects b WHERE a.rtdPageID = b.rtdPageID AND a.pageType='$pageType' AND b.shiftTimeID IS NOT NULL";
        $result = $db->query($selectQry);
        while ($res = $result->fetch_row()) {
            $array[] = $res[0];
        }
        if (isset($array))
            return $array;
    }

    function getColumnNamesExclude($db, $tablename) {
        $selectQry = "SHOW COLUMNS from $tablename";
        $exclude = array("tagID", "rtaMasterID", "status", "tagName", "tagGroupID", "LocalstartTime", "LocalendTime", "goodDataSecondsWeight", "massflowWeight", "validTag", "endTic", "startTic", "goodDataSecs", "avgMassFlowTph", "totalTons");

        $result = array();
        $dbres = $db->query($selectQry);
        while ($res = $dbres->fetch_assoc()) {
            if (!in_array($res['Field'], $exclude)) {
                $result[] = $res['Field'];
            }
        }

        return $result;
    }

//gets the item_name from the table_items table based on the tableID
//------------------------------------------------------------------
    function getItemName($db, $tableType) {
        $itemArray = array();

        //Get the current analysis column names
        $getAnalysisColsAr = getAnalysisColumnNames($db, "analysis_A1_A2_Blend");

        $selectQry = "SELECT tableID from rtd_table_objects where tableType = '$tableType'";
        $res = $db->query($selectQry);
        $tableID = $res->fetch_row();

        $query = "SELECT itemName,displayName,columnWidth from rtd_table_items where tableID = '$tableID[0]' ORDER BY position_order";
        $result = $db->query($query);

        while ($row = $result->fetch_row()) {
            if (in_array($row[0], $getAnalysisColsAr))
                $itemArray [] = $row;
        }
        return $itemArray;
    }

    function getDataFromCompletedTable($db, $tagGroupID, $value1, $value2) {
        $itemArray = array();
        $selectQry = "SELECT ";

        $selectQry .= "$tagGroupID, $value1, $value2 ";

        $selectQry .= "from rta_tag_index_completed";

        //echo $selectQry;
        $result = $db->query($selectQry);

        while ($row = $result->fetch_assoc()) {
            $itemArray [] = $row;
        }
        return $itemArray;
    }

    function getAnalysisColumnNames($db, $table) {
        $columnNamesAr = array();
        $selectQry = "SHOW COLUMNS FROM $table ";

        $result = $db->query($selectQry);

        while ($row = $result->fetch_assoc()) {
            $columnNamesAr [] = $row["Field"];
        }

        return $columnNamesAr;
    }

//Sum of fields in Completed Table

    function sumOfFields($db, $table, $tagGroupID, $array1) {
        $array2 = getDataFromCompletedTable($db, $tagGroupID, 'goodDataSecs', 'avgMassFlowTph');

        foreach ($array1 as $attr => $val) {
            $array[] = $val;
        }

        foreach ($array2 as $value) {

            $count = 1; //To keep track of array count

            $selectQry = "SELECT "; //Building the select Query
            foreach ($array as $val) {
                if ($value['goodDataSecs'] && $value['avgMassFlowTph']) {

                    if ($count == count($array)) {//For the last array value we don't want a , in the select stmt.
                        if ($val[0] == 'totalTons')
                            $selectQry .= "ROUND(SUM($val[0]), 2)as $val[0] "; //If totals tons then sum
                        else
                            $selectQry .= "ROUND((SUM($val[0] * goodDataSecs * avgMassFlowTph)) / SUM(goodDataSecs * avgMassFlowTph),2) as $val[0] ";
                    }
                    else {
                        if ($val[0] == 'totalTons')
                            $selectQry .= "ROUND(SUM($val[0]), 2) as $val[0],";
                        else
                            $selectQry .= "ROUND((SUM($val[0] * goodDataSecs * avgMassFlowTph)) / SUM(goodDataSecs * avgMassFlowTph),2) as $val[0],";
                    }
                }//end of if

                elseif (!$value['goodDataSecs'] && $value['avgMassFlowTph']) {

                    if ($count == count($array)) {//For the last array value we don't want a , in the select stmt.
                        if ($val[0] == 'totalTons')
                            $selectQry .= "ROUND(SUM($val[0]), 2)as $val[0] "; //If totals tons then sum
                        else
                            $selectQry .= "ROUND((SUM($val[0] * avgMassFlowTph)) / SUM(avgMassFlowTph),2) as $val[0] ";
                    }
                    else {
                        if ($val[0] == 'totalTons')
                            $selectQry .= "ROUND(SUM($val[0]), 2) as $val[0],";
                        else
                            $selectQry .= "ROUND((SUM($val[0] * avgMassFlowTph)) / SUM(avgMassFlowTph),2) as $val[0],";
                    }
                }//end of elseif
                else {
                    if ($count == count($array)) {//For the last array value we don't want a , in the select stmt.
                        $selectQry .= "ROUND((SUM($val[0] * 1)) / SUM(1),2) as $val[0] ";
                    } else {
                        $selectQry .= "ROUND((SUM($val[0] * 1)) / SUM(1),2) as $val[0],";
                    }
                }

                $count++;
            }

            $selectQry .= "from rta_tag_index_completed where tagGroupID = '$tagGroupID'";
        } //end of foreach($value)
        //echo $selectQry;
        $result = $db->query($selectQry);

        while ($row = $result->fetch_assoc()) {
            $resArray = $row;
        }

        return $resArray;
    }

//Gets all tags and thier selected columns
    function getAllTagsByColumns($db, $array, $tagGroupID = NULL, $start_time = NULL, $end_time = NULL) {
        $tagArray = array();
        $count = 1; //To keep count of the $array

        $selectQry = "SELECT tagName, "; //Start Building the Select Query

        foreach ($array as $value) {
            if ($count == count($array))
                $selectQry .= "$value[0] ";
            else
                $selectQry .= "$value[0],";
            $count ++;
        }
        $selectQry .= "FROM rta_tag_index_completed";

        if (isset($tagGroupID) && ($start_time == ''))
            $selectQry .= " WHERE tagGroupID = '$tagGroupID'";

        if (!isset($tagGroupID) && (isset($start_time)) && (isset($end_time)))
            $selectQry .= " WHERE LocalstartTime >= '$start_time' AND LocalendTime <= '$end_time'";

        if (isset($tagGroupID) && (isset($start_time)) && (isset($end_time)))
            $selectQry .= " WHERE tagGroupID = '$tagGroupID' AND LocalstartTime >= '$start_time' AND LocalendTime <= '$end_time'";

        //echo $selectQry;
        $result = $db->query($selectQry);

        while ($row = $result->fetch_assoc()) {
            array_push($tagArray, $row);
        }
        if (!isset($tagArray))
            return false;
        return $tagArray;
    }

// Copied from pageTools.php
//----------------------------------------------------------------------------------------
// getPassedVar - returns a var passed as get or post, with protection against unset vars
//----------------------------------------------------------------------------------------
//! Returns a var that might have been passed either via _GET (from a URI param) or
//! _POST (from a form).  _GET is checked first, so URI params can be used to override
//! form vars.
//!
//! \returns The passed var, or NULL if neither _GET nor _POST var of the specified
//! name exists.
//!
    function getPassedVar($varName) {
        $passed = NULL;
        if (isset($_GET[$varName]))
            $passed = $_GET[$varName];
        else {
            if (isset($_POST[$varName]))
                $passed = $_POST[$varName];
        }

        return( $passed );
    }

// Copied from pageTools.php
//----------------------------------------------------------------------------------------
// obj_clone - cross-version object cloner
//----------------------------------------------------------------------------------------
//! Object clone function to use to make object duplication work on both PHP4 and PHP5.
    function obj_clone($object) {
        if (version_compare(phpversion(), '5.0') < 0)
            return $object;
        else
            return @clone($object);
    }

    function getTableColums($tableName) {

        $db = Yii::$app->db;
        $colQuery = "show columns from  {$tableName}"; // Query to featch element from analysis_A1_A2_Blend table
        $colCommand = $db->createCommand($colQuery);
        $columns = $colCommand->queryAll();
        return $columns;
    }

    function getSetting($table, $key) {

        
           $query = "select * from $table where varName = '$key' ";
        
        $result = Yii::app()->db->createCommand($query)->queryRow();

        return $result['varValue'];

    }

    static function getRmSetting($key) {

       
        $query = "select * from rm_settings where varKey = '$key' ";
        
        $result = Yii::app()->db->createCommand($query)->queryRow();

        return $result['varValue'];
    }

   /*static function getSingleEntry($outCol,$table,$varKey,$colVal) {

        $rows = (new \yii\db\Query())
                ->select(["$outCol"])
                ->from("$table")
                ->where(["$varKey" => "$colVal"])
                ->one();

        return $rows["$outCol"];
    }*/
    
    function setSettings($varKey, $value) {
        $var_key_to_name_Array = array("MANUAL"=>"Manual", "Automatic"=>"AUTOMODE",
                            "AUTO_TEST"=>"AUTO_TEST","starvation"=>"STARVATION",
                            'Simulation' => 'SIMULATE',
                            "CONST_FEEDERS"=>"constFeeders",
                            "CRON_RUN_TIME"=>"CRON_RUN_TIME", 
                            "CRON_CHANGE_FLAG" => "CRON_CHANGE_FLAG",
                            "sensitivity"=>"sensitivity",'auto_test' => 'AUTO_TEST');

        $updateQuery = "update rm_settings set varValue = '" . $value . "' where varKey = '" . $varKey . "'";

        //$tempAr = array("varName" => $setAr['varName'], "varKey" => $setAr['varKey'], 
        //"varValue" => $setAr['varValue'], "CurFeeder" => $setAr['CurFeeder'], 
        //"ConstFeeder" => $setAr['ConstFeeder']);
        $tempAr = array("varName" => $var_key_to_name_Array[$varKey], 
                        "varKey" => $varKey, "varValue" => $value);

        $this->updateConfigLog($tempAr, "rm_settings", "varKey",$varKey);
        Yii::$app->db->createCommand($updateQuery)->execute();

        $prductModel =  \app\models\ProductProfile::findOne(['product_id' => 1]);
        
        $prductModel->updated_on = date('Y-m-d H:i:s');
        $prductModel->save();
        return true;
    }

    function serializeTable($talbeName) {

        $setPointsModel = SetPoints::findAll(['product_id' => $productId]);
    }

    function updateConfigLog($valAr, $inTable, $inkey, $inVal) {
        $connection = Yii::$app->db;
        $dbArray = array();
        $outPutAr = "";
        $curDateTime = date('Y-m-d H:i:s');
        //print_r($valAr);exit();
        $selQuery = "SELECT * FROM $inTable WHERE $inkey = '{$inVal}' LIMIT 1";
        $command = $connection->createCommand($selQuery);
        $result = $command->queryAll();
        //echo $SelQuery;echo "<br/>";
       
        if (($result)) {
            foreach ($result as $row) {
                $dbArray = $row;
            }//foreach
        }//if
        $resultDiffAr = array_diff($valAr, $dbArray);
        
        //echo "valAr"; print_r($valAr);
        //echo "dbArray"; print_r($dbArray);
        //echo "resultDiffAr"; print_r($resultDiffAr);
        //echo "<br/>";
        foreach ($resultDiffAr as $id => $val) {

            if ($inTable == "rm_settings") {
                $dbVal = $dbArray["varValue"];
                if (isset($valAr["CurFeeder"])) {
                    $id = $valAr["CurFeeder"];

                    if ($dbVal)
                        $outPutAr = " $id is being controlled by Rawmix now";
                    else
                        $outPutAr = " $id is a constant Feeder now";

                    $updateQuery = "UPDATE rm_fdr_delay_counter set fdr_counter=1, fdr_updated='{$curDateTime}' " .
                            "WHERE fdr_name = (SELECT src_type FROM rm_source where src_name='{$id}') ";
                    //echo $updateQuery ;
                    $command = $connection->createCommand($updateQuery);
                    $command->execute();
                }else {
                    $id = $valAr["varKey"];
                    $inpVal = $valAr["varValue"];
                    $outPutAr = " $id was changed from $dbVal to $inpVal";
                }

                $insQ = "INSERT into rm_config_log values (NULL,'{$inTable}','{$id}','{$inpVal}','{$outPutAr}','{$curDateTime}')";
                //echo $insQ . "<br/>";
                $command = $connection->createCommand($insQ);
                $command->execute();
                break;
            } else if (isset($dbArray[$id])) { 

                $dbVal = round($dbArray[$id], 3);
                $inpVal = round($valAr[$id], 3);
                
                if ($dbVal != $inpVal) {

                    $outPutAr = " For $inVal $id was changed from $dbVal to $inpVal ";
                    $insQ = "INSERT into rm_config_log values (NULL,'{$inTable}','{$id}','{$inpVal}','{$outPutAr}',now())";
                    //echo $insQ . "<br/>";
                    $command = $connection->createCommand($insQ);
                    $command->execute();
                }
            }
        }
    }

    function updateSetPointsLog($beforState, $afterState) {

        $resultDiffAr = array_diff($beforState, $afterState);

        if (!empty($resultDiffAr)) {


            $setPointsLog = new \app\models\SetPointsLog();
            $setPointsLog->sp_name = $beforState['sp_name'];
            $setPointsLog->sp_value = $afterState['sp_value_num'];
            $setPointsLog->sp_updated = date("Y-m-d H:i:s");
            $setPointsLog->sp_description = $beforState['sp_name'].' changed from '.$beforState['sp_value_num'].' to '.$afterState['sp_value_num'];
                
            
            $tz = date_default_timezone_get();
            $time = date("Y-m-d H:i:s");
            $result = $setPointsLog->save();
            return $result;
        }

        return false;
    }
    
    
    function updateSourceLog($beforState, $afterState) {

        $resultDiffAr = array_diff($beforState, $afterState);

        if (!empty($resultDiffAr)) {


            $sourceLog = new \app\models\SourceLog();
            $sourceLog->src_name = $beforState['src_name'];
            $sourceLog->src_min_feedrate = $beforState['src_min_feedrate'];
            $sourceLog->src_max_feedrate = $beforState['src_min_feedrate'];
            $sourceLog->updated  =date("Y-m-d H:i:s");
            $result = $sourceLog->save();
            
            return $result;
        }

        return false;
    }
    
    
    
    function updateElementLog($beforState, $afterState) {

        $resultDiffAr = array_diff($beforState, $afterState);

        $sourceModel = \app\models\Source::findOne(['src_id' => $beforState['source_id']]);
        if (!empty($resultDiffAr)) {


            $eleLog = new \app\models\ElementLog();
            $eleLog->src_name = $sourceModel->src_name;
            $eleLog->src_id   = $sourceModel->src_id;  
            $eleLog->ele_name = $beforState['element_name'];
            $eleLog->ele_value = $beforState['element_value'];
            $eleLog->updated = date("Y-m-d H:i:s");
            $result = $eleLog->save();
            $error  = $eleLog->errors;
            return $result;
        }

        return false;
    }

}
