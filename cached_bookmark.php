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
require_once( '../kernel/setup_inc.php' );
include_once( TIDBITS_PKG_PATH.'bookmark_lib.php' );

$gBitSystem->verifyFeature( 'tidbits_bookmarks' );

if (!$gBitUser->isRegistered()) {
	$gBitSmarty->assign('msg', tra("You must log in to use this feature"));
	$gBitSystem->display( 'error.tpl' , NULL, array( 'display_mode' => 'display' ));
	die;
}
if (!isset($_REQUEST["urlid"])) {
	$gBitSmarty->assign('msg', tra("No url indicated"));
	$gBitSystem->display( 'error.tpl' , NULL, array( 'display_mode' => 'display' ));
	die;
}
// Get a list of last changes to the Wiki database
$info = $bookmarklib->get_url($_REQUEST["urlid"]);
$gBitSmarty->assign_by_ref('info', $info);
$info["refresh"] = $info["last_updated"];
$gBitSystem->display( 'bitpackage:kernel/view_cache.tpl', NULL, array( 'display_mode' => 'display' ));
?>
