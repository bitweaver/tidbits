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
class UserFilesLib extends BitBase {
	function UserFilesLib() {
		BitBase::BitBase();
	}
	function userfiles_quota($user) {
		global $gBitUser;
		if ($gBitUser->isAdmin()) {
			return 0;
		}
		return $this->mDb->getOne("select sum(`filesize`) from `".BIT_DB_PREFIX."tidbits_userfiles` where `user_id`=?",array($user));
	}
	function upload_userfile($user, $name, $filename, $filetype, $filesize, $data, $path) {
		global $gBitSystem;
		$now = $gBitSystem->getUTCTime();
		$query = "insert into `".BIT_DB_PREFIX."tidbits_userfiles`(`user_id`,`name`,`filename`,`filetype`,`filesize`,`data`,`created`,`hits`,`path`)
    values(?,?,?,?,?,?,?,?,?)";
		$this->mDb->query($query,array($user,$name,$filename,$filetype,(int) $filesize,$this->dbByteEncode($data),(int) $now,0,$path));
	}
	function list_userfiles($user, $offset, $max_records, $sort_mode, $find) {
		if ($find) {
			$findesc = '%' . strtoupper( $find ). '%';
			$mid = " and (UPPER(`filename`) like ?)";
			$bindvars=array($user,$findesc);
		} else {
			$mid = " ";
			$bindvars=array($user);
		}
		$query = "select `file_id`,`user_id`,`name`,`filename`,`filetype`,`filesize`,`created`,`hits` from `".BIT_DB_PREFIX."tidbits_userfiles` where `user_id`=? $mid order by ".$this->mDb->convertSortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tidbits_userfiles` where `user_id`=? $mid";
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
	function get_userfile($user, $file_id) {
		$query = "select * from `".BIT_DB_PREFIX."tidbits_userfiles` where `user_id`=? and `file_id`=?";
		$result = $this->mDb->query($query,array($user,(int) $file_id));
		$res = $result->fetchRow();
		$res['data'] = $this->dbByteDecode( $res['data'] );
		return $res;
	}
	function remove_userfile($user, $file_id) {
		global $tidbits_userfiles_use_dir;
		$path = $this->mDb->getOne("select `path` from `".BIT_DB_PREFIX."tidbits_userfiles` where `user_id`=? and `file_id`=?",array($user,(int) $file_id));
		if ($path) {
			@unlink ($tidbits_userfiles_use_dir . $path);
		}
		$query = "delete from `".BIT_DB_PREFIX."tidbits_userfiles` where `user_id`=? and file_id=?";
		$this->mDb->query($query,array($user,(int) $file_id));
	}
}
$userfileslib = new UserFilesLib();
?>
