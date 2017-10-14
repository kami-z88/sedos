 <?php
class User{
	public $id;
	public $phone_num;
	public $name;
	public $email;
	public $email_verified;
	public $active;
	public $permissions;
	protected static $instance = NULL;


	var $db;
	
	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}			
		return self::$instance;
	}

	function __construct($id = null){
		$id = $_SESSION['id'];
		$this->db = new Database();
		$user = $this->getBaseUser($id);
		$this->id = $user['id'];
		$this->phone_num = $user['phone_num'];
		$this->name = $user['name'];
		$this->email = $user['email'];
		$this->email_verified = $user['email_verified'];
		$this->active = $user['id'];
		$this->permissions = explode(',',$user['permissions']);
	}

	public function register($name, $email, $phone, $password, $confirm){
		$error = array();
		$name = trim($name);

		if (strlen($name) == 0 )
			$error['name1'] = trans("Please enter your name");

		if ($password != $confirm)
			$error['pass1'] = trans("Password fields didn't match");

		 if (strlen($password) < 8)
		 	$error['pass2'] = trans("Password must be at least 8 charachters long.");

		if (! filter_var($email, FILTER_VALIDATE_EMAIL))
			$error['email1'] =  trans("Email address is considered invalid");

		 if($this->emailTaken($email))
			$error['email2'] = trans("Email is taken by another user");
		

		$phone = validate_phone($phone);
		 if(!$phone)
			$error['phone1'] = trans("Phone number is invalid");
		
		$result = $this->db->query ("SELECT * FROM user WHERE phone_num ='$phone' LIMIT 1");
		 if($this->db->numRows($result) > 0)
			$error['phone2'] = trans("Your phone number has been already registered");
		


		if(count($error) < 1){
			$this->addUser($name, null, $email, $password);
			$userid = $this->db->last_id();
		}

		return array(
				'userid' => @$userid,
				'errors' => $error,
			);
	}
	public function update($name=null, $email=null, $phone=null, $password=null, $confirm=null, $uid=null){
		if(! $uid)
			$uid = $this->id;

		$error = array();
		$variables = array();
		$query = "UPDATE user SET ";

		$name = trim($name);
		if ( $name )
			$variables[] = "name='" . $name . "' ";

		if (isset($password))
			if($password == $confirm){
				if (strlen($password) < 8 )
		 			$error[] = trans("Password must be at least 8 charachters long.");
		 		else
					$variables[] = "password='" . md5($password) . "' ";
			}else 
				$error[] = trans("Password fields didn't match");

		if ($email)
			if (! filter_var($email, FILTER_VALIDATE_EMAIL))
				$error[] =  trans("Email address is considered invalid");
			else{
				$variables[] = "email='" . $email . "' ";
				$variables[] = "email_verified='0' ";
			}

		
		if ($phone){
			$phone = validate_phone($phone);
			if ($phone){
				$result = $this->db->query ("SELECT * FROM user WHERE phone_num ='$phone' AND NOT id='$uid' LIMIT 1");
				if($this->db->numRows($result) > 0){
					$error[] = trans("Your phone number has been already registered");
				}else{
					$variables[] = "phone_num='" . $phone . "' ";
				}
			}else
				$error[] = trnas("Phone number is invalid");
		}
		
		if(count($error) < 1){
			$query .= implode($variables, ', ') . "WHERE id='" . $uid . "' LIMIT 1";
			$this->db->execute($query);
		}

		return $error;
	}


	public function login($phone, $password, $remember){
		$phone = validate_phone($phone);
		if(! $phone)
			return trnas("Phone number is invalid");

		$uid = $this->get_uid_from_user_phone($phone);
		if (!$uid)
			return trans("Phone number is wrong");

		$user = $this->getBaseUser($uid);

		if (! $this->verifyPassword($password, $user['password']))
			return "Password is wrong.";

		$sessiondata = $this->addSession($user['id'], $remember);
		if ($sessiondata == false) {
			return trans("System error");
		}		

		return true;
	}

	public function do_login_by_phone($phone){
		$uid = $this->get_uid_from_user_phone($phone);
		$sessiondata = $this->addSession($uid);
		if ($sessiondata == false) {
			return trans("System error");
		}		

		return true;
	}

	protected function addUser($name, $phone, $email, $password, $sendmail=false)  {
		$password = md5($password);
		$this->db->execute("INSERT INTO user (phone_num, name, email, password) VALUES ('$phone','$name','$email', '$password')");
	}

	public function updateRegisteredUser($uid=null, $phone=Null, $code=Null, $email_code=Null, $reset_code=Null){
		if(! $uid)
			$uid = $this->id;
		$query = "SELECT * FROM registeration WHERE uid='$uid'";
		if( $phone ) $query .= " AND phone_num='$phone'";
		$result = $this->db->query ($query);
		if( $this->db->numRows() == 0){
			$query = "INSERT INTO registeration (phone_num, uid";
			if ($code) $query .= ",code";
			if ($email_code) $query .= ",email_code";
			if ($reset_code) $query .= ",reset_code";
			$query .= ") VALUES('$phone','$uid'";
			if ($code) $query .= ",'$code'";
			if ($email_code) $query .= ",'$email_code'";
			if ($reset_code) $query .= ",'$reset_code'";
			$query .= ")";
		}else{
			$query = "UPDATE registeration SET";
			if ($code) $query .= " code='$code'";
			if ($email_code) $query .= " email_code='$email_code'";
			if ($reset_code) $query .= " reset_code='$reset_code'";
			$query .= " WHERE uid='$uid'";
			if( $phone ) $query .= " AND phone_num='$phone'";
		}
		$this->db->execute($query);
	}

	public function getRegisteredUserResetCode($uid=Null, $phone=Null){
		if(! $uid)
			$uid = $this->id;
		$result = $this->db->query ("SELECT reset_code FROM registeration WHERE uid='$uid' AND phone_num='$phone' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) )
			return $row['reset_code'];
	}

	public function sendEmailVerification($email, $email_code, $username='') {
		require 'modules/PHPMailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;

		//From email address and name
		$mail->From = "no-replay@" . SITE_NAME;
		$mail->FromName = "SEDOS Admin";

		//To address and name
		$mail->addAddress($email, $username);

		//Send HTML or Plain Text email
		$mail->isHTML(true);

		$mail->Subject = trans("Email Verification");
		$mail->Body = "<h1>Hello " . $username . "</h1>, Welcome to Sedos.<br>To verify your email please click on the link bellow: " . get_link('verify-email', $email_code);
		$mail->AltBody = "Hello " . $username . ", Welcome to Sedos. To verify your email please click on this link: " . get_link('verify-email', $email_code);

		return $mail->send();
	}

	public function addPhoneNumber($phone, $uid=null){
		if(! $uid)
			$uid = $this->id;
		$this->db->execute("INSERT INTO phone (phone_num, uid) VALUES ('$phone','$uid')");
	}

	public function removePhoneNumber($phone, $uid=null){
		if(! $uid)
			$uid = $this->id;
		$this->db->execute("DELETE FROM phone WHERE phone_num ='$phone' AND uid = '$uid'");
	}

	public function removeRegistrationPhoneNumber($phone, $uid=null){
		if(! $uid)
			$uid = $this->id;
		$this->db->execute("DELETE FROM registeration WHERE phone_num ='$phone' AND uid = '$uid'");
	}

	public function get_group_invitations($uid, $limit=null){
		$group = new Group();
		$phones = $this->get_phone_numbers($uid);
		$phones[] = $this->get_defualt_Phone_number($uid);
		$query = "SELECT * FROM group_users WHERE default_phone in(" . implode(',',$phones) .") AND status='invited'";
		if($limit > 0){$query .= " LIMIT $limit";}
		$result = $this->db->query($query) ;
		$group_id = 0;
		while ( $row=$this->db->fetchArray($result) ){
			$row_id = $row['id'];
			if($row['uid'] == Null){
				if($row['gid'] != $group_id){
					$this->db->execute("UPDATE group_users SET uid=$uid WHERE id=$row_id");
					$group_id = $row['gid'];
				}
				else{$this->db->execute("DELETE FROM group_users WHERE id=$row_id");}
			}
			$groupIDs[] = $row['gid'];
		}
		return $group->get_group_info_by_ids($groupIDs);

	}

	public function reject_group_invitation($uid, $gid){
		$result = $this->db->execute("UPDATE  group_users SET status='rejected' WHERE uid='$uid' AND gid='$gid' ");
	}

	public function accept_group_invitation($uid, $gid){
		$this->db->execute("UPDATE  group_users SET status='member' WHERE uid='$uid' AND gid='$gid' ");
	}



	public function reject_organization_invitation($uid, $oid){
		$result = $this->db->execute("DELETE FROM organization_users WHERE uid='$uid' AND oid='$oid' ");
	}

	public function accept_organization_invitation($uid, $oid){
		$this->db->execute("UPDATE organization_users SET ' WHERE uid='$uid' AND gid='$gid' ");
	}


	public function verifyRegisteredEmail($code){
		$result = $this->db->query ("SELECT * FROM registeration WHERE email_code='$code' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) ){
			$uid = $row['uid'];
		}
		if($uid){
			$this->db->execute("UPDATE user SET email_verified='1' WHERE id='$uid'");
			return true;
		}
		return false;
	}

	public function verifyRegisteredPhone($phone, $code){
		if( $this->get_uid_from_user_phone($phone) > 0 )
			return false;
		$result = $this->db->query ("SELECT * FROM registeration WHERE phone_num ='$phone' AND code='$code'  LIMIT 1");
		while ( $row=$this->db->fetchArray($result) ){
			$uid = $row['uid'];
		}
		if($uid != null){
			if(! $this->get_defualt_Phone_number($uid)){
				$this->db->execute("UPDATE user SET phone_num='$phone' WHERE id='$uid' LIMIT 1;");
			} else {
				$this->addPhoneNumber($phone, $uid);
			}
			$this->update_user_memberships_with_phone($phone, $uid);
			// remove item from registeration table
			// $this->db->execute("DELETE FROM registeration WHERE phone_num ='$phone' and code = '$code'");
			$success =  true;
		} else { $success = false;}

		return $success;
	} 

	public function get_defualt_Phone_number ($uid){

	$result = $this->db->query ("SELECT phone_num FROM user WHERE id ='$uid' LIMIT 1");
		$row=$this->db->fetchArray($result);
		return $row['phone_num'];		
	}

	public function get_uid_from_user_phone($phone)
	{
		$result = $this->db->query ("SELECT * FROM user WHERE phone_num ='$phone' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) )
			return $row['id'];
		return null;
	}

	public function get_uid_from_phone($phone)
	{
		$this->db->query ("SELECT * FROM phone WHERE phone_num ='$phone' LIMIT 1");
		$result=$this->db->fetchArray($result);
			return $result['uid'];
	}

	public function emailTaken($email)
	{
		$this->db-> query ("SELECT email FROM user WHERE email ='$email' LIMIT 1");
		if ( $this->db->numRows() > 0 )
			return true;
		return false;
	}

	public function verifyPassword($pass,$hash)
	{
		if(md5($pass)==$hash)
			return true;
		return false;
	}

	public function getBaseUser($uid)
	{
		$result = $this->db->query("SELECT * FROM user WHERE id ='$uid' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) )
			return $row;
	}

	protected function addSession($uid, $remember=false)
	{
		$ip = $this->getIp();
		$user = $this->getBaseUser($uid);
		if (!$user) {
			return false;
		}
		$hash = sha1(HASH . microtime());
		$data['hash'] = $hash;
		$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$this->deleteExistingSessions($uid);
		if ($remember == 'on') {
			$data['expire'] = date("Y-m-d H:i:s", strtotime('+30 days'));
			$data['expiretime'] = strtotime($data['expire']);
		} else {
			$data['expire'] = date("Y-m-d H:i:s", strtotime('+30 minutes'));
			$data['expiretime'] = 0;
		}

		$query = $this->db->execute("UPDATE user SET session='$hash' WHERE id='$uid' LIMIT 1;");

		$data['expire'] = strtotime($data['expire']);

		$_SESSION['id']= $uid;
		$_SESSION['hash']= $hash;

		return $data;
	}
	
	protected function getIp()
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
		   return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		   return $_SERVER['REMOTE_ADDR'];
		}
	}

	protected function deleteExistingSessions($uid)
	{
		$query = $this->db->execute("UPDATE user SET session=NULL WHERE id='$uid' LIMIT 1;");
	}

	function is_logged_in(){
		$uid = @$_SESSION['id'];
		$hash = @$_SESSION['hash'];

		$result = $this->db->query("SELECT * FROM user WHERE id ='$uid' and session='$hash' LIMIT 1");
		if( $this->db->numRows() > 0 )
			return true;
		return false;
	}

	function logged_in_user(){
		$uid = @$_SESSION['id'];
		$hash = @$_SESSION['hash'];

		$result = $this->db->query("SELECT * FROM user WHERE id ='$uid' and session='$hash' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) )
			return $row;
	}

	function logout(){
		$uid = $_SESSION['id']; 
		$this->db->execute("UPDATE user SET session='' WHERE id='$uid' LIMIT 1 ");
		session_reset();
	}


	function get_phone_numbers($uid=null){
		if(! $uid)
			$uid = $this->id;
		$result = $this->db->query("SELECT * FROM phone WHERE uid ='$uid'");
		while ( $row=$this->db->fetchArray($result) )
			$phone[] = $row['phone_num'];
		return $phone;
	}

	function get_registered_phone_numbers($uid=null){
		if(! $uid)
			$uid = $this->id;
		$result = $this->db->query("SELECT * FROM registeration WHERE uid ='$uid'");
		while ( $row=$this->db->fetchArray($result) )
			$phone[] = $row['phone_num'];
		return $phone;
	}

	function get_unverified_phone_numbers($uid=null){
		if(! $uid)
			$uid = $this->id;
		$verified_phones = $this->get_phone_numbers($uid);
		$verified_phones[] = $this->get_defualt_Phone_number($uid);

		$result = $this->db->query("SELECT * FROM registeration WHERE uid ='$uid' AND phone_num NOT IN (" . implode(',', $verified_phones) . ")");
		while ( $row=$this->db->fetchArray($result) )
			$phones[] = $row['phone_num'];
		return $phones;
	}

	function delete_user ($uid){
		$this->db->execute("DELETE FROM registeration WHERE uid ='$uid'");
		$this->db->execute("DELETE FROM phone WHERE uid ='$uid'");
		$this->db->execute("DELETE FROM user WHERE id ='$uid'");
		$this->db->execute("DELETE FROM group_users WHERE uid ='$uid'");
	}


	function get_organizations($uid=null){
		// Get organizations in which $uid is an admin

		if(! $uid)
			$uid = $this->id;
		$i = 0;
		
		$oids = $this->get_user_organization_ids($uid);
		if(count($oids)<1)
			return array();
		$result = $this->db->query("SELECT * FROM organizations WHERE id IN (" . implode(',', $oids) . ")");
		while ( $row=$this->db->fetchArray($result) ){
			$organizations[$i]['info'] = $row;
			$organizations[$i]['parents'] = org()->get_list($row['id']);
			array_pop($organizations[$i]['parents']);
			$i++;
		}
		return $organizations;
	}

	public function get_user_organization_ids($uid){
		$oids = array();
		$result = $this->db->query("SELECT * FROM organization_users WHERE uid ='$uid'");
		while ( $row=$this->db->fetchArray($result) )
			$oids[]= $row['oid'];
		return $oids;
	}


	function get_organizations_tree($uid=null){
		// Get organizations in which $uid is an admin, use tree structure

		if(! $uid)
			$uid = $this->id;
		$i = 0;
		$oids = $this->get_user_organization_ids($uid);
		if(count($oids)<1)
			return array();
		$result = $this->db->query("SELECT * FROM organizations WHERE id IN (" . implode(',', $oids) . ")");

		while ( $row=$this->db->fetchArray($result) ){
			$organizations = org()->get_list($row['id']);
			foreach ($organizations as $key => $org) {
				$list[$org['id']] = $org;
			}
			$i++;
		}
		$tree = buildTree($list, 'parent_id', 'id');
		return $tree;
	}

	function get_user_by_id($uid=null){
		if(! $uid)
			$uid = $this->id;
		$result = $this->db->query("SELECT * FROM user WHERE id ='$uid' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) ){		
			return $row;
		}
	}

	function get_users_by_ids($ids){
		if(count($ids)<1)
			return;
		$users = array();
		$result = $this->db->query("SELECT * FROM user WHERE id IN(" . implode(',',$ids) . ")");
		while ( $row=$this->db->fetchArray($result) ){		
			$users[$row['id']] = $row;
		}

		return $users; 
	}

	function has_permission ($permission, $type='user', $id=0, $uid=null){
		if(! $uid)
			$uid = $this->id;
		// cache user's permissions inside class property.
		if(strtolower($type) == 'user')
			$id = $uid;
		if(! count($this->permissions[$uid][strtolower($type)][$id]) > 0 ) {
			if(strtolower($type) == 'user'){
				$result = $this->db->query("SELECT permissions FROM user WHERE id ='$uid' LIMIT 1");
				while ( $row=$this->db->fetchArray($result) ){		
					$perms = $row['permissions'];
				}
				$this->permissions[$uid]['user'][$uid] = explode(',', $perms);
			}
			if(strtolower($type) == 'group'){
				$result = $this->db->query("SELECT permissions FROM group_users WHERE uid ='$uid' AND gid='$id' LIMIT 1");
				while ( $row=$this->db->fetchArray($result) ){		
					$perms = $row['permissions'];
				}
				$this->permissions[$uid]['group'][$id] = explode(',', $perms);;
			}

			if(strtolower($type) == 'org'){
				$result = $this->db->query("SELECT permissions FROM organization_users WHERE uid ='$uid' AND oid='$id' LIMIT 1");
				while ( $row=$this->db->fetchArray($result) ){		
					$perms = $row['permissions'];
				}
				$this->permissions[$uid]['org'][$id] = explode(',', $perms);;
			}
			
		}
		if(in_array($permission, $this->permissions[$uid][strtolower($type)][$id]))
			return true;
		else
			return false;

	}


	public function update_user_memberships_with_phone($phone, $uid=null){
		if(! $uid)
			$uid = $this->id;
		$this->db->execute("UPDATE group_users SET uid='$uid' WHERE uid=NULL AND default_phone='$phone' AND status='admin'");
		$this->db->execute("UPDATE organization_users SET uid='$uid' WHERE uid IS NULL AND default_phone='$phone'");
	}

	public function get_user_email_by_uid($uid){
		$result = $this->db->query("SELECT email FROM user WHERE id=$uid AND email_verified=1 AND active=1 ");
		$row = $this->db->fetchArray($query);
		return $row['email'];
	}


	public function get_users_emails_by_oid($oid) {
		$org = new Organization();
		$group = new Group();
		$gids = $org->get_organization_gids($oid);
		$uids = $group-> get_uids_by_gids($gids);
		foreach ($uids as $uid) {
			$emails[] = $this->get_user_email_by_uid($uid);
		}
		return array_unique($emails);
	}

	/*
	register()
	get()
	edit()
	delete()
	send_sms($text,$group)
	get_group_ids()
	add_to_group($gid)
	remove_from_group($gid)
	get_group_role($gid)
	edit_group_role($gid)

	
	get_default_phone()
	add_phone_number()
	remove_phone_number($phone_id)
	edit_phone_number($phone_id)

	*/
}