<?php

//----------------------------------------------------------------------------------------
// ConfigFile - config file management utility class
//----------------------------------------------------------------------------------------
// SABIA Proprietary.
// Copyright (c) 2009 by SABIA, Inc.
//
//! \file
//! Class for handling standard configuration files that look like
//! \verbatim
//! [foo]
//! bar = 23
//! quux = some string
//! [baz]
//! gain = 24.9
//! \endverbatim
//!
//! Intended to be functionally equivalent to the C++ configFile.h class, with some
//! exceptions.  Environment variable expansion is not supported.
//----------------------------------------------------------------------------------------


//! ConfigFile class for managing ini-style configuration files.
//! This is a flattening of ConfigFileBase and ConfigFile from the C++ code.
class ConfigFile {
    
    

    // Data (Base class)
    var $m_bInitDone;           //!< true if object read in OK from external source
    var $m_curPath;             //!< current group path string
    var $m_bExpandVars;         //!< true => expand env vars / macros
    var $m_storeDefaults;       //!< true => store default values
    var $m_pRootGroup;          //!< Root level group
    var $m_pCurGroup;           //!< 'current' group object


    // Data (file specific)
    var $m_fileName;            //!< name of the file
    var $m_fullFileName;        //!< fully qualified filename for msgs
    var $m_lineNum;             //!< line number for msgs
    var $m_parsingLocal;        //!< true if parsing local defs in global/local scheme
    var $m_useSubdir;           //!< true if config is stored in ~/<.appname>/<.appname>
    var $m_bAbsoluteFile;       //!< true if file is stored in absolute location given by m_fileName
    var $m_readOnly;            //!< true if configfile is to be treated as read-only
    var $m_commentBuf;          //!< accumulated comments and ws before the current entry/group

    // Data (new for PHP)
    var $m_errmsg;              //!< error message from parsing methods
    //! Constructor (base)
    public $isDebugEnable = false;
    function __construct( ) {
        $this->m_pRootGroup = null;
        $this->m_pCurGroup = null;
    }

    //! Sets initial state of base vars
    function initVars()
    {
        // base class vars
        $this->m_bInitDone = false;
        $this->m_curPath = "";
        $this->m_bExpandVars = false;
        $this->m_storeDefaults = false;
        // Destructively realloc the root group
        $this->m_pRootGroup = new CfgGroup( null, null, "" );
        $this->m_pCurGroup = $this->m_pRootGroup;

        // file class vars
        $this->m_fileName = "";
        $this->m_fullFileName = "";
        $this->m_lineNum = 0;
        $this->m_parsingLocal = false;
        $this->m_useSubdir = false;
        $this->m_bAbsoluteFile = false;
        $this->m_readOnly = false;
        $this->m_commentBuf = "";

        // new PHP specific vars
        $this->m_errmsg = "";
        
        return true;
    }

    //------------------------------------------------------------------------------
    //! Decomposes path into group list, collapsing '.' and '..' (base class)
    //! Breaks a group path into its constituent groups (separated by PATH_SEP_CHAR)
    //! Removes . and .. entries by:
    //!  \arg   When a ./ component is seen, ignore it
    //!  \arg   When a ../ component is seen, ignore it and delete the previous component
    //!
    //!  \retval  true  Group path is well-formed.
    //!  \retval  false Group path had too many ..'s (component stack underflow)
    //------------------------------------------------------------------------------
    function breakPathIntoGroups(
        $rawpath,               //!< Path to decompose (IN)
        &$comps                 //!< Array of groups in the path (OUT)
        )
    {
        $indx = 0;
        while( $indx < strlen( $rawpath ) )
        {
            // eat leading or surplus PATH_SEP_CHARs
            while( substr( $rawpath, $indx, 1 ) == PATH_SEP_CHAR )
                $indx++;
            if ( $indx == strlen( $rawpath ) )
                break;
            // new component...extract it and see if it's . or ..
            $inxtsep = strpos( $rawpath, PATH_SEP_CHAR, $indx);
            if ( $inxtsep === FALSE )
            {
                $inxtsep = strlen( $rawpath ); // make loop logic work
                $scomp = substr( $rawpath, $indx );
            }
            else
                $scomp = substr( $rawpath, $indx, $inxtsep - $indx );
            if ( $scomp == ".." )
            {
                if ( count( $comps ) > 0 )
                {
                    // kill the last array member
                    unset( $comps[ count( $comps ) - 1 ] );
                }
                else
                {
                    // Too many ..'s in group path!
                    $this->debug("breakPathIntoGroups:: Error - too many ..'s in group path!\n");
                    return false;
                }
            }
            else if ( $scomp != "." )
                $comps[] = $scomp;
            $indx = $inxtsep;
        }
        return true;
    }

