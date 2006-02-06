<?php
	$gBitSystem->registerPackage( 'tidbits', dirname( __FILE__).'/');


	if( $gBitUser->isRegistered() && $gBitSystem->getPreference('usermenu') == 'y' ) {

		if (!isset($_SESSION['usermenu'])) {
			include_once(TIDBITS_PKG_PATH . 'user_menu_lib.php');

			$user_menus = $usermenulib->list_usermenus($gBitUser->mUserId, 0, -1, 'position_asc', '');
			$gBitSmarty->assign('usr_user_menus', $user_menus['data']);
			$_SESSION['usermenu'] = $user_menus['data'];
		} else {
			$user_menus = $_SESSION['usermenu'];
			$gBitSmarty->assign('usr_user_menus', $user_menus);
		}
	}

?>
