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
 * Task properties:
 *  user, task_id, title, description, taks_date, status, priority, completed, percentage
 * @package tidbits
 */
class TaskLib extends BitBase {

	function get_task( $pUserId,  $task_id) {
		$query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_tasks` WHERE `user_id`=? AND `task_id`=?";
		$result = $this->mDb->query($query,array( $pUserId, (int)$task_id));
		$res = $result->fetchRow();
		return $res;
	}

	function update_task_percentage( $pUserId,  $task_id, $perc) {
		$query = "UPDATE `".BIT_DB_PREFIX."tidbits_tasks` SET `percentage`=? WHERE `user_ AND `task_id`=?";
		$this->mDb->query($query,array((int)$perc, $pUserId, (int)$task_id));
	}

	function open_task( $pUserId,  $task_id) {
		$query = "UPDATE `".BIT_DB_PREFIX."tidbits_tasks` SET `completed`=?, `status`=?, `percentage`=? WHERE `user_id`=? AND `task_id`=?";
		$this->mDb->query($query, array(0,'o',0, $pUserId, (int)$task_id));
	}

	function replace_task( $pUserId,  $task_id, $title, $description, $date, $status, $priority, $completed, $percentage) {
		if ($task_id != 0) {
			$query = "UPDATE `".BIT_DB_PREFIX."tidbits_tasks` SET `title` = ?, `description` = ?, `task_date` = ?, `status` = ?, `priority` = ?, ";
			$query.= "`percentage` = ?, `completed` = ?  WHERE `user_id`=? AND `task_id`=?";
			$this->mDb->query($query,array($title,$description,$date,$status,$priority,$percentage,$completed, $pUserId, $task_id));
			return $task_id;
		} else {
			$query = "INSERT INTO `".BIT_DB_PREFIX."tidbits_tasks`(`user_id`,`title`,`description`,`task_date`,`status`,`priority`,`completed`,`percentage`) ";
			$query.= " VALUES(?,?,?,?,?,?,?,?)";
			$this->mDb->query($query,array($pUserId,$title,$description,$date,$status,$priority,$completed,$percentage));
			$task_id = $this->mDb->getOne( "SELECT  MAX(`task_id`) FROM `".BIT_DB_PREFIX."tidbits_tasks` WHERE `user_id`=? AND `title`=? AND `task_date`=?",array( $pUserId, $title,$date));
			return $task_id;
		}
	}

	function complete_task( $pUserId,  $task_id) {
		global $gBitSystem;
		$now = $gBitSystem->getUTCTime();
		$query = "UPDATE `".BIT_DB_PREFIX."tidbits_tasks` set `completed`=?, `status`='c', `percentage`=100 WHERE `user_id`=? AND `task_id`=?";
		$this->mDb->query($query,array((int)$now, $pUserId, (int)$task_id));
	}

	function remove_task( $pUserId,  $task_id) {
		$query = "DELETE FROM `".BIT_DB_PREFIX."tidbits_tasks` WHERE `user_id`=? AND `task_id`=?";
		$this->mDb->query($query,array( $pUserId, (int)$task_id));
	}

	function list_tasks( $pUserId,  $offset, $max_records, $sort_mode, $find, $use_date, $pdate) {
		global $gBitSystem;
		$now = $gBitSystem->getUTCTime();
		$bindvars=array($pUserId);
		if ($use_date == 'y') {
			$prio = " AND `task_date`<=? ";
			$bindvars2=$pdate;
		} 
		else {
			$prio = '';
		}

		if ($find) {
			$findesc = '%' . strtoupper( $find ). '%';
			$mid = " AND (UPPER(`title`) like ? or UPPER(`description`) like ?)";
			$bindvars[]=$findesc;
			$bindvars[]=$findesc;
		} else {
			$mid = "" ;
		}

		$mid.=$prio;
		if (isset($bindvars2)) 
			$bindvars[]=$bindvars2;

		$query = "SELECT * FROM `".BIT_DB_PREFIX."tidbits_tasks` WHERE `user_id`=? $mid order by ".$this->mDb->convertSortmode($sort_mode).",`task_id` desc";
		$query_cant = "SELECT COUNT(*) FROM `".BIT_DB_PREFIX."tidbits_tasks` WHERE `user_id`=? $mid";
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

}
global $tasklib;
$tasklib = new TaskLib();
?>
