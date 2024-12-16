<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include( CAL_PARAM_NAME_FILE );

/**
 * Description of UsrCalUtils
 *
 * @author webtatva
 */
class UsrCalUtils {
    //put your code here
    
static function usrCalEnabled()
{
    // get the cal_adjust control file
    $cadj = new ConfigFile();
    if ( $cadj->load( CAL_ADJUST_FILE ) )
    {
        $temp = "/" . "GLOBAL";
        $cadj->setPath( $temp );
        $enabled = $cadj->readEntry( "enable_user_calibration", "" );
    }
    else
    {
        return FALSE;
    }

    if( $enabled == 'yes' )
        return TRUE;
    else
        return FALSE;
}

static function writeEnableUsrCal( $value )
{
    // get the cal_adjust control file
    $cadj = new ConfigFile();
    if ( $cadj->load( CAL_ADJUST_FILE , false) )
    {
        $temp = "/" . "GLOBAL";
        $cadj->setPath( $temp );
        $cadj->writeEntry( "enable_user_calibration", $value );
        $cadj->flush();
    }
    else
    {
        return FALSE;
    }
}

static function readUsrCalPath( &$pathSpec )
{
    $cadj = new ConfigFile();
    if ( $cadj->load( CAL_ADJUST_FILE ) )
    {
        $cadj->setPath( "/USRCAL" );
        $pathSpec->dir = $cadj->readEntry( "lib_dir", "" );
        $pathSpec->file = $cadj->readEntry( "lib_file", "" );
    }
    else
    {
        return FALSE;
    }

    return TRUE;
}

static function writeUsrCalPath( $pathSpec )
{
    $cadj = new ConfigFile();
    if ( $cadj->load( CAL_ADJUST_FILE, false ) )
    {
        $cadj->setPath( "/USRCAL");
        $cadj->writeEntry( "lib_dir", $pathSpec->dir );
        $cadj->writeEntry( "lib_file", $pathSpec->file );
        $cadj->flush();
    }
    else
    {
        return FALSE;
    }

    return TRUE;
}

static function readStdCal( &$stdCal )
{
    $cadj = new ConfigFile();
    if ( $cadj->load( CAL_ADJUST_FILE ) )
    {
        $cadj->setPath( "/STDCAL" );
        $stdCal = $cadj->readEntry( "std_display_list", "" );
    }
    else
    {
        return FALSE;
    }

    return TRUE;
}

static function writeStdCal( $stdCal )
{
    $cadj = new ConfigFile();
    if ( $cadj->load( CAL_ADJUST_FILE, false ) )
    {
        $cadj->setPath( "/STDCAL");
        $cadj->writeEntry( "std_display_list", $stdCal );
        $cadj->flush();
    }
    else
    {
        return FALSE;
    }

    return TRUE;
}

static function getUsrCalLibDirs( $dir, &$dirList )
{
    // Open a known directory and put sub-directory names in dirList 
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                $fileType = filetype($dir . $file);
                if( $fileType == "dir")
                {
                    if( ($file != "..") && ( $file != "." ) )
                    {
                        $dirList[] = $file;
                    }
                }
            }
            closedir($dh);
        }
    }
}

static function getUsrCalLibFiles( $dir, &$fileList  )
{
    // set the extension for files to look for in dir
    $fileExt = $dir . "*.cfg";

    // find all files with a .cfg extension
    foreach (glob( $fileExt ) as $fileName)
    {
        $fileList[] = $fileName;
    }
}

static function extractCalFileName( $filePath )
{
    $tempStr = str_replace( CAL_ADJUST_DIR, "", $filePath );
    $tempStr = str_replace( ".cfg", "", $tempStr );
    return $tempStr;
}

}
