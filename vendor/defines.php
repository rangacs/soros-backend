<?php

//----------------------------------------------------------------------------------------
// defines.php - global PHP defines for analyzer UI
//----------------------------------------------------------------------------------------
// SABIA Proprietary
// Copyright (c) 2002-2007,2009 by SABIA, Inc.
//
//! \file
//! Global defines for the SABIA analyzer user interface.
//
//----------------------------------------------------------------------------------------

// Constants
define( "SECS_PER_MIN",    60 );              //!< Seconds per minute
define( "SECS_PER_HOUR", 3600 );              //!< Seconds per hour
define( "SECS_PER_DAY", 86400 );              //!< Seconds per calendar day

// Paths to config and status files
define( "UI_DOT_CONF_FILENAME",       "/usr/local/sabia-ck/ui.conf" );                   //!< Path to UI config file.
define( "UI_CONF",                    "/usr/local/sabia-ck/ui.conf" );                   //!< Path to UI config file (short name).
define( "ANALYZER_DOT_CONF_FILENAME", "/usr/local/sabia-ck/analyzer.conf" );             //!< Path to core analyzer config file.
define( "ANALYZER_CONF",              "/usr/local/sabia-ck/analyzer.conf" );             //!< Path to core analyzer config file (short name).
define( "DATAD_DOT_STATUS_FILENAME",  "/usr/local/sabia-ck/datad.status" );              //!< Path to datad status file (one of several - datad1, datad2...)
define( "PLCD_DOT_STATUS_FILENAME",   "/usr/local/sabia-ck/plcd.status" );               //!< Path to plcd status file
define( "PLCD_DOT_CONF_FILENAME",     "/usr/local/sabia-ck/plcd.conf" );                 //!< Path to plcd config file
define( "SHIFT_CONF_FILENAME",        "/usr/local/sabia-ck/plcd.status" );               //!< Where to get shift reset info
define( "PEERS_DOT_CONF_FILENAME",    "/usr/local/sabia-ck/peers.conf" );                //!< Peers file for networked analyzer (not implemented)
          //!< Calibration final adjustment gain/offset file.
define( "MSG_FILENAME",               "/var/www/html/export/usrMsgs.log" );              //!< Messages file path.
define( "SAMPLE_RESULTS_FILENAME",    "/var/www/html/export/staticSampleResults.log" );  //!< Path to static sample results file.
define( "DATAQ_LOG_FILEPATH",         "/usr/local/sabia-ck/dataq.log" );                 //!< Path to Dataq DI-194 output file from 'di_reader' program.
define( "ANALYZER_VERSION_FILEPATH",  "/usr/local/sabia-ck/version.cfg" );               //!< Path to core analyzer SW version file (installed by RPM).
define( "HPEAKD_ANALYZER_VERSION_FILEPATH",  "/usr/local/sabia-ck/hpeakd_version.cfg" );               //!< Path to core analyzer SW version file (installed by RPM).
define( "BPITVAC_ANALYZER_VERSION_FILEPATH",  "/usr/local/sabia-ck/bpitvac_version.cfg" );               //!< Path to core analyzer SW version file (installed by RPM).
define( "PLCD_VERSION_FILEPATH",      "/usr/local/sabia-ck/plcd_version.cfg" );          //!< Path to plcd version file (installed by RPM).
define( "HW_RAID_STATUS_FILE",        "/var/www/html/export/3wareRAIDStatus" );          //!< Path to 3Ware RAID status file written by SABIA cron job

//! The STATIC_CRON_LOCK file is a semaphore lock on the static cron job.
//! The file mod time is the age of the cron job; the cron script pid is stored inside.
define("STATIC_CRON_LOCK","/usr/local/sabia-ck/static_cron_lock");

// Paths to various analyzer binaries and directories
define( "SABIA_PATH",           "/usr/local/sabia-ck/");                  //!< Analyzer root installation directory.
define( "EXPORT_PATH",          "/var/www/html/export/" );                //!< Directory where exported data files go.
define( "RAWDATA_PATH",         "/usr/local/sabia-ck/" );                 //!< Base path to the rawdata file directories.
define( "RESPONSE_PATH",        "/usr/local/sabia-ck/rsp" );              //!< Response set directory path.
define( "ANALYZE_CMD",          "/usr/local/sabia-ck/bin/analyze" );      //!< Path to the 'analyze' program.
define( "CFG_EDIT_CMD",         "/usr/local/sabia-ck/bin/cfgedit");       //!< Path to the 'cfgedit' program.
define( "MMETER_READ_CMD",      "/usr/local/sabia-ck/bin/mmeter_read" );  //!< Path to program to read Mettler moisture meter.
define( "BACKANNOTATE_CMD",     "/usr/local/sabia-ck/bin/backAnnotate" ); //!< Path to rawdata backannotation program.
define( "SERVICE_ANALYZER_CMD", "/sbin/service analyzer" );               //!< Path and args for 'service analyzer' script.
define( "SERVICE_ANALYZER_STATUS_CMD", "/sbin/service analyzer status" ); //!< Path and args for 'service analyzer status' action

//! DATAD_BASE_NAME is the base name of all data daemon 'datadN' executable binaries.  If these are
//! ever renamed, many things would have to change to match.
define( "DATAD_BASE_NAME",  "datad" );

