<?php

$tables = array(

'tidbits_bookmarks_urls' => "
	url_id I4 AUTO PRIMARY,
	name C(30),
	url C(250),
	data X,
	last_updated I8,
	folder_id I4 NOTNULL,
	user_id I4 NOTNULL
	CONSTRAINT ', CONSTRAINT `user_bookmarks_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_menus' => "
	menu_id I4 AUTO PRIMARY,
	user_id I4 NOTNULL,
	url C(250),
	name C(40),
	menu_position I4,
	mode C(1)
	CONSTRAINT ', CONSTRAINT `user_menus_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_tasks' => "
	task_id I4 AUTO PRIMARY,
	user_id I4 NOTNULL,
	title C(250),
	description X,
	task_date I8,
	status C(1),
	priority I4,
	completed I8,
	percentage I4
	CONSTRAINT ', CONSTRAINT `user_tasks_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_bookmarks_folders' => "
	folder_id I4 AUTO PRIMARY,
	parent_id I4,
	user_id I4 PRIMARY,
	name C(30)
",

'tidbits_fortune_cookies' => "
	forunte_id I4 AUTO PRIMARY,
	fortune C(255)
",

// we need to convert the date stamps to integer columns
'tidbits_banning' => "
	ban_id I4 AUTO PRIMARY,
	mode C(4),
	title C(200),
	ip1 C(3),
	ip2 C(3),
	ip3 C(3),
	ip4 C(3),
	ban_user C(40),
	date_from T NOTNULL,
	date_to T NOTNULL,
	use_dates C(1),
	created I8,
	ban_message X
",

'tidbits_banning_packages' => "
	ban_id I4 PRIMARY,
	package C(100) PRIMARY
",

);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( TIDBITS_PKG_NAME, $tableName, $tables[$tableName], FALSE );
}

$gBitInstaller->registerPackageInfo( TIDBITS_PKG_NAME, array(
	'description' => "This package gives your users options and possibilities to personalise your site to their tastes and demands.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Default Permissions
$gBitInstaller->registerUserPermissions( TIDBITS_PKG_NAME, array(
	array('p_tidbits_upload_userfiles', 'Can upload personal files', 'registered', TIDBITS_PKG_NAME),
	//array('p_users_assign_group_perms', 'Can assign permissions to personal groups', 'editors', TIDBITS_PKG_NAME),
	//array('p_users_assign_group_members', 'Can assign users to personal groups', 'registered', TIDBITS_PKG_NAME),
	//array('p_users_group_subgroups', 'Can include other groups in groups', 'editors', TIDBITS_PKG_NAME),
	array('p_tidbits_create_bookmarks', 'Can create user bookmarksche user bookmarks', 'registered', TIDBITS_PKG_NAME),
	array('p_tidbits_configure_modules', 'Can configure modules', 'registered', TIDBITS_PKG_NAME),
	array('p_tidbits_cache_bookmarks', 'Can cache user bookmarks', 'admin', TIDBITS_PKG_NAME),
	array('p_tidbits_use_usermenu', 'Can create items in personal menu', 'registered', TIDBITS_PKG_NAME),
	array('p_tidbits_use_tasks', 'Can use tasks', 'registered', TIDBITS_PKG_NAME),
	//array('p_users_admin', 'Can edit the information for other users', 'admin', TIDBITS_PKG_NAME),
	//array('p_users_view_icons_and_tools', 'Can view tab and tool links', 'basic', TIDBITS_PKG_NAME),
	array('p_tidbits_custom_home_theme', 'Can modify user homepage theme', 'editors', TIDBITS_PKG_NAME),
	array('p_tidbits_custom_home_layout', 'Can modify user homepage layout', 'editors', TIDBITS_PKG_NAME),
	array('p_tidbits_use_custom_css', 'Can create custom style sheets', 'editors', TIDBITS_PKG_NAME),
	//array('p_users_create_personal_groups', 'Can create personal user groups', 'editors', TIDBITS_PKG_NAME),
	array('p_tidbits_edit_fortune_cookies', 'Can admin cookies', 'editors', TIDBITS_PKG_NAME),
	array('p_tidbits_admin_banning', 'Can ban users or IPs', 'admin', TIDBITS_PKG_NAME),
) );
?>
