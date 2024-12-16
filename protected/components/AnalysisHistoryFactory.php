<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisHistoryFactory
 *
 * @author webtatva
 */
class AnalysisHistoryFactory
{
    public $table;
    public $currentDateTime;
    public $analysisRecords;
    public $analysisResult;
    public $timestamp;
    public $analyzerTime;
    public $connection;
    public $blocks;
    public $analysisResults;
    public $computedResults;
    public $rtaMathList;
    public $last100Tons;
    public $analysisAvg;
    private $debugFlag;
    private $analysisTable;
    private $samplingTime; //inseconds
    private $fallbackTime; //in seconds
    public $avgDataVector;

    //Constructor

    public function __construct($analysisTimeArray, $analysisTable)
    {

Yii::app()->db->setActive(false);

Yii::app()->db->setActive(true);

        if (isset($_REQUEST['dev_debug'])) {
            $this->debugFlag = true;
        }else{
            
            $this->debugFlag = false;
        }

        $this->analysisTable = $analysisTable;
        $this->analyzerTime = $analysisTimeArray;

        $samplingTimeQuery = "select * from rm_settings where varName = 'LAB_HISTORY_SAMPLING_TIME'";
        $samplingCommnad = Yii::app()->db->createCommand($samplingTimeQuery);
        $samplingResult = $samplingCommnad->queryRow();
        // $tmp1 = explode(':', $samplingResult['varValue']);
        $this->samplingTime = ($samplingResult['varValue'] * 60);

        $fallBackTimeQuery = "select * from rm_settings where varName = 'LAB_HISTORY_FALLBACK_TIME'";
        $fallBackCommnad = Yii::app()->db->createCommand($fallBackTimeQuery);
        $fallBackResult = $fallBackCommnad->queryRow();
        // $tmp2 = explode(':', $fallBackResult['varValue']);
        $this->fallbackTime = ($fallBackResult['varValue'] * 60);
        
        // $this->getRecords();
    }

    public function getRecords()
    {
        $subQueryArray = array();
        $subQueryList = array();

        if (count($this->analyzerTime) > 0) {
            foreach ($this->analyzerTime as $key =>  $timeStamp) {
                $tableName = $this->analysisTable;
                $baseTime = $timeStamp;
                $endTimeT = strtotime($baseTime) - $this->fallbackTime;
                $endTime = date("Y-m-d H:i:s", $endTimeT);
                
                $samplingTime = $this->getSamplingDuration($key, $this->analyzerTime);
                $startTime = date("Y-m-d H:i:s", $endTimeT - ($samplingTime * 3600));

                $offsetMinTph = Yii::app()->db->createCommand('select * from rm_settings where varKey="AUTO_OFFSET_HOURLY_MIN_TPH"')->queryRow();
                $minTPH = isset($offsetMinTph['varValue']) ? $offsetMinTph['varValue'] : 425;
                $sql = " ( select * from " . $tableName . " where LocalendTime >= '" . $startTime . "' AND LocalendTime <=  '" . $endTime . "' and TPH >='" . $minTPH . "'  ORDER BY  LocalendTime DESC ) ";

//                	Logger::calibLogger(Logger::INFO,'Analyzer query ', $sql );


                $subQueryArray[$baseTime] = $sql;
            }

            foreach ($subQueryArray as $key => $subQuery) {
                if ($this->debugFlag) {
                    echo "<p>Query: $subQuery</p>";
                }


                $command = Yii::app()->db->createCommand($subQuery);
                $recordList = $command->queryAll();

                $this->calculateSimpleAvg($recordList, $key);
            }


            foreach ($this->analysisAvg as $key => $row) {
                $count = 0;
                foreach ($row as $col => $colvalue) {
                    $this->avgDataVector[$col][] = $colvalue;
                }
            }
            // usort($finalList, 'date_compare');
            // $this->analysisResults = $recordList;

            $noOfRecords = count($this->analyzerTime);

            //Logger::calibLogger(Logger::INFO,'Total Analyzer Records found '.$noOfRecords, $this->analyzerTime);

            return $noOfRecords;
        }
    }