define( "ANALYZER_CORE_RPMNAME",    "sabia-ck" );                         //!< Base name (without version) of analyzer core SW RPM
define( "ANALYZER_PLCD_RPMNAME",    "sabia-ck-plcd" );                    //!< Base name (without version) of analyzer PLC daemon RPM
define( "ANALYZER_MODBUSD_RPMNAME", "sabia-ck-imodbusd" );                //!< Base name (without version) of analyzer Modbus/TCP xinetd daemon RPM
define( "ANALYZER_SABDAQ_RPMNAME",  "sabia-sabdaq" );                     //!< Base name (without version) of analyzer PCI card device driver RPM

//! ISO_DATE_FMT is the PHP date() format string for an ISO time string of the form needed by 'analyze'
define( "ISO_DATE_FMT", "Y-m-d\TH:i:s" );
//! DISPLAY_DATE_FMT is the PHP date() format string for user displays.  Eventually to be configurable in ui.conf
define( "DISPLAY_DATE_FMT", "Y-m-d H:i:s T" );
//! Format string used in strftime calls by data export functions
define( "EXPORT_STRFTIME_FMT", "%Y-%m-%d %H:%M:%S %Z" );

define( "BGCOLOR_MAIN", "#eeeeff" );                                      //!< Background color for most analyzer web pages

// These are defines for the filter types to apply when performing anaylze cmd - i.e. rock/coal/etc
// The define values MUST have the proper option syntax to be passed to 'analyze', including a leading space.
define( "NO_FILTERS", " -F \"\"  -B 0 -V 0 -M 0");   //!< No filters at all.
define( "NO_PARM_FILTER", " -B 0 -V 0 -M 0");        //!< No filters except rock/coal product type.
define( "FILTER_ROCK", " -F 1");                     //!< Only analyze data marked as "rock".
define( "FILTER_COAL", " -F 2");                     //!< Only analyze data marked as "coal".
define( "FILTER_ROCK_COAL", " -F 1-2");              //!< Analyze data marked as either "rock" or "coal".
define( "NO_PRODUCT_FILTER", " -F \"\"");            //!< Blank out the product type filter.

// Rock/coal/all product IDs for 20 Mile style coal tagging - options for -F
define( "MAT_ROCK", "1");
define( "MAT_COAL", "2");
define( "MAT_ROCK_COAL", "1-2");
define( "MAT_ALL", "\"\"");
define( "MAT_DEFAULT", "default" );

// Data tag string values applied for Rock/Coal quick tags
define( "MAT_ROCK_TAG", "Rock" );                    //!< Tag string applied for Rock.
define( "MAT_COAL_TAG", "Coal" );                    //!< Tag string applied for Coal.

// Alarm threshold defaults for status pages
define( "DISK_LOW_ALARM_THRESH_PCT_DFLT", 10.0 );    //!< Low disk space alarm raised if free disk below this percentage of total.
define( "DISK_CRIT_ALARM_THRESH_PCT_DFLT", 3.0 );    //!< Critical disk space alarm raised if free disk is below this percentage of total.
define( "ALIGN_GAIN_TARGET_VALUE", 1.000 );          //!< Ideal value of alignment gain (dimensionless).
define( "ALIGN_GAIN_ALARM_THRESH_DFLT", 0.02 );      //!< Display alarm if alignment gain is more than this amount away from 1.000
define( "H_PEAK_TARGET_CHANNEL", 99.5 );             //!< Ideal location of H peak, channels.
define( "H_PEAK_CHAN_ALARM_THRESH_DFLT", 10 );       //!< Display alarm if H peak is further than this away from 99.5

// Reserved user field names for rawdata tagging
define( "USERFIELD_OPERATOR_ID", "Operator_ID" );      //!< Name of reserved operator ID field.
define( "USERFIELD_SAMPLE_ID", "Sample_ID" );          //!< Name of reserved sample ID field.
define( "IDLE_TAG", "IDLE" );                          //!< Tag value applied to all rawdata fields when tagging is idle.
define( "USERFIELD_VAR_PREFIX", "user_field_" );       //!< String prepended to userfield names for _POST operations

// Defines controlling the user-entered (and field compacted) data tag info
define( "USER_FIELD_COUNT", 10);                     //!< Maximum number of user-defined fields.

//! Max size of a single user-defined field.  They are sized via the following calculation:
//! \verbatim
//!  $fieldSize = (integer) ( (80.0 - (integer)($numberOfFields/2.0) )/(integer)($numberOfFields/2.0) ); \endverbatim
//! The above equation is not exact as there are n-1 commas and n fields. But it's close enough.
define( "USER_FIELD_SIZE", 15);

//! Belt load weighting subinterval to use (seconds).  This becomes the bulk average
//! time and increment interval whenever belt load weighted averaging is in effect.
define( "BELT_LOAD_WEIGHTED_AVG_INTERVAL", 60 );

//! Special detector ID used to represent the average of all detectors.
define( "DET_ID_AVERAGE", "average" );

//----------------------------------------------------------------------------------------
// Defines for config files
//----------------------------------------------------------------------------------------

