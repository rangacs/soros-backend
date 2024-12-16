<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisDataObject
 *
 * @author veda
 */
class AnalysisDataObject {
    
    
    public $startTime;
    public $endTime;
    public $currentTime;


    public function __construct($startTime,$endTime){
        
        $this->startTime = date('Y-m-d H:i:s',strtotime($startTime));
        $this->endTime   = date('Y-m-d H:i:s',strtotime($endTime));
        
        $this->currentTime = date('Y-m-d H:i:s');
    }
    
    
    function getRecords(){
        
        
        $startTime =  $this->getStartTime();
        $endTime   = $this->getEndTime();
        $whereQuery = "where LocalendTime >= '{$startTime}' AND  LocalendTime <= '{$endTime}'";
        
        $sql = "select * from analysis_A1_A2_Blend ".$whereQuery;
        $results = Yii::app()->db->createCommand($sql)->queryAll();
       $records =  $this->processRecords($results);
       return $records;
        
    }
    
    
    public function processRecords($resutls){
        
        
        $processedRecords = array();
          foreach ($resutls as $rawData) {
            $query = "select * from rm_settings where varKey = 'ANALYZER_FILTER_BAD_RECORDS' ";
            $result = Yii::app()->db->createCommand($query)->queryRow();
            if ($result && $result['varValue']) {
                $setting_filter_bad_records = (int) $result['varValue'];
            }
            //Filter Here
            if ($setting_filter_bad_records) {
                $row = DashHelper::validateAndSetAnalyzerRecordUsingRange($rawData, $acSettings);
            } else {
                $row = $rawData;
            }

            $elements = HeliosUtility::spElements();
            
            foreach ($elements as $ele) {


                $tAl2O3 = (float) $row['Al2O3'];

                $tSiO2 = (float) $row['SiO2'];

                $tFe2O3 = (float) $row['Fe2O3'];

                $tCaO = (float) $row['CaO'];

                //Calculate formulas
                if ($ele == 'LocalendTime') {
                  
                    $row[$ele] = $endTime;
                } elseif ($ele == 'KH') {
                    if ($tSiO2 > 0) {
                        $formulaVal = round(($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2),3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                }  elseif ($ele == 'C3S') {
                    if ($tSiO2 > 0) {
                        $formulaVal = round((4.07*$tCaO - 7.6*$tSiO2 - 6.72*$tAl2O3 - 1.43*$tFe2O3 - 2.852*$tSO3 ),3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                }  elseif ($ele == 'C3A') {
                    if ($tSiO2 > 0) {
                        $formulaVal = round((2.65 * $tAl2O3 - 1.69 * $tFe2O3),3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                } elseif ($ele == "SM") {
                    if (($tAl2O3 + $tFe2O3) > 0) {
                        $formulaVal = round((($tSiO2) / ($tAl2O3 + $tFe2O3)), 3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                } elseif ($ele == "IM" || $ele == "AM") {
                    if ($tFe2O3 > 0) {
                        $formulaVal = round(($tAl2O3 / $tFe2O3), 3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = round($formulaVal,3);
                }

            
            }
            
            $processedRecords[] = $row;
        }

        
        return $processedRecords;
    }
    
    public function getAverage($elements){
       
        
        $avgCol = [];
        foreach($elements as $ele){
            
            if($ele =="totalTons"){
                $avgCol[] = "sum($ele) as $ele";
            }else{
                $avgCol[] = "avg($ele) as $ele";
            }
        }
        $colQuery = implode(' , ',$avgCol );
        $startTime =  $this->getStartTime();
        $endTime   = $this->getEndTime();
        $whereQuery = "where LocalendTime >= '{$startTime}' AND  LocalendTime <= '{$endTime}'";
        
        $sql = "select min(LocalendTime) as LocalStartTime, max(LocalendTime) as LocalendTime, $colQuery from analysis_A1_A2_Blend ".$whereQuery;
        $results = Yii::app()->db->createCommand($sql)->queryAll();
        
        /*
         * 
         *                 $tAl2O3 = (float) $row['Al2O3'];

                $tSiO2 = (float) $row['SiO2'];

                $tFe2O3 = (float) $row['Fe2O3'];

                $tCaO = (float) $row['CaO'];

                //Calculate formulas
                if ($ele == 'LocalendTime') {
                  
                    $row[$ele] = $endTime;
                } elseif ($ele == 'KH') {
                    if ($tSiO2 > 0) {
                        $formulaVal = round(($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2),3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                }  elseif ($ele == 'C3S') {
                    if ($tSiO2 > 0) {
                        $formulaVal = round((4.07*$tCaO - 7.6*$tSiO2 - 6.72*$tAl2O3 - 1.43*$tFe2O3 - 2.852*$tSO3 ),3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                }  elseif ($ele == 'C3A') {
                    if ($tSiO2 > 0) {
                        $formulaVal = round((2.65 * $tAl2O3 - 1.69 * $tFe2O3),3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                } elseif ($ele == "SM") {
                    if (($tAl2O3 + $tFe2O3) > 0) {
                        $formulaVal = round((($tSiO2) / ($tAl2O3 + $tFe2O3)), 3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                } elseif ($ele == "IM" || $ele == "AM") {
                    if ($tFe2O3 > 0) {
                        $formulaVal = round(($tAl2O3 / $tFe2O3), 3);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = round($formulaVal,3);
                }

         */
        return $results;
        
    }
    public function getEndTime(){
        
        
      $startTime =   isset($this->endTime) ? $this->endTime : $this->currentTime;
      
      return $startTime;
        
    }
    public function getStartTime(){
        
        $defaultendTime = date('Y-m-d H:i:s' , strtotime() -  3600 * 24);
        $endTime =   isset($this->startTime) ? $this->startTime : $defaultendTime;
      
        return $endTime;
    }
}
