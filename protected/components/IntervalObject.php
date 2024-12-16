<?php

/**
 * Description of IntervalObject
 *
 * @author veda
 */
class IntervalObject {

    //put your code here
    private $startTime;
    private $endTime;

    public function __construct($minutes = 0) {

        $currentTimeTick = time();

        $this->startTime = date('Y-m-d H:i:s', $currentTimeTick - $minutes * 60);
        $this->endTime = date('Y-m-d H:i:s', $currentTimeTick);
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

    public function getIntervalQuery() {

        return "LocalendTime > '" . $this->startTime . "' and LocalenTime < '" . $this->startTime . "'";
    }
    

}