//----------------------------------------------------------------------------------------
// analyzer.conf
//----------------------------------------------------------------------------------------
define( "AN_CONF_SYSTEM_SECT", "system" );                                                           //!< Name of system params section in analyzer.conf
define( "AN_CONF_SYSTEM_NUM_CHANNELS",                      "num_channels" );                        //!< Number of channels in system, typically 512.
define( "AN_CONF_SYSTEM_NUM_DETECTORS",                     "num_detectors" );                       //!< Number of detectors in system, typically 1 or 2.
define( "AN_CONF_SYSTEM_HAVE_MOISTURE_AB",                  "have_moisture_meter_ab" );              //!< Tells whether we get moisture meter on Allen-Bradley PLC input.
define( "AN_CONF_SYSTEM_AUTOREAD_METTLER",                  "auto_read_mettler_moisture_meter" );    //!< Nonzero means use attached Mettler moisture meter (static personality).

// [datadN] - no section name defined since there can be multiple sections

define( "AN_CONF_DATAD_IDSTRING_1",                         "id_string_1" );                         //!< Data tagging string 1.
define( "AN_CONF_DATAD_IDSTRING_2",                         "id_string_2" );                         //!< Data tagging string 2.
define( "AN_CONF_DATAD_IDSTRING_3",                         "id_string_3" );                         //!< Data tagging string 3.
define( "AN_CONF_DATAD_IDSTRING_4",                         "id_string_4" );                         //!< Data tagging string 4.
define( "AN_CONF_DATAD_SABDAQ_DEVICE_NUM",                  "sabdaq_device_num" );                   //!< Device number (0-based) of PCI ADC card.
define( "AN_CONF_DATAD_INTEG_TIME_MS",                      "integ_time_ms" );                       //!< datad integration time in millisec (default 5000)
define( "AN_CONF_DATAD_ADC_ACCUM_MS",                       "adc_accum_ms" );                        //!< ADC card accumulation time, milliseconds (default 200).
define( "AN_CONF_DATAD_DATA_DIR_PATH",                      "data_dir_path" );                       //!< Path to the datad's rawdata directory.
define( "AN_CONF_DATAD_ACTIVE_PMT_MODE",                    "active_pmt_mode" );                     //!< PMT tracking mode keyword.
define( "AN_CONF_DATAD_PMT_VOLTAGE",                        "pmt_voltage" );                         //!< Fixed PMT voltage used when active_pmt_mode == fixed
define( "AN_CONF_DATAD_ACTIVE_PMT_MIN_SEARCH_VOLTAGE",      "active_pmt_min_search_voltage" );       //!< Minimum search voltage for PMT voltage tracking
define( "AN_CONF_DATAD_ACTIVE_PMT_MAX_SEARCH_VOLTAGE",      "active_pmt_max_search_voltage" );       //!< Maximum search voltage for PMT voltage tracking
define( "AN_CONF_DATAD_GLOBAL_OFFSET_EST_CHANS",            "global_offset_est_chans" );             //!< Global offset solution estimate and H peak target offset.
define( "AN_CONF_DATAD_ALIGNMENT_RESPONSE",                 "datad_alignment_response" );            //!< Alignment response set used with this detector's data
define( "AN_CONF_DATAD_ANALYSIS_RESPONSE",                  "datad_analysis_response" );             //!< Analysis response set used with this detector's data
define( "AN_CONF_DATAD_OFFSET_COMPENSATION",                "offset_compensation" );                 //!< Analog offset compensation DAC value.
define( "AN_CONF_DATAD_COUNT_RATE_FILTER_THRESH_CPS",       "count_rate_filter_thresh_cps" );        //!< Count rate filter threshold, CPS.  Zero disables the filter.
define( "AN_CONF_DATAD_COUNT_RATE_FILTER_DECAY_REFDATE",    "count_rate_filter_decay_refdate" );     //!< Source decay reference date for count rate filter.
define( "AN_CONF_DATAD_FINE_PID_PROPORTIONAL",              "active_pmt_fine_pid_proportional" );    //!< proportional HV tracking constant
define( "AN_CONF_DATAD_DELTAV_SIGNIFICANCE_THRESH",         "active_pmt_deltav_significance_thresh");//!< threshold for "significant" HV jump
define( "AN_CONF_DATAD_H_PEAK_RECOG_THRESH",                "h_peak_recog_thresh" );                 //!< H peak recognition metric threshold
define( "AN_CONF_DATAD_H_PEAK_MAX_FWHM",                    "h_peak_max_fwhm" );                     //!< H peak recognition max FWHM before rejection
define( "AN_CONF_DATAD_USE_RESULTS",                        "use_results" );                         //!< Whether to use this detector's results in outputs.
define( "AN_CONF_DATAD_DISPLAY_NAME",                       "display_name" );                        //!< UI display name for the detector.

// Values for active_pmt_mode
define( "AN_CONF_DATAD_ACTIVE_PMT_MODE_FINE_TRACK",         "fine_track" );                          //!< PMT tracking mode keyword value for fine-tracking mode.
define( "AN_CONF_DATAD_ACTIVE_PMT_MODE_FIXED",              "fixed" );                               //!< PMT tracking mode keyword value for fixed voltage mode.