    //End of compute data

    public function getElementRecords($eleSettings)
    {
        $subQueryArray = array();
        $subQueryList = array();

        if (count($this->analyzerTime) > 0) {
            foreach ($this->analyzerTime as$key => $timeStamp) {
                $tableName = $this->analysisTable;
                $baseTime = $timeStamp;
                $endTimeT = strtotime($baseTime) - $this->fallbackTime;
                $endTime = date("Y-m-d H:i:s", $endTimeT);
                
                
                
                $samplingTime = $this->getSamplingDuration($key, $this->analyzerTime);
                $startTime = date("Y-m-d H:i:s", $endTimeT - ($samplingTime * 3600));
//                $startTime = date("Y-m-d H:i:s", $endTimeT - ($this->samplingTime));
                
                
                $elename = $eleSettings['eleName'];

                $offsetMinTph = Yii::app()->db->createCommand('select * from rm_settings where varKey="AUTO_OFFSET_HOURLY_MIN_TPH"')->queryRow();
                $minTPH = isset($offsetMinTph['varValue']) ? $offsetMinTph['varValue'] : 425;
                
                $min = $eleSettings['min']-$eleSettings['valid_datapoint_range'];
                $max = $eleSettings['max']+$eleSettings['valid_datapoint_range'];
                $filterSubQuery = " $elename > ".$min." AND  $elename < ".$max;
                $sql = " ( select $elename , LocalendTime, totalTons , TPH from " . $tableName . " where $filterSubQuery and LocalendTime >= '" . $startTime . "' AND LocalendTime <=  '" . $endTime . "' and TPH >='" . $minTPH . "'  ORDER BY  LocalendTime DESC ) ";

//                	Logger::calibLogger(Logger::INFO,'Analyzer query ', $sql );


                $subQueryArray[$baseTime] = $sql;
            }

            $recordList = array();
            foreach ($subQueryArray as $key => $subQuery) {
                if ($this->debugFlag) {
                    echo "<p>Query: $subQuery</p>";
                }


                $command = Yii::app()->db->createCommand($subQuery);
                $recordList[$key] = $command->queryAll();
            }

            return $recordList ;
        }
    }

    
    
    public function calculateEleAvg($data, $ele)
    {
        $rtaMathArray = array();

        
        foreach ($data as $timestamp => $ar) {
            $tempAr = LabUtility::getColumn($ar, $ele);
            $arraySum   = array_sum($tempAr);
            $arrayCount = count($tempAr);
            
            $avg = $arrayCount === 0 ? 0:  $arraySum / $arrayCount;
            $rtaMathArray[$timestamp]  = round($avg, 3);
        }
          
        return $rtaMathArray;
    }

    public function calculateAvg($data, $key)
    {
        unset($this->rtaMathList);
        $this->computedResults = array(); //To reset old values
        //To clear previous value
        unset($rtaMathArray);
        $id = 0;
        foreach ($data as $record) {

            // $dataRecord = $dbResponse->fetch_assoc();
            // Check to see if arry of rtaMath objects has been created (1st pass only)
            if (!isset($rtaMathArray)) {
                $rtaMathArray = array();
                foreach ($record as $varName => $value) {
                    $rtaMathArray[$varName] = new rtaMath($varName);
                    // Set general toggles for *all* variables
                    $rtaMathArray[$varName]->goodDataSecondsWeight = 1; //$this->goodDataSecondsWeight;//1
                    $rtaMathArray[$varName]->massflowWeight = 1; // $this->massflowWeight;//1
                    // Some variables should not be weighted so we check for those
                    $rtaMathArray[$varName]->toggleCheck(); //comment
                    $rtaMathArray[$varName]->conversionFactor = 0.000277778; // $rtaCfgObj->timeConversionMassflowWeight; //0.000277778;
                }
            }
            // If we reach here all the rtaMath classes have been created and the proper toggles for calculation have been set
            foreach ($record as $varName => $value) {
                $rtaMathArray[$varName]->dataVector[$id] = $value;
            }

            $id++;
        }
        foreach ($rtaMathArray as $varName => $rtaMathObj) {
            $rtaMathObj->massflowVector = $rtaMathArray['avgMassFlowTph']->dataVector;
            $rtaMathObj->goodDataSecondsVector = $rtaMathArray['goodDataSecs']->dataVector;
        }

        $this->rtaMathList[] = $rtaMathArray;


        foreach ($this->rtaMathList as $rtaMathArray) {
            $endTimeValue = $rtaMathArray['LocalendTime'];
            $sResult = rtaMath::getAnalysisData($rtaMathArray);

            $date = array_pop($endTimeValue->dataVector);
            $dresult = isset($date) ? $date : null;
            $sResult['LocalendTime'] = $dresult;


            $this->analysisAvg[$key] = $sResult;
        }
    }
    
    
    
    
    /**
     *
     * @param array $data
     * @param string $key
     */