    //------------------------------------------------------------------------------
    //! Normalizes group path by resolving all '..' and / in the path
    //------------------------------------------------------------------------------
    //! Collapses all instances of '..' and extra slashes in the config path.
    //! \retval true Success.
    //! \retval false Unable to decompose path into components (malformed path).
    //
    function normPath(
        $basePath,              //!< Base path (IN)
        $relPath,               //!< Relative path from basePath (IN)
        &$outPath               //!< Normalized path (OUT)
        )
    {
        // form the combined base + rel path
        $rawpath = $basePath . PATH_SEP_STR . $relPath;
        // break rawpath into group name components and squash out ..'s
        $comps = array();
        if ( !$this->breakPathIntoGroups( $rawpath, $comps ) )
            return false;

        // reassemble the stack of components into the result string
        // put a path separator before all components except the first
        $outPath = "";
        for ( $i = 0; $i < count( $comps ); $i++ )
        {
            if ( $i != 0 )
                $outPath .= PATH_SEP_STR;
            $outPath .= $comps[$i];
        }
        return true;
    }

    //------------------------------------------------------------------------------
    //! Changes (relatively) group path in m_curPath
    //------------------------------------------------------------------------------
    //! This is a composite of the base and file derived class versions.
    //! Sets the group path, creating parent groups along the way.
    //! \retval true Path setting succeeded.
    //! \retval false Problem parsing new path.
    //
    function changePath(
        $path                   //!< new path to set
        )
    {
        // Set and normalize m_curPath
        if ( strlen( $path ) == 0 )
            $this->m_curPath = "";
        else
        {
            if ( substr( $path, 0, 1 ) == PATH_SEP_CHAR )
                $rslt = $this->normPath( "", substr( $path, 1 ), $this->m_curPath );
            else
                $rslt = $this->normPath( $this->m_curPath, $path, $this->m_curPath );
            if ( !$rslt )
                return false;
        }

        $this->m_pCurGroup = $this->m_pRootGroup;
        // if we're at the root, done
        if ( empty( $this->m_curPath ) )
            return true;

        // March down thru the now normalized path, finding/creating subgroups as we go
        $groups = array();
        if ( !$this->breakPathIntoGroups( $this->m_curPath, $groups ) )
            return false;
        for ( $i = 0; $i < count( $groups ); $i++ )
        {
            $pGroup = $this->m_pCurGroup->findSubgroup( $groups[$i] );
            if ( $pGroup )
                $this->m_pCurGroup = $pGroup;
            else
                $this->m_pCurGroup = $this->m_pCurGroup->addSubgroup( $groups[$i] );
        }
        return true;
    }

    //------------------------------------------------------------------------------
    //! Sets absolute group path
    //------------------------------------------------------------------------------
    //! \retval true Success.
    //! \retval false Malformed path specified.
    function setPath(
        $fpath                  //!< Absolute path to set (IN)
        )
    {
        // first change to root
        if ( ! $this->changePath("") )
            return false;
        return $this->changePath( $fpath );
    }

