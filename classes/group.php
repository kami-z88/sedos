<?php
class Group{
	public $id;
	public $oid;
	public $name;
	protected static $instance = NULL;
	protected static $db1 = NULL;

	var $db;
	
	public static function instance($id=null) {
		if ( self::$instance == null ) {
			self::$instance = new self($id);
		}			
		return self::$instance;
	}

	function Group($id=null){
		$this->db = new Database();
		$result = $this->db->query("SELECT * FROM groups WHERE id ='$id' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) ){
			$this->oid = $row['oid'];
			$this->name = $row['name'];
		}
		$this->id = $id;
	}

	public function add($name, $oid = null ){
		$result = $this->db->query("INSERT INTO  groups (name,oid) VALUES ('$name', '$oid')");
		return $this->db->last_id();
	}

	public function create($name, $uid, $oid = null ){
		$gid = $this->add($name, $oid);
		global $group_permissions;
		$permissions = implode(',', $group_permissions);
		$this->add_user($gid,$uid,null,'admin', $permissions );
		return $this->db->last_id();
	}

	public function add_user($id, $uid, $phone = null, $status='member', $permissions = null ){
		if(! $phone)
			$phone = user()->get_defualt_Phone_number ($uid);
		if($uid>0)
			$this->db->execute("INSERT INTO group_users (gid,uid,default_phone,status,permissions) VALUES ('$id', '$uid', '$phone', '$status', '$permissions')");
		else
			$this->db->execute("INSERT INTO group_users (gid,default_phone,status,permissions) VALUES ('$id', '$phone', '$status', '$permissions')");

	}

	public function update($gid, $name){
		$this->db->execute("UPDATE groups SET name ='$name' WHERE id ='$gid'");
	}

	public function delete($gid){
		$this->db->query("DELETE  FROM groups WHERE id ='$gid'");
		$this->db->query("DELETE  FROM group_users WHERE gid ='$gid'");

	}

	public function leave_group($gid, $uid){
		$result = $this->db->query("DELETE  FROM group_users WHERE gid ='$gid' AND uid = '$uid' LIMIT 1");
		return $result;
	}

	public function is_user_group_member($gid, $uid) {
		$result = $this->db->query("SELECT * FROM group_users WHERE gid ='$gid' AND uid = '$uid'  LIMIT 1");
		$result = $this->db->fetchArray($result);
		if($result) {
			return true;
		} else {return false;}
	}

	public static function get_group_member_number($gid){
		$db1 = new Database();
		$result = $db1->query("SELECT COUNT(gid) FROM group_users WHERE gid ='$gid'");
		$row=$db1->fetchArray($result);
		return $row["COUNT(gid)"];
	}	



	public function get_user_permissions($uid, $gid=null){
		if(! $gid)
			$gid = $this->id;
		$result = $this->db->query("SELECT * FROM group_users WHERE uid ='$uid' AND gid ='$gid' ");
		while ( $row=$this->db->fetchArray($result) ) 
			$permissions = $row['permissions'];
		return explode(',', $permissions);
	}

	public function set_user_default_phone($user, $phone_num, $gid=null){
		if(! $gid)
			$gid = $this->id;
		$uid = $user->id;
		$result = $this->db->query("SELECT * FROM phone WHERE uid ='$uid' AND phone_num ='$phone_num' ");
		if( $this->db->numRows() > 0 or $user->phone_num==$phone_num or $phone_num=='null')
			$this->db->execute("UPDATE group_users SET default_phone='$phone_num' WHERE uid ='$uid' AND gid ='$gid'");

	}
	public function get_user_default_phone($uid, $gid=null){
	/*
	Get user's default number for this group
	$uid: user_id
	$gid: group_id, if it's not set we use class object's default id
	*/
		// get group id if it's not in parameters
		if(! $gid)
			$gid = $this->id;
		// get user's default phone for this group
		$result = $this->db->query("SELECT * FROM group_users WHERE uid ='$uid' AND gid ='$gid' ");
		while ( $row=$this->db->fetchArray($result) ) 
			$phone_num = $row['default_phone'];
		// if default is null or 0 consider it muted!
		if(! $phone_num )
			return null; // mute
		// if default phone no longer belongs to user set default to user's login phone number
		$result = $this->db->query("SELECT * FROM phone WHERE uid ='$uid' AND phone_num ='$phone_num' ");
		while ( $row=$this->db->fetchArray($result) ) 
			$phone_num = $row['phone_num'];
		if($phone_num)
			return $phone_num;
		else{
			$user = new User($uid);
			return $user->phone_num;
		}
	}


