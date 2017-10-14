<?php
$oid = get_path(2);
$content['user'] = user()->logged_in_user();
$uid = $content['user']['id'];
$user = new User($uid);
$org = new Organization($oid);
$group = new Group();
$content['permissions'] = get_user_current_permissions($uid, $oid, $gid);


if(isset($_POST['do-remove-organization'])){
	if(in_array('REMOVE_ORGANIZATION', $content['permissions'])){
		if($_POST['remove-all-subs']=='1'){
			$org->delete($org->id);
			if($org->parent_id > 0 )
				redirect(get_link('organization', $org->parent_id));
			else
				redirect(get_link());
		}else{
			$org->delete($org->id, 'link');
			if($org->parent_id){ redirect(get_link('organization', $org->parent_id)); }
			else {redirect(get_link());}
		}		
	} else {
		die(trans('Permission Denied.'));
	}

}

if(isset($_POST['do-update-organization'])){
	$org->update($org->id, $_POST['organization-name']);
}

$content['organization']['id'] = $org->id;
$content['organization']['name'] = $org->name;
$content['organization']['groups'] = $joo = $org->get_groups();
$content['organization']['sub-organizations'] = $org->get_sub_organizations();
$user_groupIDs = $group->get_groupIDs_by_userID($uid);



require BASE_DIR . 'view/header.php';
require 'view/organization.php';
require BASE_DIR . 'view/footer.php';