// [analysis]
define( "AN_CONF_ANALYSIS_SECT",                            "analysis" );                            //!< Name of analysis section in analyzer.conf
define( "AN_CONF_ANALYSIS_BINNING_INTERVAL",                "binning_interval" );                    //!< Interval to use when aggregating data for analysis. (secs)
define( "AN_CONF_ANALYSIS_EXCLUDED_CHANNELS",               "excluded_channels" );                   //!< List of excluded channel ranges for analysis phase, e.g 1-135,425-512
define( "AN_CONF_ANALYSIS_DO_MASSFLOW_WEIGHTING",           "do_massflow_weighting" );               //!< Whether to do massflow weighting or not.  Set 0 for static analyzer in belt personality.
define( "AN_CONF_ANALYSIS_DO_SUBINTERVAL_AVERAGING",        "do_subinterval_averaging" );            //!< Whether to do subinterval (by-minute) averaging.  Must be on for belt analyzers.
define( "AN_CONF_ANALYSIS_AVERAGING_SUBINTERVAL_SECS",      "averaging_subinterval_secs" );          //!< By-minute averaging subinterval, seconds.  Defaults to 60 if not specified.  Undocumented.
define( "AN_CONF_ANALYSIS_MIN_GOOD_DATA_SECS_PER_MIN",      "min_good_data_secs_per_min" );          //!< Minimum goodDataSecs per minute of data to accept results.
define( "AN_CONF_ANALYSIS_MIN_SECS_AFTER_PMT_VOLTAGE_JUMP", "minimum_secs_after_pmt_voltage_jump" ); //!< How long data is considered invalid after significant PMT voltage change.
define( "AN_CONF_ANALYSIS_OBA_MASSFLOW_LOWER_BOUND_TPH",    "oba_massflow_lower_bound_tph" );        //!< Massflow filter threshold.  Zero disables the filter.
define( "AN_CONF_ANALYSIS_OBA_BELT_SPEED_LOWER_BOUND_FPM",  "oba_belt_speed_lower_bound_fpm" );      //!< Belt speed filter threshold.  Zero disables the filter.
define( "AN_CONF_ANALYSIS_COUNT_INTEGRAL_LBOUND_CHAN",      "count_integral_lbound_chan" );          //!< Lower limit channel for "counts above threshold" metric
define( "AN_CONF_ANALYSIS_COUNT_INTEGRAL_UBOUND_CHAN",      "count_integral_ubound_chan" );          //!< Upper limit channel for "counts above threshold" metric

// [alignment]
define( "AN_CONF_ALIGNMENT_SECT",                           "alignment" );                           //!< Name of alignment section in analyzer.conf
define( "AN_CONF_ALIGNMENT_MLR_RESPONSE_SET",               "mlr_response_set" );                    //!< Name of alignment response set for single-detector system.
define( "AN_CONF_ALIGNMENT_ENABLE_BROADENING",              "enable_broadening" );                   //!< Whether broadening correction is enabled
define( "AN_CONF_ALIGNMENT_GLOBAL_OFFSET_BRACKET_INCR",     "global_offset_bracket_incr" );          //!< global offset initial bracketing increment, channels
define( "AN_CONF_ALIGNMENT_MLR_EXCLUDED_CHANNELS",          "mlr_excluded_channels" );               //!< List of excluded channel ranges for alignment phase.
define( "AN_CONF_ALIGNMENT_MLR_CHISQR_TOLERANCE",           "mlr_chisqr_tolerance" );                //!< chisqr tolerance metric for MLR solution.
define( "AN_CONF_ALIGNMENT_H_PEAK_METRIC_THRESHOLD",        "h_peak_metric_threshold" );             //!< metric threshold for H peak recognition
define( "AN_CONF_ALIGNMENT_PRE_ALIGN_SMOOTHING_ORDER",      "pre_align_smoothing_order" );           //!< smoothing kernel order applied before alignment
define( "AN_CONF_ALIGNMENT_PK_SEARCH_RAW_SMOOTHING_ORDER",  "pk_search_raw_smoothing_order" );       //!< smoothing kernel order applied before initial peak search


// [bpi]
define( "AN_CONF_BPI_SECT",                "bpi" );            
define( "AN_CONF_BPI_DET_1",               "bpi_detecter_id_1" );
define( "AN_CONF_BPI_DET_2",               "bpi_detecter_id_2" );   


//----------------------------------------------------------------------------------------
// ui.conf
//----------------------------------------------------------------------------------------

// [display]
define( "UI_CONF_DISPLAY_SECT", "display" );                          //!< Name of display params section in ui.conf

