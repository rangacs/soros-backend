<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RoolingHelper
 *
 * @author veda
 */
class RoolingHelper extends MonitorDataProvidor{
    //put your code here
    
    
    public $startTime;
    public $endTime;
    public $rollingMin;

    public function __construct($rollingMin) {
        
        $this->rollingMin = $rollingMin;
    }

    public function init() {

        if (isset($_SESSION['rolling_minute'])) {
            
            $this->rollingMin = $_SESSION['rolling_minute'];
        } else {
              $_SESSION['rolling_minute'] = 60;
               $this->rollingMin = 60;
        }

        $this->startTime =  date('Y-m-d H:i:s', time() -($this->rollingMin * 60) );
        $this->endTime   = date('Y-m-d H:i:s', time());
    }

}


