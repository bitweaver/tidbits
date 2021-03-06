<?php

if ( $gBitSystem->isPackageActive('tidbits')) {
	$formFeaturesTidbits = array(
		'tidbits_tasks' => array(
			'label' => 'User Tasks',
			'note' => 'Make notes of tasks with due date and priority.',
		),
		'tidbits_bookmarks' => array(
			'label' => 'User Bookmarks',
			'note' => 'Users can create their own list of private bookmarks.',
		),
		'tidbits_userfiles' => array(
			'label' => 'User Files',
			'note' => 'Users can upload private user files.',
		),
		'tidbits_usermenu' => array(
			'label' => 'User menu',
			'note' => 'Users can customise their own menus.',
		),
		'tidbits_banning' => array(
			'label' => 'User Banning',
			'note' => 'Allows you to ban users from your site, based on username or IP.',
		),
	);

	if( $gBitSystem->isPackageActive( 'notepad' ) ) {
		$formFeatures['feature_notepad'] = array(
			'label' => 'User Notepad',
			'note' => 'Allow users to make notes.',
			'page' => 'UserNotepad'
		);
	}

	$gBitSmarty->assign( 'formFeatures', $formFeaturesTidbits );

	if( isset( $_REQUEST['settings'] ) ) {
		foreach ( array_keys( $formFeaturesTidbits ) as $feature) {
			$gBitSystem->storeConfig( $feature, (isset( $_REQUEST['settings'][$feature][0] ) ? $_REQUEST['settings'][$feature][0] : 'n'), TIDBITS_PKG_NAME );
		}
	}
}

?>