define( "UI_CONF_DISPLAY_UNDER_CONST",        "under_construction" ); //!< Causes display of "Under construction" page.
define( "UI_CONF_DISPLAY_EXPERT_USER",        "expert_user" );        //!< Enables expert mode display (extra info mainly).
define( "UI_CONF_DISPLAY_BACK_TIC",           "back_tic" );           //!< Enables display of 'analyze' backtick commands.
define( "UI_CONF_DISPLAY_BY_MINUTE_BACK_TIC", "by_minute_back_tic" ); //!< Enables display of by-minute 'analyze' backtick commands.
define( "UI_CONF_DISPLAY_MAX_SHIFT_DUR",      "max_shift_duration" ); //!< Maximum duration of an operating "shift", seconds.
define( "UI_CONF_DISPLAY_ALL_RSP_SETS",       "all_rsp_sets" );       //!< Enables display of all response sets
define( "UI_CONF_DISPLAY_EXTENDED_RSLTS",     "extended_results" );   //!< Enables display of raw coeffs and alignment table.
define( "UI_CONF_DISPLAY_STATIC_CACHE_PER",   "static_sample_cache_period" ); //!< Cache period (secs) for static samples.
define( "UI_CONF_DISPLAY_STATIC_SEARCH_LIM",  "static_sample_search_limit" ); //!< Max period (secs) to search back for a static sample.
define( "UI_CONF_DISPLAY_BELT_SHIFT_CTL",     "belt_shift_control" ); //!< Enables manual shift reset activation from web.
define( "UI_CONF_DISPLAY_STATIC",             "static" );             //!< True if static analyzer.
define( "UI_CONF_DISPLAY_IDLE",               "IDLE" );               //!< Enables display of IDLE tagged data times
define( "UI_CONF_DISPLAY_STAB_DELAY",         "stabilization_delay"); //!< Static analyzer stabilization delay (secs) after inserting new sample.
define( "UI_CONF_DISPLAY_DATA_FRAME_URI",     "data_frame_uri" );     //!< Initial URI for the main data frame on the start page.
define( "UI_CONF_DISPLAY_DATA_FRAME_DEFAULT", "status.php" );         //!< Default value for data_frame_uri
define( "UI_CONF_DISPLAY_MATERIAL_TYPE_SEL",  "material_type_select" ); //!< Enables display of material type filter selection inputs.

// [inputs]
define( "UI_CONF_INPUTS_SECT",                "inputs" );                   //!< Name of inputs control section in ui.conf
define( "UI_CONF_INPUTS_NEED_EXT_MOISTURE",   "need_external_moisture_input" ); //!< True means static analyzer needs moisture reading from Mettler device or user input

// [defaults]
define( "UI_CONF_DEFAULTS_SECT",              "defaults" );           //!< Name of default values section in ui.conf
define( "UI_CONF_DEFAULTS_BULK_AVG_PER",      "bulk_avg_period" );    //!< Default bulk averaging period in seconds.
define( "UI_CONF_DEFAULTS_DATA_COLL_PER",     "data_collection_period" ); //!< Default static analysis timespan.
define( "UI_CONF_DEFAULTS_ANALYSIS_INCREMENT","analysis_increment_interval" ); //!< Default origin increment (secs) between analyses
define( "UI_CONF_DEFAULTS_SPAN",              "span" );               //!< Default analysis timespan (secs).

// [labels]
define( "UI_CONF_LABELS_SECT",                "labels" );             //!< Name of label strings section in ui.conf
define( "UI_CONF_LABELS_PRODUCT_NAME",        "product_name" );       //!< Product name of the analyzer.

// [personality]
define( "UI_CONF_PERSONALITY_SECT",           "personality" );        //!< Name of personality section in ui.conf
define( "UI_CONF_PERSONALITY_STATIC",         "static" );             //!< ID string for static personality
define( "UI_CONF_PERSONALITY_BELT",           "belt" );               //!< ID string for belt personality

// [running_averages] - a confListPair section!
define( "UI_CONF_RUNNING_AVGS_SECT",          "running_averages" );             //!< Name of running averages section in ui.conf

// [running_average_displays] - another confListPair section!
define( "UI_CONF_RUNNING_AVG_DISP_SECT",      "running_average_displays" );     //!< Name of running average material stream display conflist section
define( "UI_CONF_RUNNING_AVG_DISP_COAL",      "coal" );                         //!< Enables display of running avgs for "coal" mat'l type.
define( "UI_CONF_RUNNING_AVG_DISP_ROCK",      "rock" );                         //!< Enables display of running avgs for "rock" mat'l type.
define( "UI_CONF_RUNNING_AVG_DISP_ROCKCOAL",  "rock_coal" );                    //!< Enables display of running avgs for both mat'l types.
define( "UI_CONF_RUNNING_AVG_DISP_ALL",       "all" );                          //!< Enables display of running avgs regardless of material type.

// [running_average_controls] - options for running average pages
define( "UI_CONF_RUNNING_AVG_CTLS_SECT",      "running_average_controls" );     //!< Name of running average controls section in ui.conf
define( "UI_CONF_RUNNING_AVG_CTLS_SHOW_PLOTS","show_plots" );                   //!< Whether to display trend plots on running avg page (default true)
define( "UI_CONF_RUNNING_AVG_CTLS_SHOW_ALIGNMENT","show_alignment" );           //!< Whether to display alignment table on running avg page (default true)
define( "UI_CONF_RUNNING_AVG_CTLS_BLOCK_MODE","block_mode" );

// [alignment_table] options
define( "UI_CONF_ALIGNMENT_TABLE_SECT",       "alignment_table" );              //!< Name of alignment table section in ui.conf
define( "UI_CONF_ALIGNMENT_SPAN",             "alignment_span" );               //!< Time span in seconds of alignment table (default 1800)
define( "UI_CONF_ALIGNMENT_RAWDATA_FILTERS",  "alignment_rawdata_filters" );    //!< Whether to apply rawdata filters in alignment table (default false)


