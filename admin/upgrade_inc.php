<?php
global $gBitSystem, $gUpgradeFrom, $gUpgradeTo, $gBitDb;

$upgrades = array(
	// we should move the tidbits stuff from users to here...
);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( TIDBITS_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}
?>
