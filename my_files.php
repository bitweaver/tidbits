<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tidbits/my_files.php,v 1.1 2006/01/31 16:59:55 bitweaver Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: my_files.php,v 1.1 2006/01/31 16:59:55 bitweaver Exp $
 * @package users
 * @subpackage functions
 */

/**
 * required setup
 */
require_once("../bit_setup_inc.php");
global $gBitSystem;

if (!$gBitUser->mUserId) {
	$gBitSmarty->assign('msg', tra("You are not logged in"));
	$gBitSystem->display( 'error.tpl' );
	die;
}

$userFiles = $gBitUser->getUserFiles();
$gBitSmarty->assign_by_ref('userFiles', $userFiles['files']);
$gBitSmarty->assign('numUserFiles', count($userFiles['files']));
$gBitSmarty->assign('diskUsage', $userFiles['diskUsage']);

if (!empty($_REQUEST['deleteAttachment'])) {
	$attachmentId = $_REQUEST['deleteAttachment'];
}

$gBitSystem->display('bitpackage:profile/my_files.tpl');
?>