// [trending]
define( "UI_CONF_TRENDING_SECT",              "trending" );                     //!< Name of trend computations control section in ui.conf
define( "UI_CONF_TRENDING_PLOT_INTERVAL",     "plot_interval" );                //!< Trend plot interval, seconds.
define( "UI_CONF_TRENDING_MAIN_INTERVAL",     "main_trend_interval" );          //!< Main trending interval, seconds.
define( "UI_CONF_TRENDING_MAIN_AVG_INTERVAL", "main_trend_avg_interval" );      //!< Trend averaging interval, seconds.

// [quick_trend_table]
define( "UI_CONF_QUICK_TREND_SECT",           "quick_trend_table" );            //!< Name of quick trend control section in ui.conf

// [id_string_field_names]
define( "UI_CONF_IDSTRING_FIELD_SECT",        "id_string_field_names" );        //!< Name of ID string field naming section in ui.conf

// [static_dropdown_selection]
define( "UI_CONF_STATIC_DROPDOWN_SECT",       "static_dropdown_selection" );    //!< Name of static page dropdown menu control section in ui.conf

// [results_table]
define( "UI_CONF_RESULTS_TABLE_SECT",                "results_table" );         //!< Name of section controlling contents and appearance of analysis results table
define( "UI_CONF_RESULTS_TABLE_SHOW_TOTAL_TONS",     "show_total_tons" );       //!< Enables display of total tons row in results table
define( "UI_CONF_RESULTS_TABLE_SHOW_GOOD_DATA_TIME", "show_good_data_time" );   //!< Enables display of good data time row in results table
define( "UI_CONF_RESULTS_TABLE_NUM_DECIMALS",        "num_decimals" );          //!< How many decimal places to display for normal results
define( "UI_CONF_RESULTS_TABLE_NUM_DECIMALS_DFLT",   2 );                       //!< Default value for num_decimals

// [status_limits]
define( "UI_CONF_STATUS_LIMITS_SECT",                "status_limits" );         //!< Name of section with alarm limits for status items
define( "UI_CONF_STATUS_LIMITS_COUNTRATE_LBOUND",    "count_rate_raw_cpm_lbound" ); //!< Raw count rate lower bound, cpm
define( "UI_CONF_STATUS_LIMITS_HFWHM_UBOUND",        "fwhm_ubound" );           //!< H FWHM upper bound
define( "UI_CONF_STATUS_LIMITS_GDS_LBOUND",          "good_data_secs_lbound" ); //!< Good data seconds lower bound
define( "UI_CONF_STATUS_LIMITS_HPEAKRAW_NOMINAL",    "h_peak_raw_nominal" );    //!< Raw H peak nominal value (typ. 99.5)
define( "UI_CONF_STATUS_LIMITS_HPEAKRAW_MAXDIFF",    "h_peak_raw_maxdiff" );    //!< Max num chans raw H peak can be from nominal
define( "UI_CONF_STATUS_LIMITS_ALIGNGAIN_MAXDIFF",   "align_gain_maxdiff" );    //!< Max amount alignment gain can be from nominal 1.0
define( "UI_CONF_STATUS_LIMITS_ALIGNCHISQR_UBOUND",  "align_chisqr_ubound" );   //!< Alignment chi sqr upper bound
define( "UI_CONF_STATUS_LIMITS_GLOBALOFFSET_NOMINAL","global_offset_nominal" ); //!< Global offset nominal value (typ. 0)
define( "UI_CONF_STATUS_LIMITS_GLOBALOFFSET_MAXDIFF","global_offset_maxdiff" ); //!< Global offset max excursion from nominal, in chans
define( "UI_CONF_STATUS_LIMITS_HVCMD_LBOUND",        "hv_cmd_lbound" );         //!< PMT HV command lower bound
define( "UI_CONF_STATUS_LIMITS_HVCMD_UBOUND",        "hv_cmd_ubound" );         //!< PMT HV command upper bound
define( "UI_CONF_STATUS_LIMITS_BELTSPEED_LBOUND",    "belt_speed_lbound" );     //!< Belt speed input lower bound
define( "UI_CONF_STATUS_LIMITS_BELTSPEED_UBOUND",    "belt_speed_ubound" );     //!< Belt speed input upper bound
define( "UI_CONF_STATUS_LIMITS_MASSFLOW_LBOUND",     "mass_flow_lbound" );      //!< Massflow (TPH) input lower bound
define( "UI_CONF_STATUS_LIMITS_MASSFLOW_UBOUND",     "mass_flow_ubound" );      //!< Massflow (TPH) input upper bound
define( "UI_CONF_STATUS_LIMITS_DETTEMP_NOMINAL",     "det_temp_nominal" );      //!< Detector temperature nominal value (typ. 40 C)
define( "UI_CONF_STATUS_LIMITS_DETTEMP_MAXDIFF",     "det_temp_maxdiff" );      //!< Detector temperature max excursion from nominal
define( "UI_CONF_STATUS_LIMITS_HVREADBACK_LBOUND",   "hv_readback_lbound" );    //!< HV readback value lower bound
define( "UI_CONF_STATUS_LIMITS_HVREADBACK_UBOUND",   "hv_readback_ubound" );    //!< HV readback value upper bound

