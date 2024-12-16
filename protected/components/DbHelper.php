<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbHelper
 *
 * @author veda
 */
class DbHelper {
    //put your code here
   
    public $hasAnalyserData;
    public $hasFeederData;
    
    
    public function __construct() {
        
        
    }
    
    
    
    public function makeDbLive(){
        
        $updateTables = array(array('analysis_A1_A2_Blend','LocalendTime') ,
            array('rm_source_feeder_inputs','LocalendTime'),
            array('rm_ctrl_output_feedrates','updated'),
            array('rm_inputoutputdump','rm_updated'),
            array('rm_runlog','rm_updated'),
            array('calibration_log','updated')
            );
        
        
        foreach($updateTables as $item){
            
            $this->updateData($item[0], $item[1]);
        }
        
    }
    
    public function updateData($table,$column,$timestamp = false){
     
        
        if($timestamp == false){
            $timestamp = date('Y-m-d H:i:s');
            
        }
        
        $maxDate = $this->getmaxDateInTable($table, $column);
        
        $diffTime = $this->getUpdateTimeDiff($maxDate, $timestamp);
        
        
        if($diffTime > 0){
            
            $updateQuery = "update $table set  $column = date_add($column , interval $diffTime minute )";
        }else{
            
            $updateQuery = "update $table set  $column = date_add($column , interval $diffTime minute )";
        }
        
        return Yii::app()->db->createCommand($updateQuery)->query();
    }
    
    
    public function  getUpdateTimeDiff($oldTime,$currentTime){
     
        $currentTime = strtotime($currentTime);
        $oldTimeTick = strtotime($oldTime);
        
        return ($currentTime - $oldTimeTick)/ 60;
    }
    
    public function dataExtis($table){
        
        $contQuery = 'select count(*) count from '.$table;
        $count = Yii::app()->db->createCommand($contQuery)->queryScalar();
        
        return $count;
    }
    
    public function getmaxDateInTable($table,$column){
        
        $query = "select max($column) from $table";
        
        $maxDate = Yii::app()->db->createCommand($query)->queryScalar();
        
        return $maxDate;
    }
    
     public function minDateInTable($table,$column){
        
         
        $query = "select min($column) from $table";
        
        
        $minDate = Yii::app()->db->createCommand($query)->queryScalar();
        
        return $minDate;
    }
}
