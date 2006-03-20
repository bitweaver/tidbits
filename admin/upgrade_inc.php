<?php
global $gBitSystem, $gUpgradeFrom, $gUpgradeTo, $gBitDb;

$upgrades = array(

	'BWR1' => array(
		'BWR2' => array(
		)
	),

);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( TIDBITS_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}
?>
