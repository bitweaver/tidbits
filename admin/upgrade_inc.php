<?php
global $gBitSystem, $gUpgradeFrom, $gUpgradeTo, $gBitDb;

$upgrades = array(

	'BWR1' => array(
		'BWR2' => array(
array( 'DATADICT' => array(
	array( 'RENAMECOLUMN' => array(
		'tidbits_menus' => array(
			'`position`' => 'menu_position',
		),
		'tidbits_tasks' => array(
			'`date`' => 'task_date',
		),
		'tidbits_banning' => array(
			'`user`' => 'ban_user',
			'`message`' => 'ban_message',
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
