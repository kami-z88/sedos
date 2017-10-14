<?php
// php error configuration
error_reporting( E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);

// Database configuration
define('DB_NAME', 'sedos');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_HOST', 'localhost');

define('SITE_NAME', 'localhost/sedos/');
define('SITE_URL', 'http://'.SITE_NAME);
define('SITE_DIR', dirname(__FILE__));
define('PRETTY_URLS', true);
define('RTL', true);

define('LANGUAGE', 'fa');
include_once BASE_DIR.'language/'.LANGUAGE.'.php';
define('LANG', $lang);

define('HASH', 'asdjklsdfjlkjlkgsajlgu9009gasd90gdsf');
define('MAX_SMS_LENGHT', 128);

define('USER_PERMISSIONS',
	array(
		'CREATE_GROUP',
		'CREATE_ROOT_ORGANIZATION',
		'VIEW_ORGANIZTION_TREE',
		
	)
);


define('ORG_PERMISSIONS',
	array(
		'VIEW_SUB_ORGANIZTION',
		'CREATE_SUB_ORGANIZATION',
		'CREATE_ORGANIZATION_GROUP',
		'CREATE_ORGANIZTION_USER',
		'REMOVE_ORGANIZTION_USER',
		'REMOVE_ORGANIZATION',
		'UPDATE_NAME',
	)
);

define('GROUP_PERMISSIONS',
	array(
		'VIEW_MEMBER_NAME',
		'VIEW_MEMBER_PHONE',
		'VIEW_INVITATIONS',
		'VIEW_BLOCKED_USERS',
		'INVITE_MEMBER',
		'SEND_MESSAGE',
		'SEND_SMS',
		'REMOVE_GROUP',
		'REMOVE_MEMBERS',
		'UPDATE_GROUP_NAME',
		'BLOCK_USER',
	)
);


?>