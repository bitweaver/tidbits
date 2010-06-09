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
include_once (TIDBITS_PKG_PATH.'userfiles_lib.php');
if (!isset($_REQUEST["file_id"])) {
	die;
}
$users_uf_use_db = $gBitSystem->getConfig('users_uf_use_db', 'y');
$tidbits_userfiles_use_dir = $gBitSystem->getConfig('tidbits_userfiles_use_dir', '');
$info = $userfileslib->get_userfile($gBitUser->mUserId, $_REQUEST["file_id"]);
$type = &$info["filetype"];
$file = &$info["filename"];
$content = &$info["data"];
header ("Content-type: $type");
header ("Content-Disposition: inline; filename=\"$file\"");
if ($info["path"]) {
	readfile ($tidbits_userfiles_use_dir . $info["path"]);
} else {
	echo "$content";
}
?>