    //------------------------------------------------------------------------------
    //! Deletes (recursively) the current group if empty.
    //------------------------------------------------------------------------------
    //! The group deletion ripples upward until we hit a nonempty group.
    //! The root group will not be deleted under any circumstances.
    //! \retval true  At least one group was deleted.
    //! \retval false Group is nonempty, or was the root group which cannot be deleted.
    //
    function deleteCurIfEmpty()
    {
        if ( ( count( $this->m_pCurGroup->m_entries ) != 0 ) ||
             ( count( $this->m_pCurGroup->m_subgroups ) != 0 ) )
            return false;
        // refuse to whack the root
        if ( $m_pCurGroup->parent() == null )
            return false;
        $gname = $this->m_pCurGroup->name();
        $this->m_pCurGroup = $this->m_pCurGroup->parent(); // climb the the ladder
        $this->m_pCurGroup->deleteSubgroup( $gname );      // and chop it off below us
        // recurse in case new m_pCurGroup is empty
        $this->deleteCurIfEmpty();
        return true;
    }

    //------------------------------------------------------------------------------
    //! Returns true if specified entry exists under current group
    //------------------------------------------------------------------------------
    function entryExists(
        $key                    //!< Entry name to search for
        )
    {
        $entry = $this->m_pCurGroup->findEntry( $key );
        return ( $entry != null );
    }

    // Entry read/write methods
    // This is where the differences in C++/PHP show up.  In the C++ world we used
    // overloads to handle type conversion to numeric types.  In PHP we don't get
    // to have overloads, but we do have some runtime type checking to use.
    // In this implementation we collapse the C++ overloads into a single routine
    // that figures out what to do based on the type of its args.

    //------------------------------------------------------------------------------
    //! Reads an entry from the current group, with default value support
    //------------------------------------------------------------------------------
    //! This is the primary method used by application code to read values out of
    //! the ConfigFile structure.  Note that the data type of \e defaultVal
    //! determines the data type of the return value of this function.  If no default
    //! value is supplied, the return type is a string and the caller must deal with
    //! any desired type conversion.
    //!
    //! \returns Either the current value of \e key, or \e defaultVal if \e key is
    //! not defined in the current group.
    //
    function readEntry(
        $key,                   //!< Key string of an entry in the current group (IN)
        $defaultVal = ""        //!< Default value to supply if key is not present.  Type determines return type!
        )
    {
		
        $entry = $this->m_pCurGroup->findEntry( $key );
        if ( $entry )
        {
            // already exists...we have to coerce the type we return based on the defaultVal arg
            // The internal storage is always a string
            $vstr = "";
            if ( $this->m_bExpandVars )
                $vstr = $entry->expandedVal();
            else
                $vstr = $entry->getValue();
            // Convert $vstr to have same type (int, double, string) as $defaultVal
            if ( is_float( $defaultVal ) )
                $retval = (double)$vstr;
            else if ( is_int( $defaultVal ) )
                $retval = (integer)$vstr;
            else
                $retval = $vstr;
            return $retval;
        }
        // we're gonna return the default, so no worry about types here
        if ( $this->m_storeDefaults )
            $this->writeEntry( $key, $defaultVal );
		
		
        return $defaultVal;
    }

    //------------------------------------------------------------------------------
    //! Writes an entry to the current group, creating the key if it doesn't exist.
    //------------------------------------------------------------------------------
    //! Note that we forcibly convert the value to a string here.
    //! \retval true Key was successfully written.
    //! \retval false Unable to create the new entry.
    //
    function writeEntry(
        $key,                   //!< Key name to write/create (IN)
        $value                  //!< Value to write, any scalar type.  Cannot be object or array.
        )
    {
        $entry = $this->m_pCurGroup->findEntry( $key );
        if ( ! $entry )
        {
            $entry = $this->m_pCurGroup->addEntry( $key );
        }
        if ( $entry )
        {
            $this->debug( "writeEntry: Writing new value " . strval($value) . " for key " . $key . "\n" );
            $entry->value( strval($value) );   // convert to string
            return true;
        }
        return false;
    }

