<?php
$content['user'] = user()->logged_in_user();
$uid = $content['user']['id'];
$paths = get_paths();
$profile_id = @$paths[2];
$user = new User($uid);

$content['user-phones'] = $user->get_phone_numbers();
$content['user-registered-phones'] = $user->get_registered_phone_numbers();

if($profile_id != $uid){ // and user is not admin
	require 'view/permission-error.php';
}

if($_GET["action"]=="del_phone"){
	$phone_num = $_GET["phone"];
	if(in_array($phone_num, $content['user-phones']))
		$user->removePhoneNumber($phone_num);
	elseif(in_array($phone_num, $content['user-registered-phones']))
		$user->removeRegistrationPhoneNumber($phone_num);
	else
		$content['errors-phone'][] = trans("Your request was not accepted");
}

if(isset($_POST['do_update_profile'])){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$content['errors-profile'] = $user->update($name, $email);
}

if(isset($_POST['do_reset_password'])){
	$pass1 = $_POST['pass1'];
	$pass2 = $_POST['pass2'];
	$content['errors-password'] = $user->update(null, null, null, $pass1, $pass2);
}

if(isset($_POST['do_update_phone'])){
	$phone_num = $_POST['phone_num'];
	if($content['user']['phone_num'] != $phone_num){
		if(in_array($phone_num, $content['user-phones'])){
			$user->update(null, null, $phone_num);
			$user->removePhoneNumber($phone_num);
			$user->addPhoneNumber($content['user']['phone_num']);
		}
	}else
		$content['errors-phone'] = trans("Your request was not accepted)";
}

if(isset($_POST['do_resend_mail_code'])){
		$email_code = generateRandomString(64);
		$user->updateRegisteredUser($uid, $new_phone, null, $email_code);
		user()->sendEmailVerification($_POST['email'], $email_code, $_POST['name']);
}
if(isset($_POST['do_add_phone'])){
	$new_phone = validate_phone($_POST['new-phone']);

	if(! $new_phone)
		$content['error-add-phone'] = trans("Phone number is invalid");
	elseif(in_array($new_phone, $content['user-registered-phones'])){
		$content['error-add-phone'] = trans("Your are already added this phone number");
	}
	elseif(in_array($new_phone, $content['user-phones'])){
		$content['error-add-phone'] = trans("You are registered and verified this number before");
	}
	elseif($new_phone == $user->get_defualt_Phone_number ($uid)){
		$content['error-add-phone'] = trans("This phone number is your default login number");
	}
	else{
		$digits = 4;
		$code = rand(pow(10, $digits-1), pow(10, $digits)-1);
		$user->updateRegisteredUser($uid, $new_phone, $code);
	}
}

$content['user'] = user()->logged_in_user();
$content['user-phones'] = $user->get_phone_numbers();
$content['user-registered-phones'] = $user->get_registered_phone_numbers();
$content['user-unverified-phones'] = $user->get_unverified_phone_numbers();

require BASE_DIR . 'view/header.php';

	if($profile_id != $uid){
		require 'view/permission-error-profile.php';
	} else {require 'view/edit-profile.php';}
	


require BASE_DIR . 'view/footer.php';
