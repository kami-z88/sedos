<?php
function v($data){
	print("<pre>");
	var_dump($data);
	print("</pre>");
}

function trans($text){
	if(LANG[$text])
		return LANG[$text];
	else
		return $text;
}
function ptrans($text){
	if(LANG[$text])
		echo LANG[$text];
	else
		echo $text;
}

function db() {
	require_once BASE_DIR.'modules/db/db.php';
	return Database::instance();
}

function user() {
	require_once BASE_DIR.'classes/user.php';
	return User::instance();
}
function group($gid=null) {
	require_once BASE_DIR.'classes/group.php';
	return Group::instance($gid);
}
function org($oid=null) {
	require_once BASE_DIR.'classes/organization.php';
	return Organization::instance($oid);
}
function msg() {
	require_once BASE_DIR.'classes/message.php';
	return Message::instance();
}

function get_link($page = null, $subpage = null, $parameters = null) {
	if(empty($page) || $page=='#')
		$path = SITE_URL;
	else{
		if(PRETTY_URLS){
			$path = SITE_URL . $page.'/';
			if($subpage) $path .= $subpage.'/';
		}else{
			$path = '';
			if(! empty($subpage))
				$path = '&req2='.$subpage;
			$path = '?req1=' . $page . $path;
		}
	}
	if(is_array($parameters)){
		$param = '?';
		foreach ($parameters as $key => $value) {
			$param .= $key . '=' . $value;
			if(next($parameters)){$param .= '&';}
		}
		$path .= $param;
	}
	return $path;
}

function get_paths() {
	if(PRETTY_URLS){
		$url = @$_GET["req1"];
		$path = explode('/',$url);

	}else{
		$url = @$_GET['req1'] . '/' . @$_GET['req2'];
		$path[1] = @$_GET['p'];
		$path[2] = @$_GET['s'];
	}
	return $path;
}

function get_path($request) {
	$pages = get_paths();
	return $pages[$request];
}

function validate_phone($number){
	if(strlen($number) != 11){ $number = "0".$number; }
	if (strlen($number)==11 and is_int((int)$number) and substr($number,0,2)=='09' )
		return $number;
	return false;
}

function redirect($url, $permanent = false)
{
	header('Location: ' . $url, true, $permanent ? 301 : 302);
	die();
}


function escape($input){
	$db = new Database();
	$link = $db->link;
	$output = mysqli_real_escape_string($link, $input);
	return $output;
}


function gregorian_to_jalali ($g_y, $g_m, $g_d,$str) 
{ 
	$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
	$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29); 
 
	
	$gy = $g_y-1600; 
	$gm = $g_m-1; 
	$gd = $g_d-1; 
 
	$g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400); 
 
	for ($i=0; $i < $gm; ++$i) 
		$g_day_no += $g_days_in_month[$i]; 
	if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0))) 
		/* leap and after Feb */ 
		$g_day_no++; 
	$g_day_no += $gd; 
 
	$j_day_no = $g_day_no-79; 
 
	$j_np = div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */ 
	$j_day_no = $j_day_no % 12053; 
 
	$jy = 979+33*$j_np+4*div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */ 
 
	$j_day_no %= 1461; 
 
	if ($j_day_no >= 366) { 
		$jy += div($j_day_no-1, 365); 
		$j_day_no = ($j_day_no-1)%365; 
	} 
 
	for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) 
		$j_day_no -= $j_days_in_month[$i]; 
	$jm = $i+1; 
	$jd = $j_day_no+1; 
	if($str) return $jy.'/'.$jm.'/'.$jd ;
	return array($jy, $jm, $jd); 
} 

function buildTree($flat, $pidKey, $idKey = null)
{
    $grouped = array();
    foreach ($flat as $sub){
        $grouped[$sub[$pidKey]][] = $sub;
    }

    $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
        foreach ($siblings as $k => $sibling) {
            $id = $sibling[$idKey];
            if(isset($grouped[$id])) {
                $sibling['children'] = $fnBuilder($grouped[$id]);
            }
            $siblings[$k] = $sibling;
        }

        return $siblings;
    };

    $tree = $fnBuilder($grouped[0]);

    return $tree;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// sanetize data to prevent XSS
function xssafe($data,$encoding='UTF-8')
{
   return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding);
}
function xecho($data)
{
   echo xssafe($data);
}

function has_permission($object, $action, $id=null){
	
}

function get_sms_fixed_lenght($message, $mid){
	/* Shorten text messages to fit a single SMS*/
	if(strlen($message) > MAX_SMS_LENGHT){
		$url = " " . get_link('s',$mid);
		$message = substr ( $message, 0, MAX_SMS_LENGHT - strlen($url) ) . $url;
	}
	return $message;
}

function get_user_current_permissions($uid, $oid, $gid) {
	$org = new Organization();
	$group = new Group();
	$user = new User($uid);
	$permissions = [];

	$permissions = $user->permissions;
	if($oid){
		$permissions = array_merge($permissions, $org->get_user_permissions($uid, $oid));
	}
	if($gid) {
		$permissions = array_merge($permissions, $group->get_user_permissions($uid, $gid));
	}
	return $permissions;
}