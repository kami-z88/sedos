<?php
$uid = $content['user']['id'];
$user = new User($uid);
$paths = get_paths();
$content['oid'] =  @$paths[2];
$content['user'] = user()->logged_in_user();
$content['permissions'] = get_user_current_permissions($uid, $oid, $gid);
if(!in_array('CREATE_ROOT_ORGANIZATION', $content['permissions'])){
	die(trans('Permission Denied.'));
}

	
	
//CREATE_GROUP,CREATE_ROOT_ORGANIZATION


if(isset($_POST['do_add_organization'])){
	$parent_id = $_POST['organization-id'];
	$name = $_POST['name'];
	// TODO: Check if use can do it
	org()->create($name, $content['user']['id'], $parent_id );
	if($parent_id >0){
		redirect(get_link('organization', $parent_id));
	}
	//else
		redirect(get_link());
}


if($content['oid']){
	$parent = new Organization($content['oid']);
	$content['parent']['id'] = $parent->id;
	$content['parent']['name'] = $parent->name;
}


require BASE_DIR . 'view/header.php';
require 'view/add-organization.php';
require BASE_DIR . 'view/footer.php';