    //------------------------------------------------------------------------------
    //! Removes the specified entry from the current group
    //------------------------------------------------------------------------------
    function deleteEntry(
        $key                    //!< Name of key to remove (IN)
        )
    {
        $delOK = $this->m_pCurGroup->deleteEntry( $key );
        $this->deleteCurIfEmpty();
        return $delOK;
    }
    
    /**
    //------------------------------------------------------------------------------
    //! Reads in global and (optional) local config files from a specified filename
    //------------------------------------------------------------------------------
    //! For PHP use the default args have been changed to recognize a read-only
    //! absolute file as the most common case, and the order of args is also
    //! changed so the read-only arg is right after the file name
     * 
     * @param type $fname Name of file to open (IN)
     * @param type $readOnly true => treat file as read-only
     * @param type $searchGlobalFirst true => look for global file in /etc/fname.conf (IN)
     * @param type $useSubdir true => look for file in subdir, not ~
     * @param type $absoluteFile true => absolute filename, not relative to $HOME (IN)
     * @return boolean
     */
    function load($fname,$readOnly = true,$searchGlobalFirst = false,$useSubdir = false,$absoluteFile = true )
    {
		 
        $this->initVars();
		
		
        if ( empty( $fname ) )
            return false;
        // absolute is incompatible with searchGlobalFirst and with useSubdir
        if ( $absoluteFile && ( $searchGlobalFirst || $useSubdir ) )
            return false;
        $this->m_useSubdir = $useSubdir;
        $this->m_fileName = $fname;
        $this->m_bAbsoluteFile = $absoluteFile;
        $this->m_readOnly = $readOnly;
		
        // Look for and read in global file /etc/<fname>.conf
        if ( $searchGlobalFirst )
            
        {
            $this->m_fullFileName = $this->getGlobalCfgFileName( $m_fileName );
            $this->debug( "ConfigFile:: reading global file " . $this->m_fullFileName . "\n" );
            // PHP change: we can use file_get_contents optimally
            $str = @file_get_contents( $this->m_fullFileName );
            if ( $str === FALSE )
            {
                $this->debug("ConfigFile:: global file not found\n");
            }
            else
            {
                $this->m_parsingLocal = false;
                $this->m_bInitDone = $this->loadFromString( $str );
                if ( $this->m_bInitDone )
                {
                    $this->debug( "ConfigFile:: global file read OK\n" );
                }
                else
                {
                    $this->debug("ConfigFile:: global file read FAIL\n");
                }
            }
        }
        // read local (or absolute) file, superseding global values
        $str = "";
        if ( $this->m_bAbsoluteFile )
        {
            // absolute file gets created if nonexistent, but we have to allow
            // for read-only file
            $this->m_fullFileName = $this->m_fileName;
            $this->debug( "ConfigFile:: reading absolute  file " . $this->m_fullFileName . "\n" );
            // first try just to read it
            $str = @file_get_contents( $this->m_fullFileName );
            if ( $str === FALSE )
            {
                if ( $this->m_readOnly )
                {
                    $this->debug("ConfigFile:: absolute read-only file not found.\n");
                    return false;
                }
                $this->debug("ConfigFile:: absolute file not found.  Attempting to create...\n");
                // PHP change: use file_put_contents
                $rslt = file_put_contents( $this->m_fullFileName, "" );
                if ( $rslt === FALSE )
                {
                    $this->debug("ConfigFile:: absolute file cannot be created.\n");
                    return false;
                }
            }
            $this->m_parsingLocal = false;
        }
        else
        {
            // local file doesn't get created if nonexistent
            $this->m_fullFileName = $this->getLocalCfgFileName( $this->m_fileName );
            $this->debug("ConfigFile:: reading local file " . $this->m_fullFileName . "\n" );
            $str = @file_get_contents( $this->m_fullFileName );
            if ( $str === FALSE )
            {
                $this->debug("ConfigFile:: local file not found\n");
                return false;
            }
            // This is a slight crock because it causes all the entries read from the
            // file to be marked dirty, so that when the local file is flushed to disk
            // all of its entries will be written back.
            $this->m_parsingLocal = true;
        }
		
        // parse the local/abs file that has already been inhaled into string var
        $this->m_bInitDone = $this->loadFromString( $str );
		
        if ( $this->m_bInitDone )
        {
            $this->debug("ConfigFile:: local/absolute file read OK\n");
        }
        else
        {
            $this->debug("ConfigFile:: local/absolute file read FAIL\n");
			//echo "failed";
        }
		
        $this->m_pCurGroup = $this->m_pRootGroup;
        // put current path at top of hierarchy
        $this->setPath( "" );
		//echo 'befo';
		//exit();
		
        return $this->m_bInitDone;
        
    }//load()

    
    //------------------------------------------------------------------------------
    //! Flushes to local or absolute config file
    //------------------------------------------------------------------------------
    //! Works by calling flushToString on the root group, which recursively
    //! appends all of its subgroups to the string.  The resulting string is then
    //! written out to the file.
    //! \returns true on success or if there is nothing to do, false if root group
    //! is empty or filenames not set.
    //
    function flush(
        $curOnly = false   //!< true => only write local changes (when in global/local scheme).  Ignored for absolute files.
        )
    {
        $this->debug( "Configfile::flush with m_fileName " . $this->m_fileName . " and m_fullFileName " . $this->m_fullFileName . "\n" );
        if ( $this->m_bAbsoluteFile )
            $pRoot = $this->m_pRootGroup;
        else
            $pRoot = ( $curOnly ? $this->m_pCurGroup : $this->m_pRootGroup );
        if ( ($pRoot == null) || empty( $this->m_fileName ) || empty( $this->m_fullFileName ) )
            return FALSE;

        if ( ( ! $this->m_readOnly ) && $pRoot->getDirty() )
        {
            $this->debug( "ConfigFile::flush flushing root group\n" );
            $str = "";
            $bOK = $pRoot->flushToString( $str, $this->m_bAbsoluteFile );

            if ( ! empty( $this->m_commentBuf ) )
                $str .= $this->m_commentBuf;

            if ( file_put_contents( $this->m_fullFileName, $str, LOCK_EX ) === FALSE )
                return FALSE;
        }
        return TRUE;
    }

    
    //------------------------------------------------------------------------------
    //! getGlobalCfgFileName() - return name of a global config file in /etc/\<name\>.conf
    //------------------------------------------------------------------------------
    function getGlobalCfgFileName(
        $fname                  //!< Name of file to find (without /etc/)
        )
    {
        $hasExt = (strpos( $name, "." ) !== false );
        $outbuf = "/etc/" . $fname;
        if ( ! $hasExt )
            $outbuf .= ".conf";
        return $outbuf;
    }

