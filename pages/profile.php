<?php

$content['user'] = user()->logged_in_user();
$uid = $content['user']['id'];
$paths = get_paths();
$profile_id = @$paths[2];
if($profile_id != $uid){ // and user is not admin
	require 'view/permission-error.php';
}


require BASE_DIR . 'view/header.php';

if($profile_id != $uid){ // and user is not admin
	require 'view/permission-error.php';
}else{
	require 'view/profile.php';
}

require BASE_DIR . 'view/footer.php';
