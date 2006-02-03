<?php
global $gBitSystem, $gUpgradeFrom, $gUpgradeTo, $gBitDb;

$upgrades = array(

	'BWR1' => array(
		'BWR2' => array(
// de-tikify tables
array( 'DATADICT' => array(
	array( 'RENAMETABLE' => array(
		'tiki_user_bookmarks_urls'    => 'tidbits_user_bookmarks_urls',
		'tiki_user_menus'             => 'tidbits_user_menus',
		'tiki_user_tasks'             => 'tidbits_user_tasks',
		'tiki_user_bookmarks_folders' => 'tidbits_user_bookmarks_folders',
		'tiki_user_postings'          => 'tidbits_user_postings',
		'tiki_user_votings'           => 'tidbits_user_votings',
		'tiki_userpoints'             => 'tidbits_userpoints',
		'tiki_userfiles'              => 'tidbits_userfiles',
	)),
)),
		)
	),

);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( TIDBITS_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}
?>
