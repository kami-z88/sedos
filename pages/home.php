<?php
$user = new User();

if(isset($_POST['do_login'])){
	$login = $user->login($_POST['phone'],$_POST['password'],@$_POST['remember']);
	if($login !== true)
		$content['login-error'] = $login;
}
if(isset($_POST['do_register'])){
	$result = user()->register($_POST['name'], $_POST['email'],	$_POST['phone'], $_POST['password'], $_POST['confirm']);
	if(count($result['errors'])==0){
		$digits = 4;
		$code = rand(pow(10, $digits-1), pow(10, $digits)-1);
		$email_code = generateRandomString(64);
		$user->updateRegisteredUser($result['userid'], $_POST['phone'], $code, $email_code);
		redirect(get_link('verify', $_POST['phone']));
	}else
		$content['register-errors'] = $result['errors'];
}



require BASE_DIR . 'view/header.php';

if(user()->is_logged_in()){
	$content['user']=	user()->logged_in_user();
	require 'pages/panel.php';
	require 'view/panel.php';
}else{
	require 'view/home.php';
}

require BASE_DIR . 'view/footer.php';