    //------------------------------------------------------------------------------
    //! Returns name of a local cfg file in $HOME/.name
    //! if m_useSubdir == false, returns $HOME/.[fname]\n
    //! if m_useSubdir == true,  returns $HOME/.[fname]/config\n
    //! Uses the $HOME environment var to find the local file
    //------------------------------------------------------------------------------
    //
    function getLocalCfgFileName(
        $fname                  //!< Name of file to find, without path prefix (IN)
        )
    {
        if ( empty( $fname ) )
            return FALSE;
        $pHomeEnv = getenv( "HOME" );
        if ( empty( $pHomeEnv ) )
            $homedir = ".";     // no $HOME, use current dir
        else
            $homedir = $pHomeEnv;
        $outbuf = $homedir . "/." . $fname;
        if ( $this->m_useSubdir )
        {
            // create ~/.name/ if needed
            // :TODO: bah, side effects are bad
            mkdir( $outbuf, 0755 );
            $outbuf .= "/config";
        }
        return $outbuf;
    }

    //------------------------------------------------------------------------------
    //! Accumulate comments (done for local file only)
    //------------------------------------------------------------------------------
    function accumComment(
        $str                    //!< Comment string to append (IN)
        )
    {
        $this->m_commentBuf .= $str . "\n";
    }

