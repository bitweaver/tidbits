<?php
global $gBitSystem, $gUpgradeFrom, $gUpgradeTo, $gBitDb;

$upgrades = array(

	'BWR1' => array(
		'BWR2' => array(
array( 'DATADICT' => array(
	array( 'RENAMETABLE' => array(
		'tiki_user_menus'             => 'tidbits_menus',
		'tiki_user_tasks'             => 'tidbits_tasks',
		'tiki_user_bookmarks_folders' => 'tidbits_bookmarks_folders',
		'tiki_user_bookmarks_urls'    => 'tidbits_bookmarks_urls',
		'tiki_user_postings'          => 'tidbits_postings',
		'tiki_user_votings'           => 'tidbits_votings',
		'tiki_banning'                => 'tidbits_banning',
		'tiki_banning_sections'       => 'tidbits_banning_sections',
		'tiki_cookies'                => 'tidbits_fortune_cookies',
		'tiki_userpoints'             => 'tidbits_userpoints',
		'tiki_userfiles'              => 'tidbits_userfiles',
	)),
	array( 'RENAMECOLUMN' => array(
		'tidbits_menus' => array(
			'`position`' => '`menu_position` I4',
		),
		'tidbits_tasks' => array(
			'`date`' => '`task_date` I8',
		),
		'tidbits_banning' => array(
			'`user`' => '`ban_user` C(40)',
			'`message`' => '`ban_message` X',
		),
	)),
)),
		)
	),

);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( TIDBITS_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}
?>
