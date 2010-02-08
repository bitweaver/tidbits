<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tidbits/bookmarks.php,v 1.10 2010/02/08 21:27:26 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: bookmarks.php,v 1.10 2010/02/08 21:27:26 wjames5 Exp $
 * @package tidbits
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );
require_once( TIDBITS_PKG_PATH.'bookmark_lib.php' );

$gBitSystem->verifyFeature( 'tidbits_bookmarks' );
$gBitSystem->verifyPermission( 'p_tidbits_create_bookmarks' );

if (!isset($_REQUEST["parent_id"])) {
	$_REQUEST["parent_id"] = 0;
}
if ($_REQUEST["parent_id"]) {
	$path = $bookmarklib->get_folder_path($_REQUEST["parent_id"], $gBitUser->mUserId);
	$p_info = $bookmarklib->get_folder($_REQUEST["parent_id"], $gBitUser->mUserId);
	$father = $p_info["parent_id"];
} else {
	$path = tra("TOP");
	$father = 0;
}
$gBitSmarty->assign('parent_id', $_REQUEST["parent_id"]);
$gBitSmarty->assign('path', $path);
//chekck for edit folder
if (isset($_REQUEST["editfolder"])) {
	$folder_info = $bookmarklib->get_folder($_REQUEST["editfolder"], $gBitUser->mUserId);
} else {
	$folder_info["name"] = '';
	$_REQUEST["editfolder"] = 0;
}
$gBitSmarty->assign('foldername', $folder_info["name"]);
$gBitSmarty->assign('editfolder', $_REQUEST["editfolder"]);
if (isset($_REQUEST["editurl"])) {
	$url_info = $bookmarklib->get_url($_REQUEST["editurl"]);
} else {
	$url_info["name"] = '';
	$url_info["url"] = '';
	$_REQUEST["editurl"] = 0;
}
$gBitSmarty->assign('urlname', $url_info["name"]);
$gBitSmarty->assign('urlurl', $url_info["url"]);
$gBitSmarty->assign('editurl', $_REQUEST["editurl"]);
// Create a folder inside the parentFolder here
if (isset($_REQUEST["addfolder"])) {
	
	if ($_REQUEST["editfolder"]) {
		$bookmarklib->update_folder($_REQUEST["editfolder"], $_REQUEST["foldername"], $gBitUser->mUserId);
		$gBitSmarty->assign('editfolder', 0);
		$gBitSmarty->assign('foldername', '');
	} else {
		$bookmarklib->add_folder($_REQUEST["parent_id"], $_REQUEST["foldername"], $gBitUser->mUserId);
	}
}
if (isset($_REQUEST["removefolder"])) {
	
	$bookmarklib->remove_folder($_REQUEST["removefolder"], $gBitUser->mUserId);
}
if (isset($_REQUEST["refreshurl"])) {
	
	$bookmarklib->refresh_url($_REQUEST["refreshurl"]);
}
if (isset($_REQUEST["addurl"])) {
	
	if( $urlid = $bookmarklib->replace_url($_REQUEST["editurl"], $_REQUEST["parent_id"], $_REQUEST["urlname"], $_REQUEST["urlurl"], $gBitUser->mUserId) ) {
		if ($_REQUEST["editurl"] == 0 && $gBitUser->hasPermission( 'p_tidbits_cache_bookmarks' )) {
			$bookmarklib->refresh_url($urlid);
		}
		$gBitSmarty->assign('editurl', 0);
		$gBitSmarty->assign('urlname', '');
		$gBitSmarty->assign('urlurl', '');
	} else {
		$gBitSmarty->assign( 'bookmarkError', "URL CANNOT BE MORE THAN 250 characters" );
	}
}
if (isset($_REQUEST["removeurl"])) {
	
	$bookmarklib->remove_url($_REQUEST["removeurl"], $gBitUser->mUserId);
}
$urls = $bookmarklib->list_folder($_REQUEST["parent_id"], 0, -1, 'name_asc', '', $gBitUser->mUserId);
$gBitSmarty->assign('urls', $urls["data"]);
$folders = $bookmarklib->get_child_folders($_REQUEST["parent_id"], $gBitUser->mUserId);
$pf = array(
	"name" => "..",
	"folder_id" => $father,
	"parent_id" => 0,
	"user_id" => $gBitUser->mUserId
);
$pfs = array($pf);
if ($_REQUEST["parent_id"]) {
	$folders = array_merge($pfs, $folders);
}
$gBitSmarty->assign('folders', $folders);

// Display the template
$gBitSystem->display( 'bitpackage:tidbits/tidbits_bookmarks.tpl', NULL, array( 'display_mode' => 'display' ));
?>
