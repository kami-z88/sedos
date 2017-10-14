<?php

$gid = get_path(2);
$uid = $content['user']['id'];
$user = new User($uid);
$group = new Group($gid);


if(@$_GET['set_defalt_phone']){
	$group->set_user_default_phone($user, $_GET['set_defalt_phone']);
}

if(isset($_POST['do-remove-group'])){
	$group->delete($gid);
	redirect(get_link());
}

if(isset($_POST['do-update-group'])  && isset($_POST['group-name'])){
	$group->update($gid, $_POST['group-name']);
	redirect(get_link('group', $gid));
}

if(isset($_POST['do-leave-group'])){
	$group->leave_group($gid, $uid);
	redirect(get_link());
}


$content['group']['id'] = $group->id;
$content['group']['oid'] = $group->oid;
$content['group']['name'] = $group->name;
$content['group']['default_phone'] = $group->get_user_default_phone($uid);
$content['group']['permissions'] = $group->get_user_permissions($uid);
$content['group']['organization'] = org()->get_organization( $group->oid );
$content['phones'] = $user->get_phone_numbers();
$content['messages'] = msg()->get_group_messages($gid);


if($_GET['join'] == "YES"){
	$result = $group->join_group($gid, $uid);
	redirect(get_link("organization", $content['group']['oid']));

}



require BASE_DIR . 'view/header.php';
require 'view/group.php';
require BASE_DIR . 'view/footer.php';