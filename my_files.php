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
 * @subpackage functions
 */

/**
 * required setup
 */
require_once("../kernel/setup_inc.php");
global $gBitSystem;

if (!$gBitUser->mUserId) {
	$gBitSmarty->assign('msg', tra("You are not logged in"));
	$gBitSystem->display( 'error.tpl' , NULL, array( 'display_mode' => 'display' ));
	die;
}

$userFiles = $gBitUser->getUserFiles();
$gBitSmarty->assign_by_ref('userFiles', $userFiles['files']);
$gBitSmarty->assign('numUserFiles', count($userFiles['files']));
$gBitSmarty->assign('diskUsage', $userFiles['diskUsage']);

if (!empty($_REQUEST['deleteAttachment'])) {
	$attachmentId = $_REQUEST['deleteAttachment'];
}

$gBitSystem->display('bitpackage:tidbits/my_files.tpl', NULL, array( 'display_mode' => 'display' ));
?>
