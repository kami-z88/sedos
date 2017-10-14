<?php
class Organization{
	public $id;
	public $name;
	public $parent_id;
	protected static $instance = NULL;
	protected static $db1 = NULL;
	var $db;
	
	public static function instance($id=null) {
		if ( self::$instance == null ) {
			self::$instance = new self($id);
		}			
		return self::$instance;
	}

	function Organization($id=null){
		$this->db = new Database();
		$result = $this->db->query("SELECT * FROM organizations WHERE id ='$id' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) ){
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->parent_id = $row['parent_id'];
		}
		return $row;
	}

	public static function list_organizations(){
		self::$db1 = new Database();
		$result = self::$db1->query("SELECT * FROM organizations WHERE parent_id = 0 ");
		while ( $row=self::$db1->fetchArray($result) ){
			$data[$row['id']] = $row['name'] ;
		}
		return $data;
	}

	public function add($name, $parent_id=null ){
		$result = $this->db->query("INSERT INTO  organizations (name, parent_id) VALUES ('$name', '$parent_id')");
		$oid = $this->db->last_id();
		
		return $oid;
	}

	public function create($name, $uid, $parent_id=null){
		$oid = $this->add($name, $parent_id);
		$phone = user()->get_defualt_Phone_number ($uid); 
		if($parent_id){
			// add permissions based on uid
			$permissions = $this->get_user_permissions($uid, $parent_id);
			$permissions = implode(',', $permissions);
			$this->add_user($oid, $uid, $phone, $permissions);

		}else{
			// Add all permissions for this user
			global $org_permissions;
			$this->add_user($oid, $uid, $phone, implode(',', $org_permissions));
		}

		return $result;
	}

	public function add_user($id=null, $uid=null, $phone=null, $permissions = null){
		if(! $id)
			$id = $this->id;

		if(! $phone and $uid)
			$phone = user()->get_defualt_Phone_number ($uid);
		if(! $uid){
			$uid = user()->get_uid_from_user_phone($phone);
			if(! $uid){
				$uid = user()->get_uid_from_phone($phone);
			}
		}
		if(! $uid or $uid==0)
			$this->db->execute("INSERT INTO organization_users (oid,default_phone, permissions) VALUES ('$id', '$phone', '$permissions')");
		else
			$this->db->execute("INSERT INTO organization_users (oid,uid,default_phone, permissions) VALUES ('$id', '$uid', '$phone', '$permissions')");
	}

	public function get_user_permissions($uid, $oid=null){
		if(! $oid)
			$oid = $this->id;
		$result = $this->db->query("SELECT permissions FROM organization_users WHERE oid ='$oid' AND uid = '$uid'");
		while ( $row=$this->db->fetchArray($result) )
			$permissions = $row['permissions'];
		return explode(',', $permissions);
	}

	public function get_members($oid=null){
		if(! $oid)
			$oid = $this->id;
		$result = $this->db->query("SELECT * FROM organization_users WHERE oid ='$oid'");
		$rows = array();
		$i = 0;
		while($row = $this->db->fetchArray($result)){
			$users[$i]['id'] = $row['uid'];
			$users[$i]['phone'] = $row['default_phone'];
			$users[$i]['status'] = $row['status'];
			$i++;
		}
		return $users;
	}

	public function update($id, $name){
		$query = $this->db->execute("UPDATE organizations SET name='$name' WHERE id='$id';");
		$this->name = $name;

	}
	public function delete($id, $type = 'cascade'){
		// if it's type is cascade delete all groups and sub-organizations(with their own groups)

		if($type=="cascade"){
			$this->delete_groups($id);
			$subs = $this->get_sub_organizations($id);
			foreach ($subs as $key => $org) {
				$this->delete($org['id']);
			}
			$this->delete_organization($id);
		}elseif($type=="link"){
			$pid = $this->parent_id;
			if($pid > 0){
				$query = $this->db->execute("UPDATE groups SET oid='$pid' WHERE oid='$id';");
				$query = $this->db->execute("UPDATE organizations SET parent_id='$pid' WHERE parent_id='$id';");
				$this->delete_organization($id);
			}
		}
		// else set it's groups and sub-organization's parent id to this organization's parent id

	}

	public function delete_organization($oid=null){
		if(! $oid)
			$oid = $this->id;
		$this->db->execute("DELETE FROM organizations WHERE id ='$oid'");
	}

	public function delete_groups($oid=null){
		if(! $oid)
			$oid = $this->id;

		$this->db->execute("DELETE FROM groups WHERE oid ='$oid'");
	}

	public function get_groups($oid=null){
		if(! $oid)
			$oid = $this->id;
		$result = $this->db->query("SELECT * FROM groups WHERE oid ='$oid'");
		while ( $row=$this->db->fetchArray($result) )
			$groups[] = $row;
		return $groups;
	}

	public function get_sub_organizations($oid=null){
		if(! $oid)
			$oid = $this->id;
		$result = $this->db->query("SELECT * FROM organizations WHERE parent_id ='$oid'");
		while ( $row=$this->db->fetchArray($result) )
			$organizations[] = $row;
		return $organizations;
	}

	public function get_list($oid){
		$i=0;
		$result = $this->db->query("SELECT * FROM organizations WHERE id ='$oid'");
		while ( $row=$this->db->fetchArray($result) ){
			$organization[$i] = $row;
			$p_id = $row['parent_id']; 
			while ($p_id>0) {
				$i++;
				$organization[$i] = $this->get_organization($p_id);
				$p_id = $organization[$i]['parent_id'];
			}
		}
		return array_reverse ( $organization );
	}
	function get_organization($id=null){
		$this->db = new Database();
		$result = $this->db->query("SELECT * FROM organizations WHERE id ='$id' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) ){
			return $row;
		}
	}

	function get_organization_gids($oid){
		$ids[] = $oid;
		$this->db = new Database();
		$result = $this->db->query("SELECT  id FROM (SELECT * FROM organizations ORDER BY parent_id, id) products_sorted,(SELECT @pv := '$oid') initialisation WHERE   find_in_set(parent_id, @pv) > 0 AND     @pv := concat(@pv, ',', id)");
		while ($row = $this->db->fetchArray($result)) {
			$ids[] = $row['id'];
		}
		$result = $this->db->query("SELECT id FROM groups WHERE oid in (" . implode(',',$ids) ." )");
		while ($row = $this->db->fetchArray($result)) {
			$gids[] = $row['id'];
		}
		return $gids;			
	
	 }

}