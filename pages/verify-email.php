<?php
$paths = get_paths();

$code = @$paths[2];
$verified = user()->verifyRegisteredEmail($code);
if($verified){
	$content['verify-message'] = trans("Your Email is verified");
} else {
	$content['verify-error'] = trans("Verification code is no longer valid");
}

require BASE_DIR . 'view/header.php';
require 'view/verify-email.php';
require BASE_DIR . 'view/footer.php';