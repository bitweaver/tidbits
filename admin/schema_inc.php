<?php

$tables = array(

'tiki_user_bookmarks_urls' => "
  url_id I4 AUTO PRIMARY,
  name C(30),
  url C(250),
  data X,
  last_updated I8,
  folder_id I4 NOTNULL,
  user_id I4 NOTNULL
  CONSTRAINTS ', CONSTRAINT `tiki_user_bookmarks_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tiki_user_menus' => "
  menu_id I4 AUTO PRIMARY,
  user_id I4 NOTNULL,
  url C(250),
  name C(40),
  position I4,
  mode C(1)
  CONSTRAINTS ', CONSTRAINT `tiki_user_menus_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tiki_user_tasks' => "
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

'tiki_user_bookmarks_folders' => "
  folder_id I4 AUTO PRIMARY,
  parent_id I4,
  user_id I4 PRIMARY,
  name C(30)
",

'tiki_user_postings' => "
  user_id I4 PRIMARY,
  posts I8,
  last I8,
  first I8,
  level I4
  CONSTRAINTS ', CONSTRAINT `tiki_user_postings_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tiki_user_votings' => "
  user_id I4 PRIMARY,
  id C(160) PRIMARY
  CONSTRAINTS ', CONSTRAINT `tiki_user_votings_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tiki_userpoints' => "
  user_id I4,
  points decimal(8,2),
  voted I4 DEFAULT NULL
  CONSTRAINTS ', CONSTRAINT `tiki_userpoints_user_ref` FOREIGN KEY (`user_id`) REFERENCES `".BIT_DB_PREFIX."users_users` (`user_id`)'
",

'tiki_userfiles' => "
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

'tiki_user_modules' => "
  name C(200) PRIMARY,
  title C(40),
  data X
",

);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( TIDBITS_PKG_NAME, $tableName, $tables[$tableName], FALSE );
}

$indices = array (
	'users_users_email_idx' => array( 'table' => 'users_users', 'cols' => 'email', 'opts' => array('UNIQUE') ),
	'users_users_login_idx' => array( 'table' => 'users_users', 'cols' => 'login', 'opts' => array('UNIQUE') ),
	'users_permissions_name_idx' => array( 'table' => 'users_permissions', 'cols' => 'perm_name', 'opts' => array('UNIQUE') ),
	'users_users_avatar_atment_idx' => array( 'table' => 'users_users', 'cols' => 'avatar_attachment_id', 'opts' => NULL ),
	'users_groups_user_idx' => array( 'table' => 'users_groups', 'cols' => 'user_id', 'opts' => NULL ),
	'users_groups_user_name_idx' => array( 'table' => 'users_groups', 'cols' => 'user_id,group_name', 'opts' => array('UNIQUE') ),
	'users_groupperm_group_idx' => array( 'table' => 'users_grouppermissions', 'cols' => 'group_id', 'opts' => NULL ),
	'users_groupperm_perm_idx' => array( 'table' => 'users_grouppermissions', 'cols' => 'perm_name', 'opts' => NULL ),
	'users_objectperm_group_idx' =>  array( 'table' => 'users_objectpermissions', 'cols' => 'group_id', 'opts' => NULL ),
	'users_objectperm_perm_idx' => array( 'table' => 'users_objectpermissions', 'cols' => 'perm_name', 'opts' => NULL ),
	'users_objectperm_object_idx' => array( 'table' => 'users_objectpermissions', 'cols' => 'object_id', 'opts' => NULL ),
	'users_permissions_perm_idx' => array( 'table' => 'users_permissions', 'cols' => 'perm_name', 'opts' => NULL ),
	'users_groups_map_user_idx' => array( 'table' => 'users_groups_map', 'cols' => 'user_id', 'opts' => NULL ),
	'users_groups_map_group_idx' => array( 'table' => 'users_groups_map', 'cols' => 'group_id', 'opts' => NULL ),
	'users_fav_con_idx' => array( 'table' => 'users_favorites_map', 'cols' => 'favorite_content_id', 'opts' => NULL ),
	'users_fav_user_idx' => array( 'table' => 'users_favorites_map', 'cols' => 'user_id', 'opts' => NULL )
);



$gBitInstaller->registerSchemaIndexes( TIDBITS_PKG_NAME, $indices );

$gBitInstaller->registerPackageInfo( TIDBITS_PKG_NAME, array(
	'description' => "The users package contains all user information and gives you the possiblity to assign permissions to groups of users.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'experimental',
	'dependencies' => '',
) );

// ### Sequences
$sequences = array (
	'users_users_user_id_seq' => array( 'start' => 2 ),
	'users_groups_id_seq' => array( 'start' => 4 )
);
$gBitInstaller->registerSchemaSequences( TIDBITS_PKG_NAME, $sequences );

