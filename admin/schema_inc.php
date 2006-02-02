<?php

$tables = array(

'tidbits_user_bookmarks_urls' => "
  url_id I4 AUTO PRIMARY,
  name C(30),
  url C(250),
  data X,
  last_updated I8,
  folder_id I4 NOTNULL,
  user_id I4 NOTNULL
  CONSTRAINTS ', CONSTRAINT `tiki_user_bookmarks_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_user_menus' => "
  menu_id I4 AUTO PRIMARY,
  user_id I4 NOTNULL,
  url C(250),
  name C(40),
  position I4,
  mode C(1)
  CONSTRAINTS ', CONSTRAINT `tiki_user_menus_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_user_tasks' => "
  task_id I4 AUTO PRIMARY,
  user_id I4 NOTNULL,
  title C(250),
  description X,
  date I8,
  status C(1),
  priority I4,
  completed I8,
  percentage I4
  CONSTRAINT ', CONSTRAINT `tiki_user_tasks_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_user_bookmarks_folders' => "
  folder_id I4 AUTO PRIMARY,
  parent_id I4,
  user_id I4 PRIMARY,
  name C(30)
",

'tidbits_user_postings' => "
  user_id I4 PRIMARY,
  posts I8,
  last I8,
  first I8,
  level I4
  CONSTRAINTS ', CONSTRAINT `tiki_user_postings_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_user_votings' => "
  user_id I4 PRIMARY,
  id C(160) PRIMARY
  CONSTRAINTS ', CONSTRAINT `tiki_user_votings_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_userpoints' => "
  user_id I4,
  points decimal(8,2),
  voted I4 DEFAULT NULL
  CONSTRAINTS ', CONSTRAINT `tiki_userpoints_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_userfiles' => "
  file_id I4 AUTO PRIMARY,
  user_id I4 NOTNULL,
  name C(200),
  filename C(200),
  filetype C(200),
  filesize C(200),
  data B,
  hits I4,
  is_file C(1),
  path C(255),
  created I8
  CONSTRAINTS ', CONSTRAINT `tiki_userfiles_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tidbits_user_modules' => "
  name C(200) PRIMARY,
  title C(40),
  data X
",

'tidbits_fortune_cookies' => "
	forunte_id I4 AUTO PRIMARY,
	fortune C(255)
",

'tidbits_banning' => "
	ban_id I4 AUTO PRIMARY,
	mode C(4),
	title C(200),
	ip1 C(3),
	ip2 C(3),
	ip3 C(3),
	ip4 C(3),
	`user` C(40),
	date_from T NOTNULL,
	date_to T NOTNULL,
	use_dates C(1),
	created I8,
	message X
",

'tidbits_banning_sections' => "
	ban_id I4 PRIMARY,
	section C(100) PRIMARY
",



);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( TIDBITS_PKG_NAME, $tableName, $tables[$tableName], FALSE );
}

$gBitInstaller->registerPackageInfo( TIDBITS_PKG_NAME, array(
	'description' => "This package gives you users options and possibilities to personalise your site to their tastes and demands.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'experimental',
	'dependencies' => '',
) );

// ### Default Permissions
$gBitInstaller->registerUserPermissions( TIDBITS_PKG_NAME, array(
	array('bit_p_userfiles', 'Can upload personal files', 'registered', TIDBITS_PKG_NAME),
	//array('bit_p_user_group_perms', 'Can assign permissions to personal groups', 'editors', TIDBITS_PKG_NAME),
	//array('bit_p_user_group_members', 'Can assign users to personal groups', 'registered', TIDBITS_PKG_NAME),
	//array('bit_p_user_group_subgroups', 'Can include other groups in groups', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_create_bookmarks', 'Can create user bookmarksche user bookmarks', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_configure_modules', 'Can configure modules', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_cache_bookmarks', 'Can cache user bookmarks', 'admin', TIDBITS_PKG_NAME),
	array('bit_p_usermenu', 'Can create items in personal menu', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_tasks', 'Can use tasks', 'registered', TIDBITS_PKG_NAME),
	//array('bit_p_admin_users', 'Can edit the information for other users', 'admin', TIDBITS_PKG_NAME),
	array('bit_p_view_tabs_and_tools', 'Can view tab and tool links', 'basic', TIDBITS_PKG_NAME),
	array('bit_p_custom_home_theme', 'Can modify user homepage theme', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_custom_home_layout', 'Can modify user homepage layout', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_custom_css', 'Can create custom style sheets', 'editors', TIDBITS_PKG_NAME),
	//array('bit_p_create_personal_groups', 'Can create personal user groups', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_edit_cookies', 'Can admin cookies', 'editors', TIDBITS_PKG_NAME),
) );
?>
