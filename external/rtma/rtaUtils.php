<?php
//-------------------------------------------------------------------------------------------------
// validateInputs() - checks user inputs to prevent attacks
//-------------------------------------------------------------------------------------------------
// INPUTS:  $_GET and $_POST inputs are checked for type, length, size, and illegal characters
function validateInputs()
{
    $errStr = "";
    $inputsArray = array_merge($_GET,$_POST);
    $datesArray = array ('start_time','end_time','LocalstartTime','LocalendTime');
    $integerArray = array ('rtdPageID','rtdObjectID','graphID','plot_table_id','records_requested','tagID','rta_DB_ID','rtaMasterID','numRecords','timepan');
    $pageTypeArray = array ('real-time','timeRange','tagGroupView','tagView','tagCreate','tagIndex','tagTotal','tagGroup','tagGroupIndex','tagGroupTotal','home','login','logout','settings', 'userSettings','tonsRange','tagManage','tagMain','setpoints','setcolumns','OPPage');
    $stringsArray = array ('tagName','tagGroupName','customField1','customField2','actionType','perform','screenActionForm','colorScheme','screenWidth','screenHeight');
    $booleanArray = array ('massflowWeight','goodDataSecondsWeight');
    $floatsArray = array ('dispObjectWidth');
    foreach ($inputsArray as $key=>$value)
    {
        // Check float inputs
        if (in_array($key,$floatsArray))
        {
            if (!is_numeric($value))
                $errStr .= "$key must be a number. \u000D\u000D";
        }

        // Check string inputs
        if (in_array($key,$stringsArray))
        {
            // By default every $_GET and $_POST comes as a string
            // Check for illegal characters (could do ctype_alnum() but we want to allow '_')
            $iChars = str_split("!@#$%^&*()+=-[]\\;\',./{}|\":<>?");
            $illegalCharCount = 0;
            $splitArray = str_split($value);
            for ($i=0; $i < count($splitArray); $i++)
            {
                if (in_array($splitArray[$i],$iChars))
                    $illegalCharCount++;
            }
            if ($illegalCharCount > 0)
                $errStr .= "$key has illegal characters in it. \u000D\u000D";

            // Check for string length
            if (strlen($value) > 40)
                $errStr .= "$key has too many characters in it. \u000D\u000D";
 
            if ($value != null && strlen($value) < 2)
                $errStr .= "$key must have at least 2 characters. \u000D\u000D"; 
        }

        // Check dates
        if (in_array($key,$datesArray))
        {
            if (empty($value))
                continue;    // if it's empty we let it through and do checking later

            // We know it's a date
            // Verify that it's a valid date
            if (!strtotime($value))
                $errStr .= "$key must be a real date. \u000D\u000D";
            // Verify that it is in the acceptable MYSQL format which is YYYY-MM-DD hh:mm:ss
            if (!preg_match("/^\d{4}-\d{2}-\d{2} [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/", $value))
                $errStr .= "$key must be in the format: \u000D YYYY-MM-DD hh:mm:ss \u000D\u000D";
            // Verify that there wasn't an error when saving the date in MySQL which will create a date of 0000-00-00 00:00:00
            if ($value == '0000-00-00 00:00:00')
                $errStr .= "There was an error with $key when it was saved in the database, and must be fixed before this page can be used. \u000D\u000D";
        }

        if ($key == 'timespan')
        {
            // Timespan must be in a string with format:  HH:mm:ss
            if (!preg_match("/^[0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/",$value))
                $errStr .= "$key must be in the format: \u000D hh:mm:ss \u000D\u000D";
        }

        // Check Integers (usually IDs)
        if (in_array($key,$integerArray) && $value != NULL)
        {
            // We know that it is supposed to be an integer
            if (!is_numeric($value))   // This checks to make sure that every character is a digit
                $errStr .= "$key must have only numbers in it. \u000D\u000D";
            // Verify that it is greater than zero
            if ($value <= 0)
                $errStr .= "$key must be greater than zero. \u000D\u000D";
        }

        // Check Boolean values
        if (in_array($key,$booleanArray))
        {
           // Boolean values can either be true, false, or null
           if ($value == 1 || $value == 0 || $value == NULL)
               continue;
           else
               $errStr .= "$key must be either true or false. \u000D\u000D";
        }

        // Check pageTypes
        if ($key == 'pageType')
        {
            if (!in_array($value,$pageTypeArray))
                $errStr .= "Invalid $key\u000D\u000D";
        }
    }
    if ( !empty($errStr))
    {
        // The carriage returns are using Unicode which work in Chrome and IE but not in Firefox
        echo "<html><head><body>";
        echo "<script type='text/javascript'>";
        echo "alert('$errStr')";
        echo "</script>";
        return 1;
    }
    return true;
}  // end of validateInputs()




