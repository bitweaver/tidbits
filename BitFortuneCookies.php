<?php
/**
 * Tagline Management Library
 *
 * @package tidbits
 * @version $Header: /cvsroot/bitweaver/_bit_tidbits/BitFortuneCookies.php,v 1.7 2009/10/01 13:45:50 wjames5 Exp $
 * @author awcolley
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 */

/**
 * A library used to store taglines used for Message of the day and other randomly select texts.
 *
 * Currently used for cookies.
 *
 * created 2003/06/19
 *
 * @package tidbits
 * @todo does not need to inherit BitBase class. Should hold a BitDb connection as a
 * global variable. 
 */
class TagLineLib extends BitBase
{
    /**
    * Stores and retrieves taglines.
    */
    function TagLineLib()
    {
        BitBase::BitBase();
    }
    /**
    * Lists stored taglines.
    * @param offset the location to begin listing from
    * @param max_records the maximum number of records returned
    * @param sort_mode the method of sorting used in the listing
    * @param find text used to filter listing
    * @return array of taglines
    */
    function list_cookies($offset, $max_records, $sort_mode, $find)
    {
        if ($find)
        {
            $mid = " where (UPPER(`fortune`) like ?)";
            $bindvars = array('%' . strtoupper( $find ) . '%');
        }
        else
        {
            $mid = "";
            $bindvars = array();
        }
        $query = "select * from `".BIT_DB_PREFIX."tidbits_fortune_cookies` $mid order by ".$this->mDb->convertSortmode($sort_mode);
        $query_cant = "select count(*) from `".BIT_DB_PREFIX."tidbits_fortune_cookies` $mid";
        $result = $this->mDb->query($query,$bindvars,$max_records,$offset);
        $cant = $this->mDb->getOne($query_cant,$bindvars);
        $ret = array();
        while ($res = $result->fetchRow())
        {
            $ret[] = $res;
        }
        $retval = array();
        $retval["data"] = $ret;
        $retval["cant"] = $cant;
        return $retval;
    }
    /**
    * Replace a tagline
    * @todo This doesn't look like it works correctly - wolff_borg
    *
    * @param cookieId tagline unqiue identifier
    * @param fortune text of tagline
    */
    function replace_cookie($cookieId, $cookie)
    {
        // $cookie = addslashes($cookie);
        // Check the name if ($cookieId)
        if($cookieId) {
            $query = "UPDATE `".BIT_DB_PREFIX."tidbits_fortune_cookies` SET `fortune`=? WHERE `cookieId`=?";
            $bindvars = array($cookie,(int)$cookieId);
        }
        else
        {
            $bindvars = array($cookie);
            $query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_fortune_cookies` WHERE `fortune`=?";
            $result = $this->mDb->query($query,$bindvars);
            $query = "INSERT INTO `".BIT_DB_PREFIX."tidbits_fortune_cookies`(`fortune`) VALUES(?)";
        }
        $result = $this->mDb->query($query,$bindvars);
        return true;
    }
    /**
    * Removes a tagline by unqiue identifier
    * @param cookieId tagline unqiue identifier
    */
    function remove_cookie($cookieId)
    {
        $query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_fortune_cookies` WHERE `cookieId`=?";
        $result = $this->mDb->query($query,array((int)$cookieId));
        return true;
    }
    /**
    * Retrieves the tagline by unique identifier
    * @param cookieId tagline unqiue identifier
    * @return array of tagline row information
    */
    function get_cookie($cookieId)
    {
        $query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_fortune_cookies` WHERE `cookieId`=?";
        $result = $this->mDb->query($query,array((int)$cookieId));
        if (!$result->numRows())   return false;
        $res = $result->fetchRow();
        return $res;
    }
    /**
    * Removes all stored taglines
    */
    function remove_all_cookies()
    {
        $query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_fortune_cookies`";
        $result = $this->mDb->query($query,array());
    }
	/*shared*/
	function pick_cookie() {
		$cant = $this->mDb->getOne("SELECT COUNT(*) FROM `".BIT_DB_PREFIX."tidbits_fortune_cookies`",array());
		if (!$cant) return '';

		$bid = rand(0, $cant - 1);
		//$cookie = $this->mDb->getOne("SELECT `fortune` FROM `".BIT_DB_PREFIX."tidbits_fortune_cookies` limit $bid,1"); getOne seems not to work with limit
		$result = $this->mDb->query("SELECT `fortune` FROM `".BIT_DB_PREFIX."tidbits_fortune_cookies`",array(),1,$bid);
		if ($res = $result->fetchRow()) {
			$cookie = str_replace("\n", "", $res['fortune']);
			return '<em>"'.$cookie.'"</em>';
		} else {
			return "";
		}
	}

}

/**
 * @global TagLineLib fortune manangement library
 */
global $taglinelib;
$taglinelib = new TagLineLib();
?>
