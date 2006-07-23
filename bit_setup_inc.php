<?php
global $gBitSystem,$gBitUser;
$registerHash = array(
	'package_name' => 'tidbits',
	'package_path' => dirname( __FILE__ ).'/',
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitUser->isRegistered() && $gBitSystem->getConfig('tidbits_usermenu') == 'y' ) {

	if (!isset($_SESSION['tidbits_usermenu'])) {
		include_once(TIDBITS_PKG_PATH . 'user_menu_lib.php');

		$user_menus = $usermenulib->list_usermenus($gBitUser->mUserId, 0, -1, 'menu_position_asc', '');
		$gBitSmarty->assign('usr_user_menus', $user_menus['data']);
		$_SESSION['tidbits_usermenu'] = $user_menus['data'];
	} else {
		$user_menus = $_SESSION['tidbits_usermenu'];
		$gBitSmarty->assign('usr_user_menus', $user_menus);
	}
}

?>