    //------------------------------------------------------------------------------
    //! Slurps contents of absolute file, replacing all existing values
    //------------------------------------------------------------------------------
    function readFile(
        $fname                  //!< fully qualified path to file to read (IN)
        )
    {
        $this->initVars();
        $this->m_fileName = $fname;
        $this->m_fullFileName = $fname;
        $this->m_bAbsoluteFile = true;
        $str = @file_get_contents( $this->m_fullFileName );
        if ( $str === FALSE )
            return FALSE;
        $this->m_parsingLocal = true;
        if ( ! $this-loadFromString( $str ) )
            return FALSE;
        $this->m_bInitDone = true;
        $this->pCurGroup = $this->m_pRootGroup;
        $this->setPath( "" );
        return true;
    }

    //------------------------------------------------------------------------------
    //! Parses a string (from file_get_contents) into a populated ConfigFile object
    //------------------------------------------------------------------------------
    function loadFromString(
        &$str,                   //!< complete string with entire config file (IN)
        $pRootGroup = null       //!< base group to load, if not root (IN)
        )
    {
        // set m_pCurGroup to the root specified here, or default to m_pRootGroup
        if ( $pRootGroup != null )
            $this->m_pCurGroup = $pRootGroup;
        else
            $this->m_pCurGroup = $this->m_pRootGroup;
		
        $this->m_lineNum = 1;

        $lines = preg_split( "/\n/", $str, -1, PREG_SPLIT_NO_EMPTY );  // blank lines are tossed
		
        foreach( $lines as $line )
        {
			//echo $line. "<br/>";
            if ( !$this->parseLine( $line ) )
                return false;
        }
		
        return true;
    }

    //------------------------------------------------------------------------------
    //! Parses one line from a config file string
    //------------------------------------------------------------------------------
    function parseLine(
        &$linebuf               //!< line to parse (IN)
        )
    {
		//echo "parseLine ==> <br/>";
        // gulp leading whitespace, check for empty line or comment
        $i = 0;
        while( ctype_space( substr( $linebuf, $i, 1 ) ) && ( $i < strlen( $linebuf ) ))
            $i++;
        // catch blank lines...no foul
        if ( $i == strlen( $linebuf ) )
            return true;
        // accumulate comment lines...they get glued to next entity seen
        if ( substr( $linebuf, $i, 1 ) == COMMENT_DELIMS )
        {
            // entire line is a comment
            $this->accumComment( $linebuf );
            return true;
        }		
		//echo "*";
        // check for group spec in [] or a key/value pair
        if ( substr( $linebuf, $i, 1 ) == '[' ){
            
            $str = substr( $linebuf, $i );
			//echo $str."<br/>";
            return $this->parseGroupName( $str );
        } else {
            
            
            $str = substr( $linebuf, $i );
			//echo $str."<br/>";
            return $this->parseKeyPair( $str);
        }
		//echo "parseLine <==<br/>";
    }

    //! Determines if the given string (which must be a single char) is valid for a group name
    function isGroupNameChar( $c ) {
        return ( ctype_alnum( $c ) || ( strchr("_/-!.*% @#$^&(){}?+<>;:", $c)) );
    }

    //! Determines if the given string (which must be single char) is valid as a key name
    function isKeyNameChar( $c ) {
        return ( ctype_alnum( $c ) || strchr("_/-!.*%", $c));
    }

