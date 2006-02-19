<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tidbits/user_menu_lib.php,v 1.5 2006/02/19 08:46:05 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: user_menu_lib.php,v 1.5 2006/02/19 08:46:05 lsces Exp $
 * @package users
 */

/**
 * @package users
 * @subpackage UserMenuLib
 */
class UserMenuLib extends BitBase {
	function UserMenuLib() {
		BitBase::BitBase();
	}
	function add_bk($user) {
		$query = "select tubu.`name`,`url` from `".BIT_DB_PREFIX."tidbits_bookmarks_urls` tubu, `".BIT_DB_PREFIX."tidbits_bookmarks_folders` tubf where tubu.`folder_id`=tubf.`folder_id` and tubf.`parent_id`=? and tubu.`user_id`=?";
		$result = $this->mDb->query($query,array(0,$user));
		$start = $this->get_max_position($user) + 1;
		while ($res = $result->fetchRow()) {
			// Check for duplicate URL
			if (!$this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tidbits_menus` where `url`=?",array($res['url']))) {
				$this->replace_usermenu($user, 0, $res['name'], $res['url'], $start, 'w');
				$start++;
			} else {
			}
		}
		$query = "select tubu.`name`,`url` from `".BIT_DB_PREFIX."tidbits_bookmarks_urls` tubu where tubu.`folder_id`=? and tubu.user=?";
		$result = $this->mDb->query($query,array(0,$user));
		$start = $this->get_max_position($user) + 1;
		while ($res = $result->fetchRow()) {
			// Check for duplicate URL
			if (!$this->mDb->getOne("select count(*) from `".BIT_DB_PREFIX."tidbits_menus` where `url`=?",array($res['url']))) {
				$this->replace_usermenu($user, 0, $res['name'], $res['url'], $start, 'w');
				$start++;
			} else {
			}
		}
	}
	function list_usermenus($user, $offset, $max_records, $sort_mode, $find) {
		if ($find) {
			$findesc = '%' . $find . '%';
			$mid = " and (`name` like ? or url like ?)";
			$bindvars=array($user,$findesc,$findesc);
		} else {
			$mid = " ";
			$bindvars=array($user);
		}
		$query = "select * from `".BIT_DB_PREFIX."tidbits_menus` where `user_id`=? $mid order by ".$this->mDb->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tidbits_menus` where `user_id`=? $mid";
		$result = $this->mDb->query($query,$bindvars,$max_records,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();
		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}
		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}
	function get_usermenu($user, $menu_id) {
		$query = "select * from `".BIT_DB_PREFIX."tidbits_menus` where `user_id`=? and `menu_id`=?";
		$result = $this->mDb->query($query,array($user,$menu_id));
		$res = $result->fetchRow();
		return $res;
	}
	function get_max_position($user) {
		return $this->mDb->getOne("select max(`menu_position`) from `".BIT_DB_PREFIX."tidbits_menus` where `user_id`=?",array($user));
	}
	function replace_usermenu($user, $menu_id, $name, $url, $position, $mode) {
		global $gBitSystem;
		$now = $gBitSystem->getUTCTime();
		if ($menu_id) {
			$query = "update `".BIT_DB_PREFIX."tidbits_menus` set `name`=?, `menu_position`=?, `url`=?, `mode`=? where `user_id`=? and `menu_id`=?";
			$this->mDb->query($query,array($name,$position,$url,$mode,$user,$menu_id));
			return $menu_id;
		} else {
			$query = "insert into `".BIT_DB_PREFIX."tidbits_menus`(`user_id`,`name`,`url`,`menu_position`,`mode`) values(?,?,?,?,?)";
			$this->mDb->query($query,array($user,$name,$url,$position,$mode));
			$Id = $this->mDb->getOne("select max(`menu_id`) from `".BIT_DB_PREFIX."tidbits_menus` where `user_id`=? and `url`=? and `name`=?",array($user,$url,$name));
			return $Id;
		}
	}
	function remove_usermenu($user, $menu_id) {
		$query = "delete from `".BIT_DB_PREFIX."tidbits_menus` where `user_id`=? and `menu_id`=?";
		$this->mDb->query($query,array($user,$menu_id));
	}
}
$usermenulib = new UserMenuLib();
?>
