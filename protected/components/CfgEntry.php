<?php
class CfgEntry {
    
    
    
    // Data
    var $m_parentGroup;         //!< parent group of this entry
    var $m_nextEntry;           //!< next entry in linked list
    var $m_name;                //!< name of this entry
    var $m_value;               //!< value of this entry (all stored as strings)
    var $m_expandedVal;         //!< value of this entry with macros/env expanded
    var $m_comment;             //!< associated prefix comment string
    var $m_trailComment;        //!< associated trailing comment (on same line with value)
    var $m_dirty;               //!< true if modified since most recent read
    var $m_localEntry;          //!< true if local, false => global
    var $m_localLock;           //!< true if can't be overridden locally

    /**
     * 
     * @param type $parentGroup Parent group
     * @param type $nextEntry   Next entry in the group
     * @param type $entryName   Name of the entry
     */
    function __construct($parentGroup, $nextEntry,$entryName)
    {
        $this->m_parentGroup = $parentGroup;
        $this->m_nextEntry = $nextEntry;
        $this->m_dirty = false;
        $this->m_localEntry = false;
        $this->m_expandedVal = "";
        $this->m_value = "";
        $this->m_comment = "";
        $this->m_trailComment = "";
        // check for lock prefix
        if ( substr( $entryName, 0, 1 ) == LOCAL_LOCK_PREFIX )
        {
            $this->m_localLock = true;
            $this->m_name = substr( $entryName, 1 ); // clip prefix from input string
        }
        else {
            $this->m_localLock = false;
            $this->m_name = $entryName;
        }
    }

    // Accessors - note that we can't overload the set/get names like in C++

    //! Accessor for the name
    function name() { return $this->m_name; }

    //! Accessor for the value
    function getValue() { return $this->m_value; }
    
    //! Accessor for the leading comment
    function getComment() { return $this->m_comment; }

    //! Sets the comment
    function setComment(
        $cmt                    //!< leading comment string to set
        )
    {
        // This is preserved from C++ version where there are re-entrancy concerns
        if ( strlen( $this->m_comment != 0 ) )
            die( "double assignment to comment part of CfgEntry!" );
        $this->m_comment = $cmt;
    }

    //! Accessor for the trailing comment
    function getTrailingComment( ) { return $this->m_trailComment; }

    //! Sets the trailing comment
    function setTrailingComment(
        $cmt                    //!< trailing comment string to set
        )
    {
        // This is preserved from C++ version where there are re-entrancy concerns
        if ( strlen( $this->m_trailComment != 0 ) )
            die( "double assignment to trailing comment part of CfgEntry!" );
        $this->m_trailComment = $cmt;
    }

    //! Accessor for the env/macro expanded val.  Currently just returns the regular value.
    function expandedVal( ) {
        if ( strlen( $this->m_expandedVal ) == 0 )
            $this->m_expandedVal = $this->m_value;
        return $this->m_expandedVal;
    }
    
    //! Accessor for the dirty bit
    function getDirty() { return $this->m_dirty; }

    //! Accessor for the next-entry link ref
    function nextEntry() { return $this->m_nextEntry; }

    //! Sets the next-entry link
    function next(
        &$nextEntry              //!< Ref to the next CfgEntry in the group
        )
    {
        $this->m_nextEntry = $nextEntry;
    }


    //! Marks this CfgEntry as dirty, and also its parent group chain
    function dirty(
        $bDirty                 //!< true => mark as dirty, false => reset to clean
        )
    {
        // locals are always kept dirty
        if ( $this->m_localEntry )
            $this->m_dirty = true;
        else
            $this->m_dirty = $bDirty;
        // splash dirt on the parent group chain too
        //! \bug This will not clean a dirty parent if the arg is false
        if ( $this->m_dirty )
            $this->m_parentGroup->dirty( true );
            
    }

    //------------------------------------------------------------------------------
    //! Stores a value into a CfgEntry object
    //------------------------------------------------------------------------------
    //! For various reasons we expect the input to be a string, and we need to
    //! convert all input types to string.
    //
    function value(
        $val,                   //!< value to be loaded
        $localEntry = true,     //!< true => from local config, false => global/absolute
        $fromFile = false       //!< true => being read from file
        )
    {
        // error if this entry is locked and value has already been set
        if ( ( strlen($this->m_value) != 0) && $this->m_localLock )
            return FALSE;

        $this->m_localEntry = $localEntry;

        // if the new value string contains a comment delimiter, split the strings
        // and separately replace the trailing comment
        $newval = $val;
        $cmtpos = strpos( $newval, COMMENT_DELIMS );
        if ( $cmtpos !== FALSE )
        {
            // back up from cmtpos across whitespace so that
            // trailing whitespace after keyval is included with the comment
            $wspos = $cmtpos - 1;
            while (( $wspos > 0 ) && ctype_space( substr( $newval, $wspos, 1 ) ) )
                $wspos--;
            // we are now pointing at last nonwhitespace char in value string
            $wspos++;
            $trailcmt = substr( $newval, $wspos );
            $newval = substr( $newval, 0, $wspos );
        }
        else if ( strlen( $newval ) >= 1 )
        {
            // remove trailing whitespace from commentless value string
            $wspos = strlen( $newval ) - 1;
            while (( $wspos > 0 ) && ctype_space( substr( $newval, $wspos, 1 ) ) )
                $wspos--;
            $newval = substr( $newval, 0, $wspos+1 );
        }

        // unconditionally replace trailing comment if a new one was specified
        if ( $cmtpos !== FALSE )
        {
            $this->m_trailComment = $trailcmt;
        }

        // if new value same as existing, nothing else to do
        if ( $newval == $this->m_value )
            return TRUE;

        $this->m_value = $newval;
        $this->m_expandedVal = "";  // reset env-expanded value (computed later on rqst)

        if ( ! $this->m_localLock && !$fromFile )
            $this->dirty( true );

        if ( strlen( $this->m_value ) == 0 )
            $this->dirty( false );     // never write back empty values

        return TRUE;

    }//value()

    


}// class CfgEntry