    //------------------------------------------------------------------------------
    //! Parses a [group] header, inserting it into the hierarchy
    //------------------------------------------------------------------------------
    function parseGroupName(
        &$linebuf               //!< line to parse (IN)
        )
    {
        $i = 1;
        $endpos = strpos( $linebuf, "]" );
        if ( $endpos === FALSE )
        {
            // Hmm, how to collect error messages better...C++ code writes cerr here
            $this->m_errmsg = "Error: no closing ] in group name " . $linebuf . "\n";
            return false;
        }
        $grpName = substr( $linebuf, $i, $endpos-1 );
        //TODO replace with YII::Debuger
        $this->debug( "ConfigFile:: got raw group name: " . $grpName . "\n" );
        // validate legality of group name
        for ( $i = 0; $i < strlen( $grpName ); $i++ )
        {
            if ( ! $this->isGroupNameChar( substr( $grpName, $i, 1 ) ) )
            {
                $this->m_errmsg = "Error: Illegal character in group name: " . $grpName .
                    " at line # " . $this->m_lineNum . "\n";
                return false;
            }
        }
        $absgroup = PATH_SEP_STR . $grpName;

         //TODO replace with YII::Debuger
        $this->debug("ConfigFile:: setPath to " . $absgroup . "\n" );
        $this->setPath( $absgroup );

        // any accumulated comments belong to this group
        if ( !empty( $this->m_commentBuf ) )
        {
            $this->m_pCurGroup->setComment( $this->m_commentBuf );
            $this->m_commentBuf = "";
        }


        // accum any trailing comment for next entity
        $np = $endpos + 1;
        // gulp whitespace
        while( ctype_space( substr( $linebuf, $np, 1 ) ) && ( $np < strlen( $linebuf ) ))
            $np++;
        if ( $np != strlen( $linebuf ) )
        {
            // there's trailing stuff... is it a comment?
            // if so, attach everything after ] as the comment
            if ( substr( $linebuf, $np, 1 ) == "#" )
                $this->accumComment( substr( $linebuf, $endpos+1 ) );
            else
            {
                $this->m_errmsg = "Error: trailing non-comment after group name " . $grpName . "\n";
                return false;
            }
        }
        return true;
    }

    //------------------------------------------------------------------------------
    //! Parses 'name = value' string line
    //------------------------------------------------------------------------------
    function parseKeyPair(
        &$linebuf               //!< line to parse (IN)
        )
    {
		//echo 1;
        // extract key name
        $np = 0;
        while( $this->isKeyNameChar( substr( $linebuf, $np, 1 ) ) && $np < strlen( $linebuf ) )
            $np++;
        if ( $np == 0 )
        {
            $this->m_errmsg = "Error: missing key name at line " . $this->m_lineNum;
            return false;
        }
		//echo 2;
        $keyname = substr( $linebuf, 0, $np );
        //TODO change to Yii::debug
        $this->debug("ConfigFile:: got keyname " . $keyname . "\n" );
		//echo 3;
        // eat whitespace looking for =
        while( ctype_space( substr( $linebuf, $np, 1 ) ) && ( $np < strlen( $linebuf ) ) )
            $np++;
        if ( $np >= strlen( $linebuf ) || substr( $linebuf, $np, 1 ) != '=' )
        {
            $this->m_errmsg = "Error: expected '=' at line number " . $this->m_lineNum . "\n";
            return false;
        }
        $np++;
		//echo 4;
        // eat more whitespace looking for start of value string
        while( ctype_space( substr( $linebuf, $np, 1 ) ) && ( $np < strlen( $linebuf ) ) )
            $np++;
		//echo 5;
        // extract key value ( string may be empty )
        if ( $np >= strlen( $linebuf ) )
            $keyval = "";
        else
            $keyval = substr( $linebuf, $np );
		//echo 6;
        // trim keyval to first comment delim
        $cmtpos = strpos( $keyval, COMMENT_DELIMS );
        $trailcmt = "";
        if ( $cmtpos !== FALSE )
        {
            // back up from cmtpos across whitespace so that
            // trailing whitespace after keyval is included with the comment
            $wspos = $cmtpos - 1;
            while ( $wspos > 0 && ctype_space( substr( $keyval, $wspos ) ) )
                $wspos--;
            // we are now pointing at last nonwhitespace char in value string
            $wspos++;
            $trailcmt = substr( $keyval, $wspos );
            $keyval = substr( $keyval, 0, $wspos );
        }
        else if ( ! empty( $keyval ) )
        {
            // remove trailing whitespace from commentless value string
            $wspos = strlen( $keyval ) - 1;
            while ( $wspos > 0 && ctype_space( substr( $keyval, $wspos ) ) )
                $wspos--;
            $keyval = substr( $keyval, 0, $wspos+1 );
        }
        //echo 7;
        $this->debug("ConfigFile:: got key value " . $keyval . "\n" );

        // look up keyname in current group, or create new entry
        $pEnt = $this->m_pCurGroup->findEntry( $keyname );
        if ( $pEnt == null )
        {
            $pEnt = $this->m_pCurGroup->addEntry( $keyname );
            $this->debug("ConfigFile:: added new key " . $keyname . " to group " .
            $this->m_pCurGroup->name() . "\n" ); 
        }
        // if we have accumulated comments, attach to this key
        if ( ! empty( $this->m_commentBuf ) )
        {
            $pEnt->setComment( $this->m_commentBuf );
            $this->m_commentBuf = "";
        }
		//echo 8;
        // if comments appear at end of line, accum for next entity
        if ( $cmtpos != FALSE )
            $pEnt->setTrailingComment( $trailcmt );
		//echo 9;
        $unquotedVal = $this->dequoteStr( $keyval );
		
		//echo 10;
        $pEnt->value( $unquotedVal, $this->m_parsingLocal, true /* fromFile */ );
		//echo 11;
        return true;
    }

