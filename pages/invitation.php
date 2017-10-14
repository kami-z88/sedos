<?php 
$gid = get_path(2);
$content['user'] = user()->logged_in_user();
$uid = $content['user']['id'];
$user = new User($uid);
$group = new Group($gid);

if($_GET['invitation'] == 'reject'){
	$user->reject_group_invitation($uid, $gid);
	redirect(get_link(''));
}

if($_GET['invitation'] == 'accept'){
	$user->accept_group_invitation($uid, $gid);
	redirect(get_link(''));
}


require BASE_DIR . 'view/header.php';
require 'view/invitation.php';
require BASE_DIR . 'view/footer.php';