//-------------------------------------------------------------------------------------------------
// rtaUtils.php - various tools and classes for use with RTA and RTD systems
//-------------------------------------------------------------------------------------------------

class rtaMath{
    // Descriptive
    var $varName;                                //! display name of data
    var $conversionFactor;                       //! unit converstion for massflow time unit 
    var $roundDecimal;                           //! number of decimals to round
    
    // Toggles
    var $dbWrite;                                //! toggle to determine if variable should be written to databse (for data already in RTA configtable)
    var $massflowWeight;                         //! toggle to determine if massflow should be included in any weighted average
    var $goodDataSecondsWeight;                  //! toggle to determine if GDS should be included in any weighted average
    var $summation;                              //! toggle to determine if summation operation should be done
    var $average;                                //! toggle to determine if average operation should be done
    var $stdDev;                                 //! toggle to dtermine if standard deviation calculation should be done

    // Data Vectors
    var $dataVector;                              //! contains a vector array of data for calculation
    var $massflowVector;                          //! contains a vector array of mass flow rates
    var $goodDataSecondsVector;                   //! contains a vector array of good data seconds

    // Result Containers
    var $results;                                 //! container for results:  'average', 'stdDev', 'sum'

    // constructor
    function rtaMath($varName)
    {
        $this->varName = $varName;
        $this->roundDecimal = 2;
//        $this->toggleCheck();  // Check to see if any time of calculation should be done (strings, IDs, etc. should not calculated in any way)
    }  // end of rtaMath class constructor


    //---------------------------------------------------------------------------------------------
    // standardDev() - calculates the standard deviation
    //---------------------------------------------------------------------------------------------
    //INPUTS:  if the standard deviation is a population (false) or sample(true) std. devaition calculation
    function standard_deviation($bSample = false)
    {
        if (count($this->dataVector) == 0)
            return false;

        $gdsToggle = $this->goodDataSecondsWeight;
        $massToggle = $this->massflowWeight;

        $massflow = $this->massflowVector;
        $gds = $this->goodDataSecondsVector;
        $data = $this->dataVector;

        $fMean = $this->computeAverage(); // returns either a straight average or weighted average depending on toggles 
        $fVariance = 0.0;
        $WT = 0.0;
        for($i=0; $i<count($data); $i++)
        {
            if ($gdsToggle && $massToggle)
            {
                $fVariance += $massflow[$i]*$gds[$i]*pow($data[$i] - $fMean, 2);
                $WT += $massflow[$i]*$gds[$i];
            }
            elseif ($gdsToggle && !$massToggle)
            {
                $fVariance += $gds[$i]*pow($data[$i] - $fMean, 2);
                $WT += $gds[$i];
            }
            elseif (!$gdsToggle && $massToggle)
            {
                $fVariance += $massflow[$i]*$gds[$i]*pow($data[$i] - $fMean, 2);
                $WT += $massflow[$i]*$gds[$i];
            }
            else
            {
                $fVariance += pow($data[$i] - $fMean, 2);
                $WT += 1;
            }
        }
        if ($WT != 0)
        {
            $fVariance /= $WT;
//            $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
            return (float) sqrt($fVariance);
        }
        else
            return 0;
    }

