<?php
class Message{
	public $id;
	public $gid;
	public $uid;
	public $root_oid;
	public $text;
	public $date;
	protected static $instance = NULL;
	var $db;
	
	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}			
		return self::$instance;
	}

	function Message($id=null){
		$this->db = new Database();
		$result = $this->db->query("SELECT * FROM messages WHERE id ='$id' LIMIT 1");
		while ( $row=$this->db->fetchArray($result) ){
			$this->id = $row['id'];
			$this->gid = $row['gid'];
			$this->uid = $row['uid'];
			$this->root_oid = $row['root_oid'];
			$this->text = $row['text'];
			$this->date = $row['date'];
		}
		return $row;
	}

	public function create($gid,$uid,$root_oid,$text){
		$text = escape($text);
		if($root_oid){
			$org = new Organization();
			$gids = $org->get_organization_gids($root_oid);
			foreach ($gids as $gid) {
				$results[] = $this->db->query("INSERT INTO messages(gid, uid, root_oid, text) VALUES($gid, $uid, $root_oid, '$text')");	
			}
			return $results;
		} else {
			$result = $this->db->query("INSERT INTO messages(gid, uid, text) VALUES($gid, $uid, '$text')");	
			return $result;		
		}
	}

	public function update(){}
	public function delete(){}


	public function get_group_messages($gid){
		$date = new jDateTime(true, true, 'Asia/Tehran');
		$user = new User();
		$result = $this->db->query("SELECT * FROM messages WHERE gid ='$gid' ORDER BY date DESC ");
		while ( $row=$this->db->fetchArray($result) ){
			$dt = new DateTime($row['date']);
			$row['date']= $date->date("l j F Y H:i", $dt->format( 'U' ));
			$row['sender-name'] = ($user->getBaseUser($row['uid']))['name'];
			$messages[] = $row;
			
		}
		return $messages;
	}

		public static function get_group_message_number($gid){
			$db1 = new Database();
			$result = $db1->query("SELECT COUNT(gid) FROM messages WHERE gid ='$gid'");
			$row=$db1->fetchArray($result);
			return $row["COUNT(gid)"];
	}

	public function get_user_messages($uid, $limit = null){
		$date = new jDateTime(true, true, 'Asia/Tehran');
		$user = new User();
		$result = $this->db->query("SELECT gid FROM group_users WHERE uid ='$uid' AND status='member' OR status='admin'");
		while ( $row=$this->db->fetchArray($result) ){
			$groups_ids[] = $row['gid'];
		}
		if($groups_ids){
			$result = $this->db->query("SELECT * FROM groups WHERE id IN (" . implode(',', $groups_ids) . ")");
			while ( $row=$this->db->fetchArray($result) ){
			$groups[$row['id']] = $row['name'];
			}
		
			$query = "SELECT * FROM messages WHERE gid IN (" . implode(',', $groups_ids) . ") ORDER BY date DESC";
			if($limit > 0)
				$query .= " LIMIT $limit";
			$result = $this->db->query($query);
			while ( $row=$this->db->fetchArray($result) ){
				$dt = new DateTime($row['date']);
				$row['date'] = $date->date("l j F Y H:i", $dt->format( 'U' ));
				$row['group_name'] = $groups[$row['gid']];
				$row['sender-name'] = ($user->getBaseUser($row['uid']))['name'];
				$messages[] = $row;
			}
		}
		return $messages;
	}

}