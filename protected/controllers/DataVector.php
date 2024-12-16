<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\models;
use Yii;
use \yii\base;
use yii\data\SqlDataProvider;
use yii\base\Object;

/**
 * Description of DataVector
 *
 * @author webtatva
 */
class DataVector extends Object{
    //put your code here
    
    public $detectorSource;
    public $startTime;
    public $endTime;
    public $pageType;
    public $elements;
    public $dataProvider;
    public $eleQueryString;
    public $gadget;
    
    public function __construct($gadget,$startTime,$endTime) {
    
        
        $this->detectorSource = $gadget->detector_source;
        $this->gadget     = $gadget;
        $this->startTime  = $startTime;
        $this->endTime    = $endTime;
        $eleArray         = $gadget->getElements();
        $this->eleQueryString = isset($eleArray['sql_column']) ? implode(', ', $eleArray['sql_column']) : '1';
        parent::__construct();
        $this->init();
    }
    
    public function init(){
     
        
        $countQuery = 'SELECT COUNT(*) FROM '.$this->detectorSource.' WHERE LocalendTime >= \''.$this->startTime.'\' AND LocalendTime <= \''.$this->endTime.'\'';
        $count      = Yii::$app->db->createCommand($countQuery)->queryScalar();
        $pageSize   = 10;
        
        //Sorting Data by Localend time *Highcahrt error#15
        if($this->gadget->gadget_type == 'Charts' || $this->gadget->gadget_type == 'Alert'){
            $pageSize = $count;
            $orderby  = "ORDER BY LocalendTime ASC";
        }else{
            $orderby  = "ORDER BY LocalendTime DESC";
        }
        $sql          = 'SELECT '.$this->eleQueryString.' FROM '.$this->detectorSource.' WHERE LocalendTime >= :start_time AND LocalendTime <= :end_time '.$orderby;          
        
        
        //echo $this->startTime.' debug => dsql '.$this->endTime.' =>'.$sql; // debug
       // die();
        
        $provider   = new SqlDataProvider([
            'sql' => $sql,
            'params' => [':start_time' => $this->startTime , ':end_time' => $this->endTime],
            'totalCount' => $count,
            'pagination' => [
               
                'pageSize' => $count,
            ],
            'sort' => [
                'attributes' => [
                    'LocalendTime',
                    ],
            ],
        ]);
        
     $this->dataProvider = $provider;   

    }
    
    
}
