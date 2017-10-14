<?php

$oid = get_path(2);
$uid = $content['user']['id'];
$user = new User($uid);
$org = new Organization($oid);

if(isset($_POST["do-add-admin"])){
	$phone = validate_phone($_POST['phone']);
	if($phone){
		foreach (ORG_PERMISSIONS as $key => $value)
			if(isset($_POST[$value]) and $_POST[$value]=='on')
				$perms[] = $value;
		$permissions = implode(',', $perms);
		$user_id = $user->get_uid_from_user_phone($phone);
		$org->add_user($oid, $user_id, $phone, $permissions);
	}else{
		$content['messages'][] = trans("Could Not Add Phone Number");
	}
}


if(isset($_POST["do-kickout-user"])){
	$block = false;
	if(isset($_POST['block-user'])){
		$block = true;
	} 

	$org->kickout_user($oid, $_POST['uid'], $uid, $block);

}



$content['organization']['id'] = $org->id;
$content['organization']['name'] = $org->name;
$content['organization']['permissions'] = $org->get_user_permissions($uid);


$members = $org->get_members($oid);
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
	$i++;
}

$content['js'][] = SITE_URL . "static/js/jquery.dataTables.min.js";
$content['css'][] = SITE_URL . "static/css/dataTables.bootstrap.min.css";

require BASE_DIR . 'view/header.php';
require 'view/organization-members.php';
require BASE_DIR . 'view/footer.php';