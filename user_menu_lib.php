<?php
/**
 * $Header$
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id$
 * @package tidbits
 */

/**
 * @package tidbits
 */
class UserMenuLib extends BitBase {
	function UserMenuLib() {
		BitBase::BitBase();
	}
	function add_bk($user) {
		$query = "SELECT tubu.`name`,`url` FROM `".BIT_DB_PREFIX."tidbits_bookmarks_urls` tubu, `".BIT_DB_PREFIX."tidbits_bookmarks_folders` tubf WHERE tubu.`folder_id`=tubf.`folder_id` AND tubf.`parent_id`=? AND tubu.`user_id`=?";
		$result = $this->mDb->query($query,array(0,$user));
		$start = $this->get_max_position($user) + 1;
		while ($res = $result->fetchRow()) {
			// Check for duplicate URL
			if (!$this->mDb->getOne("SELECT COUNT(*) FROM `".BIT_DB_PREFIX."tidbits_menus` WHERE `url`=?",array($res['url']))) {
				$this->replace_usermenu($user, 0, $res['name'], $res['url'], $start, 'w');
				$start++;
			} else {
			}
		}
		$query = "SELECT tubu.`name`,`url` FROM `".BIT_DB_PREFIX."tidbits_bookmarks_urls` tubu WHERE tubu.`folder_id`=? AND tubu.user=?";
		$result = $this->mDb->query($query,array(0,$user));
		$start = $this->get_max_position($user) + 1;
		while ($res = $result->fetchRow()) {
			// Check for duplicate URL
			if (!$this->mDb->getOne("SELECT COUNT(*) FROM `".BIT_DB_PREFIX."tidbits_menus` WHERE `url`=?",array($res['url']))) {
				$this->replace_usermenu($user, 0, $res['name'], $res['url'], $start, 'w');
				$start++;
			} else {
			}
		}
	}
	function list_usermenus($user, $offset, $max_records, $sort_mode, $find) {
		if ($find) {
			$findesc = '%' . $find . '%';
			$mid = " AND (`name` LIKE ? OR `url` LIKE ?)";
			$bindvars=array($user,$findesc,$findesc);
		} else {
			$mid = " ";
			$bindvars=array($user);
		}
		$query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_menus` WHERE `user_id`=? $mid ORDER BY ".$this->mDb->convertSortmode($sort_mode);
		$query_cant = "SELECT COUNT(*) FROM `".BIT_DB_PREFIX."tidbits_menus` WHERE `user_id`=? $mid";
		$ret = array();
		$cant = 0;
		if( $result = $this->mDb->query($query,$bindvars,$max_records,$offset) ) {
			$cant = $this->mDb->getOne($query_cant,$bindvars);
			while ($res = $result->fetchRow()) {
				$ret[] = $res;
			}
		}
		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}
	function get_usermenu($user, $menu_id) {
		$query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_menus` WHERE `user_id`=? AND `menu_id`=?";
		$result = $this->mDb->query($query,array($user,$menu_id));
		$res = $result->fetchRow();
		return $res;
	}
	function get_max_position($user) {
		return $this->mDb->getOne("SELECT MAX(`menu_position`) FROM `".BIT_DB_PREFIX."tidbits_menus` WHERE `user_id`=?",array($user));
	}
	function replace_usermenu($user, $menu_id, $name, $url, $position, $mode) {
		global $gBitSystem;
		$now = $gBitSystem->getUTCTime();
		if ($menu_id) {
			$query = "UPDATE `".BIT_DB_PREFIX."tidbits_menus` SET `name`=?, `menu_position`=?, `url`=?, `mode`=? WHERE `user_id`=? AND `menu_id`=?";
			$this->mDb->query($query,array($name,$position,$url,$mode,$user,$menu_id));
			return $menu_id;
		} else {
			$query = "INSERT INTO `".BIT_DB_PREFIX."tidbits_menus`(`user_id`,`name`,`url`,`menu_position`,`mode`) VALUES(?,?,?,?,?)";
			$this->mDb->query($query,array($user,$name,$url,$position,$mode));
			$Id = $this->mDb->getOne("SELECT MAX(`menu_id`) FROM `".BIT_DB_PREFIX."tidbits_menus` WHERE `user_id`=? AND `url`=? AND `name`=?",array($user,$url,$name));
			return $Id;
		}
	}
	function remove_usermenu($user, $menu_id) {
		$query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_menus` WHERE `user_id`=? AND `menu_id`=?";
		$this->mDb->query($query,array($user,$menu_id));
	}
}
$usermenulib = new UserMenuLib();
?>
