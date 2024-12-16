<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MonitorDataProvidor
 *
 * @author veda
 */

define("TIME_CHECK",0);

class MonitorDataProvidor {

    //put your code here
    public $startTime;
    public $endTime;
    private $curDateTime;
    private $curTime;

    public function __construct() {
        $this->curDateTime = date("Y-m-d H:i:s");
	$this->curTime = time();
    }
    
    public function getData($elements) {

        $interval = new IntervalObject();

        $interval->setStartTime($this->startTime);
        $interval->setEndTime($this->endTime);
        //setStartTime
        //setEndTime
        $analysisDataProvider = new AnalysisDataProvider();
        $analysisDataProvider->setElements($elements);

        $this->logTime(112);
        $record = $analysisDataProvider->getIntervalAvg($interval);
        $this->logTime(113);
        return $record;
    }
    
     
	public function logTime($icntr) {
		$stTime = date("Y-m-d H:i:s");
		$sltime = time();

		$time_diff = round($sltime - $this->curTime,2);

		if(1)echo "Time $icntr:$stTime ($sltime) Diff: $time_diff  <br/>";
		
		$this->curDateTime = $stTime;
		$this->curTime = $sltime;
	}   

}
