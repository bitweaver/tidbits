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
 * @subpackage modules
 */

/**
 * required setup
 */
require_once(USERS_PKG_PATH."task_lib.php");
global $gBitUser, $gBitSystem, $tasklib;

if ($gBitUser->getUserId() > 0 && $gBitSystem->isFeatureActive('feature_tasks') && $gBitUser->hasPermission( 'p_tidbits_use_tasks' )) {
	if (isset($_SESSION['thedate'])) {
		$pdate = $_SESSION['thedate'];
	} else {
		$pdate = $gBitSystem->getUTCTime();	
	}
	if (isset($_REQUEST["modTasksDel"])) {
		foreach (array_keys($_REQUEST["modTasks"])as $task) {
			$tasklib->remove_task($gBitUser->getUserId(), $task);
		}
	}
	if (isset($_REQUEST["modTasksCom"])) {
		foreach (array_keys($_REQUEST["modTasks"])as $task) {
			$tasklib->complete_task($gBitUser->getUserId(), $task);
		}
	}
	if (isset($_REQUEST["modTasksSave"])) {
		$tasklib->replace_task($gBitUser->getUserId(), 0, $_REQUEST['modTasksTitle'], $_REQUEST['modTasksTitle'], $gBitSystem->getUTCTime(), 'o', 3, 0, 0);
	}
	$ownurl =/*httpPrefix().*/ $_SERVER["REQUEST_URI"];
	$_template->tpl_vars['ownurl'] = new Smarty_variable( $ownurl);
	$tasks_use_dates = $gBitUser->getPreference('tasks_use_dates');
	$modTasks = $tasklib->list_tasks($gBitUser->getUserId(), 0, -1, 'priority_desc', '', $tasks_use_dates, $pdate);
	$_template->tpl_vars['modTasks'] = new Smarty_variable( $modTasks['data']);
}
?>
