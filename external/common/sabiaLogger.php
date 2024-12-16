<?php

//----------------------------------------------------------------------------------------
// Class definition and functions for the logger object
//----------------------------------------------------------------------------------------
// SABIA Proprietary
// Copyright (c) 2003-2007, 2008, 2009 by SABIA, Inc.
//
//! \file
//! 
//! 
//! 
//! 
//! 
//

/*
  // Start logging
  $log->trace("trace message.");   // Not logged because TRACE < WARN
  $log->debug("debug message.");  // Not logged because DEBUG < WARN
  $log->info("info message.");    // Not logged because INFO < WARN
  $log->warn("warn message.");   // Logged because WARN >= WARN
  $log->error("error message.");   // Logged because ERROR >= WARN
  $log->fatal("fatal message.");   // Logged because FATAL >= WARN
 */



//require_once './log4php/Logger.php';

// Tell log4php to use our configuration file.
//Logger::configure('simulation_logproperties.xml');

class sabiaLogger {

    /** Holds the Logger. */
    private $log;
    private $con;

    /** Logger is instantiated in the constructor. */
    public function __construct($loggername = __CLASS__) {
        // The __CLASS__ constant holds the class name, in our case "Foo".
        // Therefore this creates a logger named "Foo" (which we configured in the config file)
        $this->log = Logger::getLogger($loggername);
    }

    public function setDbCon($con) {
        $this->con = $con;
    }

    /** Logger can be used from any member method. */
    public function trace($msg) {
        $this->log->trace($msg);
    }

    public function traceSkipDB($msg) {
        $this->log->trace($msg);
    }

    public function debug($msg) {
        $this->log->debug($msg);
    }

    public function info($msg) {
        $this->log->info($msg);
    }

    public function warn($msg) {
        $this->log->warn($msg);
    }

    public function error($msg) {
        $this->log->error($msg);
    }

    public function fatal($msg) {
        $this->log->fatal($msg);
    }

    public function fatalSkipDB($msg) {
        $this->log->fatal($msg);
    }

}

?>