// [dataq]
define( "UI_CONF_DATAQ_SECT", "dataq" );                       //!< Name of the Dataq params section in ui.conf

define( "UI_CONF_DATAQ_AIN1_DISP_ENA",  "aIn1DisplayEnable" ); //!< Analog input 1 display enable
define( "UI_CONF_DATAQ_AIN2_DISP_ENA",  "aIn2DisplayEnable" ); //!< Analog input 2 display enable
define( "UI_CONF_DATAQ_AIN3_DISP_ENA",  "aIn3DisplayEnable" ); //!< Analog input 3 display enable
define( "UI_CONF_DATAQ_AIN4_DISP_ENA",  "aIn4DisplayEnable" ); //!< Analog input 4 display enable

define( "UI_CONF_DATAQ_DIN1_DISP_ENA",  "dIn1DisplayEnable" ); //!< Digital input 1 display enable
define( "UI_CONF_DATAQ_DIN2_DISP_ENA",  "dIn2DisplayEnable" ); //!< Digital input 2 display enable
define( "UI_CONF_DATAQ_DIN3_DISP_ENA",  "dIn3DisplayEnable" ); //!< Digital input 3 display enable

define( "UI_CONF_DATAQ_AIN1_LABEL",     "aIn1Label" );         //!< Analog input 1 display label
define( "UI_CONF_DATAQ_AIN2_LABEL",     "aIn2Label" );         //!< Analog input 2 display label
define( "UI_CONF_DATAQ_AIN3_LABEL",     "aIn3Label" );         //!< Analog input 3 display label
define( "UI_CONF_DATAQ_AIN4_LABEL",     "aIn4Label" );         //!< Analog input 4 display label

define( "UI_CONF_DATAQ_AIN1_UNITS",     "aIn1Units" );         //!< Analog input 1 display units
define( "UI_CONF_DATAQ_AIN2_UNITS",     "aIn2Units" );         //!< Analog input 2 display units
define( "UI_CONF_DATAQ_AIN3_UNITS",     "aIn3Units" );         //!< Analog input 3 display units
define( "UI_CONF_DATAQ_AIN4_UNITS",     "aIn4Units" );         //!< Analog input 4 display units

define( "UI_CONF_DATAQ_AIN1_SCALE",     "aIn1Scale" );         //!< Analog input 1 scale factor
define( "UI_CONF_DATAQ_AIN2_SCALE",     "aIn2Scale" );         //!< Analog input 2 scale factor
define( "UI_CONF_DATAQ_AIN3_SCALE",     "aIn3Scale" );         //!< Analog input 3 scale factor
define( "UI_CONF_DATAQ_AIN4_SCALE",     "aIn4Scale" );         //!< Analog input 4 scale factor

define( "UI_CONF_DATAQ_AIN1_OFFSET",    "aIn1Offset" );        //!< Analog input 1 calibration offset
define( "UI_CONF_DATAQ_AIN2_OFFSET",    "aIn2Offset" );        //!< Analog input 2 calibration offset
define( "UI_CONF_DATAQ_AIN3_OFFSET",    "aIn3Offset" );        //!< Analog input 3 calibration offset
define( "UI_CONF_DATAQ_AIN4_OFFSET",    "aIn4Offset" );        //!< Analog input 4 calibration offset

define( "UI_CONF_DATAQ_AIN1_ALM_RANGE", "aIn1AlarmRange" );    //!< Analog input 1 alarm range
define( "UI_CONF_DATAQ_AIN2_ALM_RANGE", "aIn2AlarmRange" );    //!< Analog input 2 alarm range
define( "UI_CONF_DATAQ_AIN3_ALM_RANGE", "aIn3AlarmRange" );    //!< Analog input 3 alarm range
define( "UI_CONF_DATAQ_AIN4_ALM_RANGE", "aIn4AlarmRange" );    //!< Analog input 4 alarm range

define( "UI_CONF_DATAQ_DIN1_LABEL",     "dIn1Label" );         //!< Digital input 1 display label
define( "UI_CONF_DATAQ_DIN2_LABEL",     "dIn2Label" );         //!< Digital input 2 display label
define( "UI_CONF_DATAQ_DIN3_LABEL",     "dIn3Label" );         //!< Digital input 3 display label

define( "UI_CONF_DATAQ_DIN1_UNITS",     "dIn1Units" );         //!< Digital input 1 display units
define( "UI_CONF_DATAQ_DIN2_UNITS",     "dIn2Units" );         //!< Digital input 2 display units
define( "UI_CONF_DATAQ_DIN3_UNITS",     "dIn3Units" );         //!< Digital input 3 display units

define( "UI_CONF_DATAQ_DIN1_SCALE",     "dIn1Scale" );         //!< Digital input 1 scale factor
define( "UI_CONF_DATAQ_DIN2_SCALE",     "dIn2Scale" );         //!< Digital input 2 scale factor
define( "UI_CONF_DATAQ_DIN3_SCALE",     "dIn3Scale" );         //!< Digital input 3 scale factor

define( "UI_CONF_DATAQ_DIN1_OFFSET",    "dIn1Offset" );        //!< Digital input 1 calibration offset
define( "UI_CONF_DATAQ_DIN2_OFFSET",    "dIn2Offset" );        //!< Digital input 2 calibration offset
define( "UI_CONF_DATAQ_DIN3_OFFSET",    "dIn3Offset" );        //!< Digital input 3 calibration offset