	function dequoteStr(&$str )
	{
		//echo 343;
		$i = 0;
		$c = substr( $str, $i, 1 );
		$outstr = "";
		// does str start with a double quote?
		$quoted = ( $c == '"' );
		if ( $quoted )
			$i++;
		while ( $i < strlen( $str ) )
		{
			$c = substr( $str, $i, 1 );
			if ( $c == '\\' )
			{
				if ( $i+1 < strlen( $str ) )
				{
					$i++;
					$c = substr( $str, $i, 1 );
					if ( $c == 'n' )
						$outstr .= "\n";
					else if ( $c == 't' )
						$outstr .= "\t";
					else if ( $c == '\\' )
						$outstr .= "\\";
					else
					{
						$outstr .= $c;
					}
				}
			}
			else if ( $c == '"' )
			{
				if ( $quoted )
				{
					if ( $i+1 != strlen( $str ) )
					{
						// Hmmm how to report error??
					}
					break;
				}
				$outstr .= $c;
			}
			else
			{
				$outstr .= $c;
			}
			$i++;
		}
		return $outstr;
	}	

    //------------------------------------------------------------------------------
    //! Finds subgroups matching the given pattern.
    //------------------------------------------------------------------------------
    //! Searches the subgroups of the current group for ones matching the given
    //! PCRE pattern, returning an array of the matching groups.
    //! \returns Array of matching subgroup names, which may be empty.
    //
    function findMatchingSubgroups(
        $patt                   //!< Pattern to match (PCRE)
        )
    {
        $rslt = array();
        $grp = $this->m_pCurGroup;
        if ( $grp != null )
        {
            foreach( $grp->m_subgroups as $subg )
            {
                if ( preg_match( $patt, $subg->name() ) != 0 )
                    $rslt[] = $subg->name();
            }
        }
        return $rslt;
    }

    //------------------------------------------------------------------------------
    //! Finds all keys in the current group matching the given pattern.
    //------------------------------------------------------------------------------
    //! Searches the entries of the current group for ones matching the given
    //! PCRE pattern, returning an array of the matching entry keys.
    //! \returns Array of matching entry names, which may be empty.
    //
    function findMatchingKeys(
        $patt                   //!< Pattern to match (PCRE)
        )
    {
        $rslt = array();
        $grp = $this->m_pCurGroup;
        if ( $grp != null )
        {
            foreach( $grp->m_entries as $ent )
            {
                if ( preg_match( $patt, $ent->name() ) != 0 )
                    $rslt[] = $ent->name();
            }
        }
        return $rslt;
    }

    
    function debug($msg){
        
        if($this->isDebugEnable)
            echo $msg."Echo <br>";
    }
    
}// class ConfigFile



?>
