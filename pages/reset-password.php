<?php
$user = new User();
if(isset($_POST['do-send-code'])){
	if(validate_phone($_POST['phone'])){
		$phone = $_POST['phone'];
		$_SESSION['reset_phone'] = $phone;
		$content['reset']['phone'] = $phone;
		$uid = $user->get_uid_from_user_phone($phone);
		if(!$uid) $uid = $user->get_uid_from_phone($phone);
		if($uid){
			$digits = 8;
			$reset_code = rand(pow(10, $digits-1), pow(10, $digits)-1);
			$user->updateRegisteredUser($uid, $phone, Null, Null, $reset_code);
			//redirect(get_link('reset-password',$phone));
		} else {$content['reset-error'] = trans('This phone has not been registered');}
	} else{
		$content['reset-error'] = trans('Phone number is not valid');
	}
}

if(isset($_POST['do_reset_password'])){
	$phone = $_SESSION['reset_phone'];
	$uid = $user->get_uid_from_user_phone($phone);
	if(!$uid) $uid = $user->get_uid_from_phone($phone);
	$user = new User($uid);
	if($user->getRegisteredUserResetCode($uid, $phone) == $_POST["code"]){
		$pass1 = $_POST["pass1"];
		$pass2 = $_POST["pass2"];
		$content['error-messages'] = $user->update(null, null, null, $pass1, $pass2,$uid);
		if(count($content['error-messages']) < 1){
			redirect(get_link(''));
		}
	}else{
		$content['error-messages'][] = trans("Verification code for this phone number is wronge");
	}
}


require BASE_DIR . 'view/header.php';
require 'view/reset-password.php';
require BASE_DIR . 'view/footer.php';