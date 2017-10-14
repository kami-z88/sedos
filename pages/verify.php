<?php
$paths = get_paths();
$content['verify']['phone'] =  @$paths[2];

if(isset($_POST['do_verify'])){
	$phone = $_POST['phone'];
	$code = $_POST['code'];
	if(user()->verifyRegisteredPhone($phone, $code)){
		user()->do_login_by_phone($_POST['phone']);
		redirect(get_link(''));
	} else {

		$content['verify-error'] = trans("Verification code does not match");

	}
}

require BASE_DIR . 'view/header.php';
require 'view/verify.php';
require BASE_DIR . 'view/footer.php';