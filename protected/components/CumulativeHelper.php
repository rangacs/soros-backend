<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CumulativeHelper
 *
 * @author veda
 */
class CumulativeHelper extends MonitorDataProvidor{

 

    public $startTime;
    public $endTime;

    public function __construct($startTime = null) {
        
        $this->startTime = $startTime;
        
        $this->endTime =  date('Y-m-d H:i:s',time());
    }

    public function init() {

        if (isset($_SESSION['cumulative_start_time'])) {

            $this->startTime = $_SESSION['cumulative_start_time'];
        } else {
            $_SESSION['cumulative_start_time'] = date('Y-m-d H:i:s', time() - 60 * 60);
        }

      
    }

}
