<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tidbits/download_userfile.php,v 1.6 2008/06/19 05:02:41 lsces Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: download_userfile.php,v 1.6 2008/06/19 05:02:41 lsces Exp $
 * @package tidbits
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
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
