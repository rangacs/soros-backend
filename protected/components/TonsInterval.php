<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TonsInterval
 *
 * @author veda
 */
class TonsInterval extends IntervalObject {

    //put your code here
    private $startTime;
    private $endTime;
    private $tons;

    public function __construct($tons, $startTime = null, $endTime = null) {


        $this->tons = $tons;

        $this->startTime = isset($startTime) ? $startTime : false;
        $this->endTime = isset($endTime) ? $endTime : false;
    }

    public function init() {


        $dataLimit = $this->getDataLimit();

        $totalTons = 0;
        $currentDataID = 0;
        $this->endTime = date('Y-m-d H:i:s', time());

        $this->startTime = date('Y-m-d H:i:s', (time() - (7 * 24 * 60 * 60)));

        /*
        if (isset($dataLimit['maxDataID'])) {
            $currentDataID = $dataLimit['maxDataID'];
            while ($currentDataID >= $dataLimit['minDataID']) {
                $currentDataID--;
                $result = Yii::app()->db->createCommand("select * from analysis_A1_A2_Blend where totalTons !=0 and dataID = $currentDataID")->queryRow();

                if ($result) {

                    $this->startTime = $result['LocalstartTime'];
                }

                if ($totalTons <= $this->tons) {
                    $rowTotalTons = isset($result['totalTons']) ? $result['totalTons'] : 0;
                    $totalTons += $rowTotalTons;
                } else {

                    break;
                    //return  $timesArray;
                }
            }
        }
        */
        return true;
    }

    private function getDataLimit() {

        $array = array();
        $timeQuery = $this->getTimeQuery();
        $minDataIDQuery = "select min(dataID) as min from analysis_A1_A2_Blend  " . $timeQuery;

        $maxDataIDQuery = "select max(dataID) as max from analysis_A1_A2_Blend  " . $timeQuery;

        $minDataID = (int) Yii::app()->db->createCommand($minDataIDQuery)->queryScalar();
        $maxDataID = (int) Yii::app()->db->createCommand($maxDataIDQuery)->queryScalar();
        $array = array('minDataID' => $minDataID, 'maxDataID' => $maxDataID);
        return $array;
    }

    private function getTimeQuery() {

        if (isset($this->startTime) && isset($this->endTime)) {

            $whereClouse = " where LocalendTime > '" . $this->startTime . "' AND LocalendTime < '" . $this->endTime . "'";
            return 'LocalendTime ';
        } else {

            return '';
        }
    }

    public function getStartTime() {

        return $this->startTime;
    }

    public function getEndTime() {
        return $this->endTime;
    }

    public function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    public function setEndTime($endTime) {
        $this->endTime = $endTime;
    }

    public function getIntervalQuery($timeStamps) {

        $timeTickArry = array();
        foreach($timeStamps as $timeRow){
            
            $startTimeTick = strtotime($timeRow["LocalstartTime"]);
            $endTimeTick   = strtotime($timeRow["LocalendTime"]);
            $timeTickArry[$startTimeTick] = array('LocalstartTime' => $startTimeTick, 'LocalendTime' => $endTimeTick );
            
        }
        $sorArray = ksort($timeTickArry);
        $timeQuery = array();
        foreach($sorArray as $trow){
         
            $timeQuery[] = "LocalendTime > '" . date("Y-m-d H:i:s",$trow['LocalstartTime']) . "' and LocalenTime < '" . date("Y-m-d H:i:s",$trow['LocalendTime']) . "'";
        }
        return $timeQuery;
    }

}