    //---------------------------------------------------------------------------------------------
    // computeAverage() - decides which type of average to do (requires toggles be checked prior)
    //---------------------------------------------------------------------------------------------
    function computeAverage()
    {
        $gdsToggle = $this->goodDataSecondsWeight;
        $massToggle = $this->massflowWeight;
        if ($gdsToggle && $massToggle)
        {
            return round($this->gds_massflowWeightedAverage($this->dataVector, $this->massflowVector, $this->goodDataSecondsVector),$this->roundDecimal);
        }
        elseif ($gdsToggle && !$massToggle)
        {
            return round($this->gdsWeightedAverage($this->dataVector, $this->goodDataSecondsVector),$this->roundDecimal);
        }
        elseif (!$gdsToggle && $massToggle)
        {
            return round($this->massflowWeightedAverage($this->dataVector, $this->massflowVector),$this->roundDecimal);
        }
        else
            return round($this->straightAverage($this->dataVector),$this->roundDecimal);

    }  // end of computeAverage()


    //---------------------------------------------------------------------------------------------
    // sumData() 
    //---------------------------------------------------------------------------------------------
    function sumData($dataVector)
    {
        return array_sum($dataVector);
    }  // end of sumData()


    //---------------------------------------------------------------------------------------------
    // straightAverage()
    //---------------------------------------------------------------------------------------------
    function straightAverage($dataVector)
    {
        if (count($dataVector)!= 0)
            return array_sum($dataVector)/count($dataVector);
        else
            return false;
    }  // end of straightAverage();


    //---------------------------------------------------------------------------------------------
    // gdsWeightedAverage() - performs a good data second weighted average
    //---------------------------------------------------------------------------------------------
    function gdsWeightedAverage($dataVector, $gdsArray)
    {
        $sum = 0;
        $gdsTotal = array_sum($gdsArray);
        if ($gdsTotal == 0)
            return 0;
        for( $i=0; $i<count($dataVector); $i++)
        {
            $sum += $dataVector[$i] * $gdsArray[$i];
        }
        return $sum/$gdsTotal;
    }  // end of gdsWeightedAverage()


    //---------------------------------------------------------------------------------------------
    // massflowWeightedAverage() - performs a massflow weighted average 
    //---------------------------------------------------------------------------------------------
    // NOTE: Almost all variables will use gds_massflowWeightedAverage instead of this function to incorporate how much good data was in the analysis
    function massflowWeightedAverage($dataVector, $massflowVector)
    {
        $sum = 0;
        $massflowTotal = array_sum($massflowVector);
        if ($massflowTotal == 0)
            return 0;
        for( $i=0; $i<count($dataVector); $i++)
        {
            $sum += $dataVector[$i] * $massflowVector[$i];
        }
        return $sum/$massflowTotal;
    }  // end of massflowWeightedAverage()


    //---------------------------------------------------------------------------------------------
    // gds_massflowWeightedAverage() - performs a good data second and massflow weighted average
    //---------------------------------------------------------------------------------------------
    function gds_massflowWeightedAverage($dataVector, $massflowVector, $gdsVector)
    {
        $sum = 0;
        $weightedSum = 0;
        for( $i=0; $i<count($dataVector); $i++)
        {
 
            $sum += $dataVector[$i] * $gdsVector[$i] * $massflowVector[$i];
            $weightedSum += $gdsVector[$i] * $massflowVector[$i];
        }
        if ($weightedSum == 0)
            return 0;

        return $sum/$weightedSum;
    } // end of gds_massflowWeightedAverage()

/*
    //---------------------------------------------------------------------------------------------
    // getTotalTons()
    //---------------------------------------------------------------------------------------------
    function getTotalTons($dataVector)
    {
//        $rtaConf = new ConfigFile();
//        $rtaConf->load (RTA_CONF); // readable
//        $rtaConf->setPath( "/" . "rtad" );
//        $rtad_write_time = $rtaConf->readEntry ("write_time");  // C++ rtad write time interval (default is 5 seconds) 
//        $sum = array_sum($massflowVector);
//        return $sum*$conversionFactor*$rtad_write_time;
    }  // end of getTotalTons()
*/
    