// ### Default MenuOptions
$gBitInstaller->registerMenuOptions( TIDBITS_PKG_NAME, array(
	array(42,'o','My files', TIDBITS_PKG_NAME.'userfiles.php',95,'feature_userfiles','bit_p_userfiles','Registered')
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( TIDBITS_PKG_NAME, array(
	array(TIDBITS_PKG_NAME,'webserverauth','n'),
	array(TIDBITS_PKG_NAME,'auth_create_user_auth','n'),
	array(TIDBITS_PKG_NAME,'auth_create_gBitDbUser','n'),
	array(TIDBITS_PKG_NAME,'auth_ldap_adminpass',''),
	array(TIDBITS_PKG_NAME,'auth_ldap_adminuser',''),
	array(TIDBITS_PKG_NAME,'auth_ldap_basedn',''),
	array(TIDBITS_PKG_NAME,'auth_ldap_groupattr','cn'),
	array(TIDBITS_PKG_NAME,'auth_ldap_groupdn',''),
	array(TIDBITS_PKG_NAME,'auth_ldap_groupoc','groupOfUniqueNames'),
	array(TIDBITS_PKG_NAME,'auth_ldap_host','localhost'),
	array(TIDBITS_PKG_NAME,'auth_ldap_memberattr','uniqueMember'),
	array(TIDBITS_PKG_NAME,'auth_ldap_memberisdn','n'),
	array(TIDBITS_PKG_NAME,'auth_ldap_port','389'),
	array(TIDBITS_PKG_NAME,'auth_ldap_scope','sub'),
	array(TIDBITS_PKG_NAME,'auth_ldap_userattr','uid'),
	array(TIDBITS_PKG_NAME,'auth_ldap_userdn',''),
	array(TIDBITS_PKG_NAME,'auth_ldap_useroc','inetOrgPerson'),
	array(TIDBITS_PKG_NAME,'auth_method','tiki'),
	array(TIDBITS_PKG_NAME,'auth_skip_admin','y'),
	array(TIDBITS_PKG_NAME,'allowRegister','y'),
	array(TIDBITS_PKG_NAME,'feature_userfiles','n'),
	array(TIDBITS_PKG_NAME,'forgotPass','y'),
	array(TIDBITS_PKG_NAME,'eponymousGroups','n'),
	array(TIDBITS_PKG_NAME,'modallgroups','y'),
	array(TIDBITS_PKG_NAME,'pass_chr_num','n'),
	array(TIDBITS_PKG_NAME,'pass_due','999'),
	array(TIDBITS_PKG_NAME,'registerPasscode',''),
	array(TIDBITS_PKG_NAME,'rememberme','disabled'),
	array(TIDBITS_PKG_NAME,'remembertime','7200'),
	array(TIDBITS_PKG_NAME,'rnd_num_reg','n'),
	array(TIDBITS_PKG_NAME,'userfiles_quota','30'),
	array(TIDBITS_PKG_NAME,'uf_use_db','y'),
	array(TIDBITS_PKG_NAME,'uf_use_dir',''),
	array(TIDBITS_PKG_NAME,'useRegisterPasscode','n'),
	array(TIDBITS_PKG_NAME,'validateUsers','n'),
	array(TIDBITS_PKG_NAME,'validateEmail','n'),
	array(TIDBITS_PKG_NAME,'min_pass_length','4'),
	array(TIDBITS_PKG_NAME,'feature_clear_passwords','n'),
	array(TIDBITS_PKG_NAME,'feature_custom_home','n'),
	array(TIDBITS_PKG_NAME,'feature_user_bookmarks','n'),
	array(TIDBITS_PKG_NAME,'feature_tasks','n'),
	array(TIDBITS_PKG_NAME,'feature_usermenu','n'),
	array(TIDBITS_PKG_NAME,'feature_userPreferences','y'),
	array(TIDBITS_PKG_NAME,'display_name','real_name'),
	array(TIDBITS_PKG_NAME,'change_language','y'),
	array(TIDBITS_PKG_NAME,'case_sensitive_login','y'),
	array('common', 'feature_user_watches','n'),
) );

// ### Default Permissions
$gBitInstaller->registerUserPermissions( TIDBITS_PKG_NAME, array(
	array('bit_p_userfiles', 'Can upload personal files', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_user_group_perms', 'Can assign permissions to personal groups', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_user_group_members', 'Can assign users to personal groups', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_user_group_subgroups', 'Can include other groups in groups', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_create_bookmarks', 'Can create user bookmarksche user bookmarks', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_configure_modules', 'Can configure modules', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_cache_bookmarks', 'Can cache user bookmarks', 'admin', TIDBITS_PKG_NAME),
	array('bit_p_usermenu', 'Can create items in personal menu', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_tasks', 'Can use tasks', 'registered', TIDBITS_PKG_NAME),
	array('bit_p_admin_users', 'Can edit the information for other users', 'admin', TIDBITS_PKG_NAME),
	array('bit_p_view_tabs_and_tools', 'Can view tab and tool links', 'basic', TIDBITS_PKG_NAME),
	array('bit_p_custom_home_theme', 'Can modify user homepage theme', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_custom_home_layout', 'Can modify user homepage layout', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_custom_css', 'Can create custom style sheets', 'editors', TIDBITS_PKG_NAME),
	array('bit_p_create_personal_groups', 'Can create personal user groups', 'editors', TIDBITS_PKG_NAME),
) );






?>
