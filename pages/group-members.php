<?php

$gid = get_path(2);
$uid = $content['user']['id'];
$user = new User($uid);
$group = new Group($gid);

if(isset($_POST["do-invite-member"])){
	$phone = validate_phone($_POST['phone']);
	if($phone){
		$group->invite_phone($phone, $gid);
	}else{
		$content['messages'][] = trans("Could Not Add Phone Number");
	}
}

if(isset($_POST["do-add-admin"])){
	$phone = validate_phone($_POST['phone']);
	if($phone){
	    global $group_permissions;
		foreach ($group_permissions as $key => $value)
			if(isset($_POST[$value]) and $_POST[$value]=='on')
				$perms[] = $value;
		$permissions = implode(',', $perms);
		$uid = $user->get_uid_from_user_phone($phone);
		$group->add_user($gid,$uid,$phone,'admin', $permissions );
	}else{
		$content['messages'][] = trans("Could Not Add Phone Number");
	}
}


if(isset($_POST["do-remove-invitation"])){
	$group->remove_invitation($_POST['remove-invitation-phone'], $gid);
	redirect(get_link('group-members', $gid, array('status'=>'invited')));

}

if(isset($_POST["do-unblock-membership"])){
	$group->unblock_user($_POST['unblock-user-id']);
}

if(isset($_POST["do-kickout-user"])){
	$block = false;
	if(isset($_POST['block-user'])){
		$block = true;
	} 

	$group->kickout_user($gid, $_POST['uid'], $uid, $block);

}



$content['group']['id'] = $group->id;
$content['group']['oid'] = $group->oid;
$content['group']['name'] = $group->name;
$content['group']['default_phone'] = $group->get_user_default_phone($uid);
$content['group']['permissions'] = $group->get_user_permissions($uid);
$content['group']['organization'] = org()->get_organization( $group->oid );


$status = $_GET['status'];
if(! $status) 
	$status = 'member';
$members = $group->get_members($gid, $status);
// seperate users and unregistered users(only phone) to set details for each
// Get ID of members of group which are registered users
foreach ($members as $key => $member)
	if($member['id'])
		$user_IDs[$member['id']] = $member['id'];
// Get Registered user's detail
$users = user()->get_users_by_ids($user_IDs);
// prepare table information
$i = 0;
foreach ($members as $key => $member) {
	if($member['id']){
		$content['users'][$i]['id'] = $member['id'];
		$content['users'][$i]['name'] = $users[ $member['id'] ]['name'];
		if($member['phone'])
			$content['users'][$i]['phone'] = $member['phone'];
		else
			$content['users'][$i]['phone'] = $users[ $member['id'] ]['phone_num'];

	}else{
		$content['users'][$i]['id'] = 0;
		if($member['phone'])
			$content['users'][$i]['phone'] = $member['phone'];
	}
	$content['users'][$i]['status'] = $member['status'];
	$i++;
}

$content['js'][] = SITE_URL . "static/js/jquery.dataTables.min.js";
$content['css'][] = SITE_URL . "static/css/dataTables.bootstrap.min.css";

require BASE_DIR . 'view/header.php';
require 'view/group-members.php';
require BASE_DIR . 'view/footer.php';