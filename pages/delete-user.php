<?php

$id = get_path(2);

if($content['user']['id'] = $id){

	user()->delete_user($id);
	redirect(SITE_URL);

	}

?>