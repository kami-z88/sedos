<?php
$pages = array(
	'' => 'home.php',
	'profile' => 'profile.php',
	'edit-profile' => 'edit-profile.php',
	'logout' => 'logout.php',
	'register' => 'register.php',
	'user' => 'user.php',
	'panel' => 'panel.php', // list Organizations that user is an admin + list groups which user is a member
	'organization' => 'organization.php', // list organization groups
	'organization-members' => 'organization-members.php', // list organization groups
	'add-organization' => 'add-organization.php', // list organization groups
	'group' => 'group.php', // list group members for admin - show group detail and messages for members
	'group-members' => 'group-members.php', // list group members for admin - show group detail and messages for members
	'add-group' => 'add-group.php', // list group members for admin - show group detail and messages for members
	'verify' => 'verify.php', // list group members for admin - show group detail and messages for members
	'verify-email' => 'verify-email.php', // list group members for admin - show group detail and messages for members
	'detete-user' => 'delete-user.php',
	'admin' => 'admin.php',
	'send-message' => 'send-message.php',
	'all-messages' => 'all-messages.php',
	'invitation' => 'invitation.php',
	'reset-password' => 'reset-password.php',
	'r' => 'redirect-message.php'
);

$paths = get_paths();
$req = @$paths[1];
session_start();
if(user()->is_logged_in()){
	$content['user'] = user()->logged_in_user();
}
if (isset($pages[$req])){
	require 'pages/'. $pages[$req];
}else
	require 'pages/404.php';
