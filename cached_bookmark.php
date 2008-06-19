<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tidbits/cached_bookmark.php,v 1.5 2008/06/19 05:02:41 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: cached_bookmark.php,v 1.5 2008/06/19 05:02:41 lsces Exp $
 * @package tidbits
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( TIDBITS_PKG_PATH.'bookmark_lib.php' );

$gBitSystem->verifyFeature( 'tidbits_bookmarks' );

if (!$gBitUser->isRegistered()) {
	$gBitSmarty->assign('msg', tra("You must log in to use this feature"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
if (!isset($_REQUEST["urlid"])) {
	$gBitSmarty->assign('msg', tra("No url indicated"));
	$gBitSystem->display( 'error.tpl' );
	die;
}
// Get a list of last changes to the Wiki database
$info = $bookmarklib->get_url($_REQUEST["urlid"]);
$gBitSmarty->assign_by_ref('info', $info);
$info["refresh"] = $info["last_updated"];
$gBitSystem->display( 'bitpackage:kernel/view_cache.tpl');
?>
