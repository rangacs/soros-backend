<?php


class CfgGroup {
 //------------------------------------------------------------------------------
    // Data
    //------------------------------------------------------------------------------
    var $m_entries;             //!< array of entries within this group (deque in C++)
    var $m_subgroups;           //!< array of subgroups within this group (deque in C++)
    var $m_pParentGrp;          //!< up-link to parent of this group
    var $m_pNextGrp;            //!< horizontal link to next group at same level
    var $m_name;                //!< group's name
    var $m_comment;             //!< leading comment above group [foo] line
    var $m_dirty;               //!< true => at least one subgroup or entry is dirty
    //
    //------------------------------------------------------------------------------
    //! Constructor
    //------------------------------------------------------------------------------
    /**
     * 
     * @param type $parentGrp
     * @param type $nextGrp
     * @param type $name
     */
    function __construct($parentGrp,$nextGrp,$name)
    {
        $this->m_pParentGrp = $parentGrp;
        $this->m_pNextGrp   = $nextGrp;
        $this->m_name       = $name;
        $this->m_dirty      = false;
        $this->m_entries    = array();
        $this->m_subgroups  = array();
    }

    //------------------------------------------------------------------------------
    // accessors
    //------------------------------------------------------------------------------
    //! Returns next group horizontally
    function next() { return $this->m_pNextGrp; }

    //! Returns parent group
    function parent() { return $this->m_pParentGrp; }

    //! Returns group name
    function name() { return $this->m_name; }

    //! Returns the number of subgroups.
    function getSubgroupCount() { return count($this->m_subgroups); }
    
    //! Returns the number of entries.
    function getEntryCount() { return count($this->m_entries); }
    
    //! Accessor for the leading comment
    function getComment() { return $this->m_comment; }

    //! Sets the comment
    function setComment(
        $cmt                    //!< leading comment string to set
        )
    {
        if ( strlen( $cmt ) > 0 )
            $this->m_comment = $cmt;
    }

    //! Returns the dirty state
    function getDirty() { return $this->m_dirty; }

    //------------------------------------------------------------------------------
    //! Sets the dirty state for this group and its parent chain
    //------------------------------------------------------------------------------
    //! Note that a true dirty flag ripples up to the parents, clear goes down
    //! to the children.
    function dirty(
        $bDirty                 //!< true => mark as dirty, false => reset to clean
        )
    {
        $this->m_dirty = $bDirty;
        if ( $bDirty )
        {
            if ( $this->m_pParentGrp )
                $this->m_pParentGrp->dirty( true );
        }
        else
        {
            // cleaning - iterate through our entries and subgroups, turning off their dirty bits
            reset( $this->m_entries );
            foreach ( $this->m_entries as $ent )
                $ent->dirty( false );
            reset( $this->m_subgroups );
            foreach ( $this->m_subgroups as $grp )
                $grp->dirty( false );
        }
    }

    //! Searches for a subgroup by name.
    function findSubgroup(
        $gname                  //!< Name of subgroup to search for
        )
    {
        reset( $this->m_subgroups );
        foreach( $this->m_subgroups as $grp )
        {
            if ( $grp->name() == $gname )
                return $grp;
        }
        return null;
    }

    //! Searches for a member entry by name
    function findEntry(
        $ename                  //!< Name of entry to search for
        )
    {
        reset( $this->m_entries );
        foreach( $this->m_entries as $ent )
        {
            if ( $ent->name() == $ename )
                return $ent;
        }
        return null;
    }

    //! Adds a subgroup to this group
    function addSubgroup(
        $gname                  //!< Name of subgroup to add.
        )
    {
        $ptmp = new CfgGroup( $this, null, $gname );
        $this->m_subgroups[] = $ptmp;
        return $ptmp;
    }

    //! Adds an entry to this group
    function addEntry(
        $ename                  //!< Name of entry to add
        )
    {
        $ptmp = new CfgEntry( $this, null, $ename );
        $this->m_entries[] = $ptmp;
        return $ptmp;
    }

    //------------------------------------------------------------------------------
    //! Removes a subgroup from this group
    //------------------------------------------------------------------------------
    //! \returns true => group was found and removed; false => not found
    //! \bug Does not ripple dirty bit upwards nor set our own dirty bit
    function deleteSubgroup(
        $gname                  //!< Name of group to be removed
        )
    {
        reset( $this->m_subgroups );
        foreach( $this->m_subgroups as $key => $grp )
        {
            if ( $grp->name() == $name )
            {
                unset( $this->m_subgroups[$key] );
                return true;
            }
        }
        return false;
    }

    //------------------------------------------------------------------------------
    //! Removes an entry from the group
    //------------------------------------------------------------------------------
    //! \returns true => entry was found and removed; false => not found
    //! \bug Does not set our own dirty bit!  (does this actually matter?)
    function deleteEntry(
        $ename                  //!< Name of entry to be removed
        )
    {
        reset( $this->m_entries );
        foreach( $this->m_entries as $key => $ent )
        {
            if( $ent->name() == $ename )
            {
                unset( $this->m_entries[$key] );
                $this->m_pParentGrp->dirty( true );
                return true;
            }
        }
        return false;
    }

    //------------------------------------------------------------------------------
    //! Returns the fully-qualified internal pathname of a group.
    //! Determins the pathname within the group hierarchy.
    //! \returns Full absolute internal pathname of the group, e.g. <tt>/foo/bar/baz</tt>.
    //------------------------------------------------------------------------------
    function fullPath( )
    {
        // top level group is nameless
        if ( $this->m_pParentGrp == null )
            return "";
        // recurse upwards
        $parFullPath = $this->m_pParentGrp->fullPath();
        // if parent is the root, nothing to paste
        if ( strlen( $parFullPath ) == 0 )
            $fullpath = $this->m_name;
        else
            $fullpath = $parFullPath . PATH_SEP_STR . $this->m_name;
        return $fullpath;
    }

    //------------------------------------------------------------------------------
    //! Flushes members of this group to a specified output string
    //------------------------------------------------------------------------------
    function flushToString(
        &$ostr,                 //!< output string to which group members are written (IN/OUT)
        $flushAll               //!< false => only write dirty items, true => ignore dirty bits and write everything (IN)
        )
    {
        $writeHdr = true;
        // calculate header name with [ ]
        $fullpath = $this->fullPath();
        $hdrstring = "";
        if ( strlen( $fullpath ) != 0 )
        {
            // not root group
            $hdrstring .= "[" . $fullpath . "]";
        }

        // iterate all our entries and process them
        foreach( $this->m_entries as $ite )
        {
            if ( ( $flushAll || $ite->getDirty()) && strlen( $ite->getValue() ) != 0 )
            {
                if ( $writeHdr )
                {
                    if ( ! empty( $this->m_comment ) )
                        $ostr .= $this->m_comment;
                    $ostr .= $hdrstring . "\n";
                    $writeHdr = false;
                }
            }
            $filtVal = HeliosUtility::enquoteStr( $ite->getValue() );
            DEBUGMSG( "Flushing value " . $ite->getValue() . " enquoted to " . $filtVal . "\n" );
            $s = $ite->getComment();
            if ( ! empty( $s ) )
                $ostr .= $s;
            $ostr .= $ite->name() . " = " . $filtVal;
            $s = $ite->getTrailingComment();
            if ( ! empty( $s ) )
                $ostr .= $s;
            $ostr .= "\n";
            $ite->dirty( false );
        }
        // recursively flush subgroups
        foreach( $this->m_subgroups as $subgrp )
        {
            $subgrp->flushToString( $ostr, $flushAll );
        }
    }

    



}// class CfgGroup

