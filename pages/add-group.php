<?php
$paths = get_paths();
$content['oid'] =  @$paths[2];
$content['user'] = user()->logged_in_user();
if(isset($_POST['do_add_group'])){
	$parent_id = $_POST['organization-id'];
	$name = $_POST['name'];
	// TODO: Check if use can do it
	$groupID = group()->create($name,$content['user']['id'], $parent_id );
	if($parent_id > 0)
		redirect(get_link('organization', $parent_id));
	else
		redirect(get_link());
}


if($content['oid']){
	$parent = new Organization($content['oid']);
	$content['parent']['id'] = $parent->id;
	$content['parent']['name'] = $parent->name;
}


require BASE_DIR . 'view/header.php';
require 'view/add-group.php';
require BASE_DIR . 'view/footer.php';