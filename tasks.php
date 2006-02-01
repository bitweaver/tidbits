<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tidbits/tasks.php,v 1.2 2006/02/01 13:48:50 hash9 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: tasks.php,v 1.2 2006/02/01 13:48:50 hash9 Exp $
 * @package users
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
require_once( TIDBITS_PKG_PATH.'task_lib.php' );

$gBitSystem->verifyFeature( 'feature_tasks' );
$gBitSystem->verifyPermission( 'bit_p_tasks' );

$comp_array = array();
$comp_array_p = array();
for ($i = 0; $i < 101; $i += 10) {
	$comp_array[] = $i;
	$comp_array_p[] = $i . '%';
}
$gBitSmarty->assign('comp_array', $comp_array);
$gBitSmarty->assign('comp_array_p', $comp_array_p);
if (!isset($_REQUEST["task_id"]))
	$_REQUEST["task_id"] = 0;
if (isset($_REQUEST["complete"]) && isset($_REQUEST["task"])) {
	
	foreach (array_keys($_REQUEST["task"])as $task) {
		$tasklib->complete_task($gBitUser->mUserId, $task);
	}
}
if (isset($_REQUEST["open"]) && isset($_REQUEST["task"])) {
	
	foreach (array_keys($_REQUEST["task"])as $task) {
		$tasklib->open_task($gBitUser->mUserId, $task);
	}
}
if (isset($_REQUEST["delete"]) && isset($_REQUEST["task"])) {
	
	foreach (array_keys($_REQUEST["task"])as $task) {
		$tasklib->remove_task($gBitUser->mUserId, $task);
	}
}
if (isset($_REQUEST["update"])) {
	
	foreach ($_REQUEST["task_perc"] as $task => $perc) {
		$tasklib->update_task_percentage($gBitUser->mUserId, $task, $perc);
	}
}
if (isset($_REQUEST["tasks_use_dates"])) {
	$tasks_use_dates = $_REQUEST["tasks_use_dates"];
} else {
	$tasks_use_dates = $gBitUser->getPreference( 'tasks_use_dates' );
}
$tasks_maxRecords = $gBitUser->getPreference('tasks_maxRecords', $maxRecords);
$maxRecords = $tasks_maxRecords;
$gBitSmarty->assign('tasks_use_dates', $tasks_use_dates);
$gBitSmarty->assign('tasks_maxRecords', $tasks_maxRecords);
if ($_REQUEST["task_id"]) {
	$info = $tasklib->get_task($gBitUser->mUserId, $_REQUEST["task_id"]);
} else {
	$info = array();
	$info['title'] = '';
	$info['description'] = '';
	$info['priority'] = 3;
	$info['status'] = 'o';
	$info['date'] = $gBitSystem->getUTCTime();
}
if (isset($_REQUEST['save'])) {
	$date = $gBitSystem->mServerTimestamp->getUTCFromDisplayDate(mktime(0, 0, 0, $_REQUEST["Date_Month"], $_REQUEST["Date_Day"], $_REQUEST["Date_Year"]));
	if ($_REQUEST['status'] == 'c') {
		$_REQUEST['percentage'] = 100;
		$completed = $date;
	} else {
		$completed = 0;
	}
	if ($_REQUEST['percentage'] == 100) {
		$completed = $date;
		$_REQUEST['status'] = 'c';
	} else {
		$_REQUEST['status'] = 'o';
		$completed = 0;
	}
	$tasklib->replace_task($gBitUser->mUserId, $_REQUEST["task_id"], $_REQUEST["title"], $_REQUEST["description"], $date, $_REQUEST['status'], $_REQUEST['priority'], $completed, $_REQUEST['percentage']);
	$info = array();
	$info['title'] = '';
	$info['description'] = '';
	$info['priority'] = 3;
	$info['status'] = 'o';
	$info['date'] = $gBitSystem->getUTCTime();
	$_REQUEST["task_id"] = 0;
}
$gBitSmarty->assign('task_id', $_REQUEST["task_id"]);
$gBitSmarty->assign('info', $info);
$gBitSmarty->assign('Date_Month', date("m", $info['date']));
$gBitSmarty->assign('Date_Day', date("d", $info['date']));
$gBitSmarty->assign('Date_Year', date("Y", $info['date']));
if ( empty( $_REQUEST["sort_mode"] ) ) {
	$sort_mode = 'priority_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $maxRecords;
}
$gBitSmarty->assign_by_ref('offset', $offset);
if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$gBitSmarty->assign('find', $find);
$gBitSmarty->assign_by_ref('sort_mode', $sort_mode);
if (isset($_SESSION['thedate'])) {
	$pdate = $_SESSION['thedate'];
} else {
	$pdate = $gBitSystem->getUTCTime();
}
$tasks = $tasklib->list_tasks($gBitUser->mUserId, $offset, $maxRecords, $sort_mode, $find, $tasks_use_dates, $pdate);
if($maxRecords == 0) {
	$cant_pages = 0;
	$gBitSmarty->assign('actual_page', '1');
} else {
	$cant_pages = ceil($tasks["cant"] / $maxRecords);
	$gBitSmarty->assign('actual_page', 1 + ($offset / $maxRecords));
}
$gBitSmarty->assign_by_ref('cant_pages', $cant_pages);
if ($tasks["cant"] > ($offset + $maxRecords)) {
	$gBitSmarty->assign('next_offset', $offset + $maxRecords);
} else {
	$gBitSmarty->assign('next_offset', -1);
}
// If offset is > 0 then prev_offset
if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $maxRecords);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}
$gBitSmarty->assign_by_ref('tasks', $tasks["data"]);
$gBitSmarty->assign('tasks_use_dates', $tasks_use_dates);
$percs = array();
for ($i = 0; $i <= 100; $i += 10) {
	$percs[] = $i;
}
$gBitSmarty->assign_by_ref('percs', $percs);

$gBitSystem->display( 'bitpackage:tidbits/user_tasks.tpl');
?>
