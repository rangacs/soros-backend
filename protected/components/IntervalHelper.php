<?php


/**
 * Description of IntervalHelper
 *
 * @author veda
 */
class IntervalHelper extends MonitorDataProvidor{

    //put your code here

    public $startTime;
    public $endTime;

    public function __construct($startDate = NULL, $endDate = NULL) {
        
    }

    public function init() {

        $tmp1 =  $_SESSION['interval_end_time'];
        $tmp2 =  $_SESSION['interval_start_time'];
        
        if (isset($_SESSION['interval_start_time'])) {
            
            $this->startTime = $tmp2;
        } else {
               $_SESSION['interval_start_date'] = date('Y-m-d H:i:s', time() - 60 * 60);
               $this->startTime = $_SESSION['interval_start_date'];
        }

         if (isset($_SESSION['interval_end_time'])) {
            
            $this->endTime = $tmp1;
        } else {
            $_SESSION['interval_end_time'] = date('Y-m-d H:i:s', time());
            
            $this->endTime == date('Y-m-d H:i:s', time());
        }
    }

}
