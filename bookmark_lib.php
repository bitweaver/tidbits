<?php
/**
 * $Header$
 *
 * Lib for user administration, groups and permissions
 * This lib uses pear so the constructor requieres
 * a pear DB object
 
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
class BookmarkLib extends BitBase {
	function BookmarkLib() {
		parent::__construct();
	}
	function get_folder_path($folder_id, $user_id) {
		$path = '';
		$info = $this->get_folder($folder_id, $user_id);
		$path = '<a href='.TIDBITS_PKG_URL.'bookmarks.php?parent_id="' . $info["folder_id"] . '">' . $info["name"] . '</a>';
		while ($info["parent_id"] != 0) {
			$info = $this->get_folder($info["parent_id"], $user_id);
			$path
				= $path = '<a href='.TIDBITS_PKG_URL.'bookmarks.php?parent_id="' . $info["folder_id"] . '">' . $info["name"] . '</a>' . '>' . $path;
		}
		return $path;
	}
	function get_folder($folder_id, $user_id) {
		$query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_bookmarks_folders` WHERE `folder_id`=? AND `user_id`=?";
		$result = $this->mDb->query($query,array($folder_id,$user_id));
		if (!$result->numRows())
			return false;
		$res = $result->fetchRow();
		return $res;
	}
	function get_url($url_id) {
		$query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_bookmarks_urls` WHERE `url_id`=?";
		$result = $this->mDb->query($query,array($url_id));
		if (!$result->numRows())
			return false;
		$res = $result->fetchRow();
		return $res;
	}
	function remove_url($url_id, $user_id) {
		$query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_bookmarks_urls` WHERE `url_id`=? AND `user_id`=?";
		$result = $this->mDb->query($query,array($url_id,$user_id));
		return true;
	}
	function remove_folder($folder_id, $user_id) {
		// Delete the category
		$query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_bookmarks_folders` WHERE `folder_id`=? AND `user_id`=?";
		$result = $this->mDb->query($query,array($folder_id,$user_id));
		// Remove objects for this category
		$query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_bookmarks_urls` WHERE `folder_id`=? AND `user_id`=?";
		$result = $this->mDb->query($query,array($folder_id,$user_id));
		// SUbfolders
		$query = "SELECT `folder_id` FROM `".BIT_DB_PREFIX."tidbits_bookmarks_folders` WHERE `parent_id`=? AND `user_id`=?";
		$result = $this->mDb->query($query,array($folder_id,$user_id));
		while ($res = $result->fetchRow()) {
			// Recursively remove the subcategory
			$this->remove_folder($res["folder_id"], $user_id);
		}
		return true;
	}
	function update_folder($folder_id, $name, $user_id) {
		$query = "UPDATE `".BIT_DB_PREFIX."tidbits_bookmarks_folders` SET `name`=? WHERE `folder_id`=? AND `user_id`=?";
		$result = $this->mDb->query($query,array($name,$folder_id,$user_id));
	}
	function add_folder($parent_id, $name, $user_id) {
		// Don't allow empty/blank folder names.
		if (empty($name))
			return false;
		$query = "INSERT INTO `".BIT_DB_PREFIX."tidbits_bookmarks_folders`(`name`,`parent_id`,`user_id`) VALUES(?,?,?)";
		$result = $this->mDb->query($query,array($name,$parent_id,$user_id));
	}
	function replace_url($url_id, $folder_id, $name, $url, $user_id) {
		$id = NULL;
		if( strlen( $url ) < 250 ) {
			global $gBitSystem;
			$now = $gBitSystem->getUTCTime();
			if ($url_id) {
				$query = "UPDATE `".BIT_DB_PREFIX."tidbits_bookmarks_urls` SET `user_id`=?,`last_updated`=?,`folder_id`=?,`name`=?,`url`=? WHERE `url_id`=?";
				$bindvars=array($user_id,(int) $now,$folder_id,$name,$url,$url_id);
			} else {
				$query = " insert into `".BIT_DB_PREFIX."tidbits_bookmarks_urls`(`name`,`url`,`data`,`last_updated`,`folder_id`,`user_id`)
		  values(?,?,?,?,?,?)";
					$bindvars=array($name,$url,'',(int) $now,$folder_id,$user_id);
			}
			$result = $this->mDb->query($query,$bindvars);
			$id = $this->mDb->getOne("select max(`url_id`) from `".BIT_DB_PREFIX."tidbits_bookmarks_urls` WHERE `url`=? AND `last_updated`=?",array($url,(int) $now));
		}
		return $id;
	}
	function refresh_url($url_id) {
		$info = $this->get_url($url_id);
		if (strstr($info["url"], 'bit_') || strstr($info["url"], 'messages_'))
			return false;
		@$fp = fopen($info["url"], "r");
		if (!$fp)
			return;
		$data = '';
		while (!feof($fp)) {
			$data .= fread($fp, 4096);
		}
		fclose ($fp);
		global $gBitSystem;
		$now = $gBitSystem->getUTCTime();
		$query = "UPDATE `".BIT_DB_PREFIX."tidbits_bookmarks_urls` SET `last_updated`=?, `data`=? WHERE `url_id`=?";
		$result = $this->mDb->query($query,array((int) $now,BitDb::dbByteEncode( $data ),$url_id));
		return true;
	}
	function list_folder($folder_id, $offset, $max_records, $sort_mode = 'name_asc', $find, $user_id) {
		if ($find) {
			$findesc = '%' . strtoupper( $find ) . '%';
			$mid = " AND UPPER(`name`) LIKE ? OR UPPER(`url`) LIKE ?";
			$bindvars=array($folder_id,$user_id,$findesc,$findesc);
		} else {
			$mid = "";
			$bindvars=array($folder_id,$user_id);
		}
		$query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_bookmarks_urls` WHERE `folder_id`=? AND `user_id`=? $mid ORDER BY ".$this->mDb->convertSortmode($sort_mode);
		$query_cant = "SELECT COUNT(*) FROM `".BIT_DB_PREFIX."tidbits_bookmarks_urls` WHERE `folder_id`=? AND `user_id`=? $mid";
		$result = $this->mDb->query($query,$bindvars,$max_records,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();
		while ($res = $result->fetchRow()) {
			$res["datalen"] = strlen($res["data"]);
			$ret[] = $res;
		}
		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}
	function get_child_folders($folder_id, $user_id) {
		$ret = array();
		$query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_bookmarks_folders` WHERE `parent_id`=? AND `user_id`=?";
		$result = $this->mDb->query($query,array($folder_id,$user_id));
		while ($res = $result->fetchRow()) {
			$cant = $this->mDb->getOne("SELECT COUNT(*) FROM `".BIT_DB_PREFIX."tidbits_bookmarks_urls` WHERE `folder_id`=?",array($res["folder_id"]));
			$res["urls"] = $cant;
			$ret[] = $res;
		}
		return $ret;
	}
}
global $bookmarklib;
$bookmarklib = new BookmarkLib();
?>