    //---------------------------------------------------------------------------------------------
    // toggleCheck() - checks to see what type of toggles should be applied for various calculations
    //---------------------------------------------------------------------------------------------
    function toggleCheck($avgToggle = 1, $stdevToggle = 1, $sumToggle = 1, $tonsAvgToggle = 0)
    {
        // TODO:  Need a better way to set toggles for what type of averaging to do.  Perferably a database table as some may not have massflow

        // Check to see if any of the variable should allow any type of calculation at all
        $noCalcArray = array ('dataID','timeField','rtaMasterID','analysisID','spectrumID','rawcoefID','rta_DB_ID','detectorID','span','bulkAvgPeriod','incrementInterval','detectorID','newSpan','newBulkAvgPeriod','newIncrementInterval','autoAdjustTimeParams', 'subintervalMode', 'subintervalSecs','weighted','detectorCount','GMTendTime', 'LocalendTime','LocalstartTime', 'timeField', 'endTic', 'startTic');
        if (in_array($this->varName, $noCalcArray))
        {
            $this->dbWrite = 0;
            return false;
        }

        // Check to see if the variable should not be massflow weighted
        $noMassflowWeightArray = array ( 'goodDataSecs', 'totalCounts', 'countsAboveThresh', 'chiSquare', 'hpeakcounts', 'hpeakfwhm', 'totalTons', 'avgMassFlowTph');
        if (in_array($this->varName, $noMassflowWeightArray))
        {
            $this->massflowWeight = 0;
        }

        // Check to see if any variables should not be good data second weighted
        $noGoodDataSecondsWeightArray = array ( 'goodDataSecs', 'chiSquare', 'hpeakfwhm', 'totalTons');
        if (in_array($this->varName, $noGoodDataSecondsWeightArray))
        {
            $this->goodDataSecondsWeight = 0;
        }

        // After all checks we're assuming we want to average everything else (can be overwritten later)
        $this->average = $avgToggle;
        $this->stdDev = $stdevToggle;
        $this->summation = $sumToggle;

        if ($this->varName == 'totalTons')
        {
            $this->average = $tonsAvgToggle;
            $this->summation = $sumToggle;
            $this->stdDev = 0;
        }

        return true; 

    } // end of toggleCheck()

    //---------------------------------------------------------------------------------------------
    // tabulate() - calculates averages of data and summations based on toggles
    //---------------------------------------------------------------------------------------------
    function tabulate()
    {
        if ($this->dbWrite === 0)
            return false;

        if (count($this->dataVector) == 0)
            return false;

        if ($this->average)
        {
            $this->results['average'] = $this->computeAverage();
        }
        if ($this->summation)
        {
            $this->results['sum'] = round($this->sumData($this->dataVector),$this->roundDecimal);
        }
        if ($this->stdDev)
        {
            $stdDev = $this->standard_deviation();
            if ($stdDev === false)
                return false;
            $this->results['stdDev'] = round($stdDev, $this->roundDecimal);       
        }

        // If the data is a string or a non-calculated value (like a toggle or something) everything is the same so we return one since they should be all the same
        if (empty($this->average) && empty($this->summation))
        {
            $this->results['nonCalculated'] = $this->dataVector[count($this->dataVector)-1];  // This is a better way to return non-calculated values
            return $this->dataVector[count($this->dataVector)-1];   // Here we are returning the last value (for dates to get last value for other types of data it won't matter which)
        }
        return true;
    } // end of tabulate()



} // end of rtaMath class definition
?>