	public function get_members($gid, $status='member'){
		$result = $this->db->query("SELECT uid, default_phone, status FROM group_users WHERE gid ='$gid' AND status='$status'");
		$i = 0;
		while($row = $this->db->fetchArray($result)){
			$users[$i]['id'] = $row['uid'];
			$users[$i]['phone'] = $row['default_phone'];
			$users[$i]['status'] = $row['status'];
			$i++;
		}
		return $users;
	}


	public function get_user_groups($uid){
		$groups = array();
		$i = 0;
		$result = $this->db->query("SELECT * FROM group_users WHERE uid ='$uid' AND status ='member' OR status = 'admin'");
		while ( $row=$this->db->fetchArray($result) ){
			$groups[$i]['row_id'] = $row['id'];
			$groups[$i]['gid'] = $row['gid'];
			$groups[$i]['uid'] = $row['uid'];
			$groups[$i]['role'] = $row['role'];
			$groups[$i]['default_phone'] = $row['default_phone'];
			$groups_result = $this->db->query("SELECT * FROM groups WHERE id ='" . $groups[$i]['gid'] . "' LIMIT 1");
			while ( $group_row=$this->db->fetchArray($groups_result) ){
				$groups[$i]['name'] = $group_row['name'];
				$groups[$i]['oid'] = $group_row['oid'];
			}
			$i++;
		}
		return $groups;
	}

	public function get_groupIDs_by_userID($uid){
		$groupIDs = array();
		$result = $this->db->query("SELECT * FROM group_users WHERE uid ='$uid'");
		while ($row=$this->db->fetchArray($result)) {
			$groupIDs[] = $row['gid'];
		}

		return $groupIDs;
	}

	public function join_group ($gid, $uid){
		$defualt_Phone = user()->get_defualt_Phone_number ($uid);
		$result = $this->add_user($gid, $uid, $defualt_Phone);
		return $result;		
	}

	public function get_group_oid($id){
		$result = $this->db->query("SELECT * FROM groups WHERE id ='$id' LIMIT 1");
		$row = $this->db->fetchArray($result);
		return $row[oid];
	}

	public function get_group_info_by_ids($ids){
		if($ids){
			$result = $this->db->query("SELECT * FROM groups WHERE id in(" . implode(',',$ids) .")");
			while($row = $this->db->fetchArray($result)){
				$group_info[$row['id']] = $row['name'];
			}
			return $group_info;
		}	
	}

	public function kickout_user($gid, $uid, $admin_id, $block){
		if($block){
			$this->db->execute("UPDATE group_users SET status='blocked' WHERE gid ='$gid' AND  uid ='$uid'");
		}else
			$this->leave_group($gid, $uid);
	}

	public function unblock_user($uid, $gid=null){
		if(! $gid)
			$gid = $this->id;
		$this->db->execute("UPDATE group_users SET status='member' WHERE gid ='$gid' AND  uid ='$uid'");
	}

	public function remove_invitation($phone, $gid=null){
		if(! $gid)
			$gid = $this->id;
		$result = $this->db->query("DELETE  FROM group_users WHERE gid ='$gid' AND default_phone = '$phone' LIMIT 1");
		return $result;
	}

	public function invite_phone($phone, $gid=null, $status='invited'){
		if(! $gid)
			$gid = $this->id;

		$uid = user()->get_uid_from_user_phone($phone);
		if(!$uid){
			$uid = user()->get_uid_from_phone($phone);
		}

		if(!$uid or $uid<1){
			// add invitition by phone
			$this->db->execute("INSERT INTO group_users (gid, default_phone, status) VALUES('$gid', '$phone', '$status');");			
		}elseif($uid && !$this->is_user_group_member($gid, $uid)){
			// invite existing user
			$this->db->execute("INSERT INTO group_users (gid, uid, default_phone, status) VALUES('$gid', '$uid', '$phone', '$status');");
		}
	}

	public function get_group_phones($gid){
		$result = $this->db->query("SELECT default_phone FROM group_users WHERE gid ='$gid' AND default_Phone IS NOT NULL");
		while($row = $this->db->fetchArray($result)){
			$phones[] = $row['default_phone'];
		}
		return array_unique($phones);	
	}

		public function get_groups_phones($oid){
			$gids = org()->get_organization_gids($oid);
			$result = $this->db->query("SELECT default_phone FROM group_users WHERE gid in(".implode(',',$gids).") AND default_Phone IS NOT NULL");
			while($row = $this->db->fetchArray($result)){
				$phones[] = $row['default_phone'];
		}
		return array_unique($phones);	
	}

	public function get_uids_by_gids($gids){
		if($gids){
			$result = $this->db->query("SELECT uid FROM group_users WHERE gid in(".implode(',',$gids).") AND (status='member' OR status='admin')");
			while ($row = $this->db->fetchArray($result)){
					$uids[] =  $row['uid'];
			}
			return $uids;
		} else return null;

	}

}