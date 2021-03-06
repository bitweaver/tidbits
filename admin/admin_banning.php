<?php

// $Header$

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
require_once( '../../kernel/setup_inc.php' );

include_once (TIDBITS_PKG_PATH.'ban_lib.php');

$gBitSystem->verifyFeature( 'tidbits_banning' );
$gBitSystem->verifyPermission( 'p_tidbits_admin_banning' );


if (isset($_REQUEST['ban_id'])) {
	$info = $banlib->get_rule($_REQUEST['ban_id']);
} else {
	$_REQUEST['ban_id'] = 0;

	$info['packages'] = array();
	$info['title'] = '';
	$info['mode'] = 'user';
	$info['ip1'] = 255;
	$info['ip2'] = 255;
	$info['ip3'] = 255;
	$info['ip4'] = 255;
	$info['use_dates'] = 'n';
	$info['date_from'] = $gBitSystem->getUTCTime();
	$info['date_to'] = $info['date_from'] + 7 * 24 * 3600;
	$info['ban_message'] = '';
}

$gBitSmarty->assign('ban_id', $_REQUEST['ban_id']);
$gBitSmarty->assign_by_ref('info', $info);

if (isset($_REQUEST['remove'])) {
	
	$banlib->remove_rule($_REQUEST['remove']);
}

if (isset($_REQUEST['del']) && isset($_REQUEST['delsec'])) {
	
	foreach (array_keys($_REQUEST['delsec'])as $sec) {
		$banlib->remove_rule($sec);
	}
}

if (isset($_REQUEST['save'])) {
	
	$_REQUEST['use_dates'] = isset($_REQUEST['use_dates']) ? 'y' : 'n';

	$_REQUEST['date_from'] = mktime(0, 0, 0, $_REQUEST['date_fromMonth'], $_REQUEST['date_fromDay'], $_REQUEST['date_fromYear']);
	$_REQUEST['date_to'] = mktime(0, 0, 0, $_REQUEST['date_toMonth'], $_REQUEST['date_toDay'], $_REQUEST['date_toYear']);
	$packages = array_keys($_REQUEST['package']);
	$banlib->replace_rule($_REQUEST['ban_id'], $_REQUEST['mode'], $_REQUEST['title'], $_REQUEST['ip1'], $_REQUEST['ip2'],
		$_REQUEST['ip3'], $_REQUEST['ip4'], $_REQUEST['user'], $_REQUEST['date_from'], $_REQUEST['date_to'], $_REQUEST['use_dates'],
		$_REQUEST['ban_message'], $packages);

	$info['packages'] = array();
	$info['title'] = '';
	$info['mode'] = 'user';
	$info['ip1'] = 255;
	$info['ip2'] = 255;
	$info['ip3'] = 255;
	$info['ip4'] = 255;
	$info['use_dates'] = 'n';
	$info['date_from'] = $gBitSystem->getUTCTime();
	$info['date_to'] = $info['date_from'] + 7 * 24 * 3600;
	$info['ban_message'] = '';
	$gBitSmarty->assign_by_ref('info', $info);
}

$where = '';
$wheres = array();
/*
if(isset($_REQUEST['filter'])) {
  if($_REQUEST['filter_name']) {
   $wheres[]=" name='".$_REQUEST['filter_name']."'";
  }
  if($_REQUEST['filter_active']) {
   $wheres[]=" is_active='".$_REQUEST['filter_active']."'";
  }
  $where = implode('and',$wheres);
}
*/
if (isset($_REQUEST['where'])) {
	$where = $_REQUEST['where'];
}

if ( empty( $_REQUEST["sort_mode"] ) ) {
	$sort_mode = 'created_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}

if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}

$gBitSmarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}

$gBitSmarty->assign('find', $find);
$gBitSmarty->assign('where', $where);
$gBitSmarty->assign_by_ref('sort_mode', $sort_mode);
$items = $banlib->list_rules($offset, $max_records, $sort_mode, $find, $where);
$gBitSmarty->assign('cant', $items['cant']);

$cant_pages = ceil($items["cant"] / $max_records);
$gBitSmarty->assign_by_ref('cant_pages', $cant_pages);
$gBitSmarty->assign('actual_page', 1 + ($offset / $max_records));

if ($items["cant"] > ($offset + $max_records)) {
	$gBitSmarty->assign('next_offset', $offset + $max_records);
} else {
	$gBitSmarty->assign('next_offset', -1);
}

if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $max_records);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}

$gBitSmarty->assign_by_ref('items', $items["data"]);

$packages = array(
	'wiki',
	'galleries',
	'file_galleries',
	'cms',
	'blogs',
	'forums',
	'chat',
	'categories',
	'games',
	'faqs',
	'html_pages',
	'quizzes',
	'surveys',
	'webmail',
	'trackers',
	'featured_links',
	'directory',
	'user_messages',
	'newsreader',
	'mybitweaver',
	'workflow',
	'charts'
);

$gBitSmarty->assign('packages', $packages);


$gBitSystem->display( 'bitpackage:tidbits/admin_banning.tpl', NULL, array( 'display_mode' => 'admin' ));

?>