    public function calculateSimpleAvg($data, $key)
    {
        $rtaMathArray = array();
        $this->computedResults = array(); //To reset old values
        //
        $id = 0;

        $query = 'select * from ac_settings ';
        $results = Yii::app()->db->createCommand($query)->query();
        $acSettings = array();
        
   Yii::app()->db->setActive(false);

Yii::app()->db->setActive(true);
		 
        
        $query = "select * from rm_settings where varKey = 'ANALYZER_FILTER_BAD_RECORDS' ";
        $result = Yii::app()->db->createCommand($query,PDO::MYSQL_ATTR_USE_BUFFERED_QUERY)->queryRow();
        if ($result && $result['varValue']) {
            $setting_filter_bad_records = (int) $result['varValue'];
        }
        
        

        foreach ($results as $item) {
            $acSettings[$item['element_name']] = $item;
        }


        foreach ($data as $row) {
            if ($setting_filter_bad_records) {
                $record = DashHelper::validateAndSetAnalyzerRecordUsingRange($row, $acSettings);
            } else {
                $record = $row;
            }

            if ($record['totalTons'] == 0) {
                continue;
            }
            // If we reach here all the rtaMath classes have been created and the proper toggles for calculation have been set
            foreach ($record as $varName => $value) {
                $rtaMathArray[$varName]->dataVector[$id] = $value;
            }

            $id++;
        }


        $eleAvg = array();
        foreach ($rtaMathArray as $index => $value) {
            $count = count($value->dataVector);
            $sum = array_sum($value->dataVector);
            $avg = round(($sum / $count), 3);

            switch ($index) {

                case "dataID":
                    break;
                case "rtaMasterID": break;
                case "startTic": break;
                case "endTic": break;
                case "LocalstartTime": $eleAvg[$index] = $rtaMathArray['LocalendTime']->dataVector[0];
                    break;
                case "LocalendTime": $eleAvg[$index] = array_pop($rtaMathArray['LocalendTime']->dataVector);
                    break;
                case "GMTendTime": break;
                case "goodDataSecs": break;
                case "avgMassFlowTph": break;
                case "totalTons": $eleAvg[$index] = $sum;
                    break;
                default: $eleAvg[$index] = $avg;
                    break;
            }
        }//End of foreach


        $this->analysisAvg[$key] = $eleAvg;
    }

    public function getSamplingDuration($timeIndex, $timeArray = array())
    {
        $isNextTimeIndex = isset($timeArray[$timeIndex+ 1]) ? $timeIndex +1 : false;
        
        
        if ($isNextTimeIndex) {
            $timeDiff =  strtotime($timeArray[$timeIndex]) - strtotime($timeArray[$timeIndex+1]);
            
            $defultHour = $timeDiff > (4 * 3600) ? 4  : $timeDiff / 3600;
            
            
            
            $samplingTime  =  $defultHour;
        } else {
            $samplingTime  =  $this->samplingTime / 3600;
        }
        
        return $samplingTime;
    }
    public function clean($string)
    {
        //$string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-\.]/', '', $string); // Removes special chars.
    }
}