define( "UI_CONF_DATAQ_DIN1_ALM_RANGE", "dIn1AlarmRange" );    //!< Digital input 1 alarm range
define( "UI_CONF_DATAQ_DIN2_ALM_RANGE", "dIn2AlarmRange" );    //!< Digital input 2 alarm range
define( "UI_CONF_DATAQ_DIN3_ALM_RANGE", "dIn3AlarmRange" );    //!< Digital input 3 alarm range

//----------------------------------------------------------------------------------------
// datadN.status
//----------------------------------------------------------------------------------------
define( "DATAD_STATUS_SECT",                "datad_status" );          //!< Name of datad status section
define( "DATAD_STATUS_ACTIVE_PMT_STATE",    "active_pmt_state" );      //!< Current PMT tracking state
define( "DATAD_STATUS_COUNT_RATE_CPS",      "count_rate_cps" );        //!< Hardware count rate, counts/sec
define( "DATAD_STATUS_OUTPUT_PMT_VOLTAGE",  "output_pmt_voltage" );    //!< Commanded PMT voltage, Volts
define( "DATAD_STATUS_HV_DAC_SETTING",      "hv_dac_setting" );        //!< HV coarse DAC output value
define( "DATAD_STATUS_HV_FINE_DAC_SETTING", "hv_fine_dac_setting" );   //!< HV fine DAC output value

//----------------------------------------------------------------------------------------
// plcd.status
//----------------------------------------------------------------------------------------
define( "PLCD_STATUS_SECT",             "plcd_status" );           //!< Name of plcd status section
define( "PLCD_STATUS_LAST_SHIFT_RESET", "last_shift_reset_time" ); //!< Timestamp of last PLC shift reset action
define( "PLCD_STATUS_SHIFT_RESET_GMT",  "shift_reset_time_gmt" );  //!< GMT time string of last PLC shift reset action


// Window frame names for the standard frameset
//! \todo These frames oughtta become divs
define( "FRAME_NAME_DATA",              "dataFrame" );         //!< Name of big lower pane for data/results.
define( "FRAME_NAME_LOGO",              "logoFrame" );         //!< Name of upper logo/banner frame.
define( "FRAME_NAME_MSG",               "msgFrame" );          //!< Name of upper right scrolling msgs frame
define( "FRAME_NAME_NAV",               "navFrame" );          //!< Name of left column nav bar frame.


//----------------------------------------------------------------------------------------
// UI Strings
//----------------------------------------------------------------------------------------

define( "ANALYSIS_ENGINE_ERR_PREFIX", "Analysis engine reports error:" );


//-------------------------window names-----------------------
// These are the 2nd args to window.open() jscript calls.

//---belt analyzer--------
//"B_AVG_WIN"     window that displays running averages
//"STAT_WIN"      displays current analyzer status
//"B_CONF_WIN"    display to mod current configuration
//"B_CUM_WIN"     win to enter range and display cumulative results
//"T_PLTO_WIN"    trend plot main window where user select options
//"B_PLOT_WIN"    belt plot window for trending of final results
//"B_PLOT_RAW_WIN"    belt plot window for trending of raw results
//"B_RECORDS_WIN" belt window to display and search records
//"TAG_DATA_WIN"  window for tagging data
//"QUICK_TREND_WIN" for a quick 1 min trend window

//---static analyzer-------------------------------------------
//"S_MAIN_WIN"    window that displays running averages

//---on line analyzer-------
//"OL_MAIN_WIN"   main online analyzer window

// must have no blank spaces or cr after the php mark Otherwise will screw up
// plot/image tagged pages



//********************* Config Files **********************************************
define( "LOCAL_LOCK_PREFIX", "!" );   //!< Name prefix implying local locked value
define( "PATH_SEP_CHAR", "/" );       //!< Path separator in group name
define( "PATH_SEP_STR", "/" );        //!< Path separator in group name (same as CHAR in PHP)
define( "COMMENT_DELIMS", "#" );      //!< Comment delimiters as string.  PHP version note: we only support ONE comment delim



//----------------------------------------------------------------------------------------
// usrCalDefines.php - user applied calibration defines 
//----------------------------------------------------------------------------------------
define("CAL_ADJUST_FILE", "/usr/local/sabia-ck/cal_adjust/cal_adjust.cfg" );
define("CAL_ADJUST_DIR", "/usr/local/sabia-ck/cal_adjust/usrCalLib/" );
define("STD_CAL_BUTTON_NAME","Standard Calibration" );
define("USR_CAL_BUTTON_NAME","User Calibration" );
define("SCALE_PARAM_BUTTON_NAME","Scale User Params" );
define("DEFINE_PARAM_BUTTON_NAME","Define User Params" );

define("CAL_PARAM_NAME_FILE", "/usr/local/sabia-ck/cal_adjust/usrCalParamNames.php" );

define("ANALYSIS_AVG_TABLE",'analysis_A1_A2_Blend');

//Spectrum trend Plot trend

define("MIN_TREND_PLOT_SPAN_MINS",5);
define("MAX_TREND_PLOT_SPAN_MINS", 60 * 10 );

?>
