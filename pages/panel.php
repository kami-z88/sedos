<?php
$uid = $content['user']['id'];
$user = new User($uid);
$content['user-organizations'] = $user->get_organizations_tree();
$content['permissions'] = get_user_current_permissions($uid, $oid, $gid);

$groups = group()->get_user_groups($uid);
foreach ($groups as $key => $group) {
	$content['raw-groups'][$key] = $group;
	$content['raw-groups'][$key]['users_count'] = group()->get_group_member_number($group['gid']);
	$content['raw-groups'][$key]['organization'] = org()->get_list($group['oid']);
}

$content["messages"] = msg()->get_user_messages($uid, 10);

function print_tree_html($arr){
	echo '<ul >';
		foreach ($arr as $key => $item) {
			if(count($item["children"])>0)
				echo '<li><span><i class="fa fa-minus"></i></span> <a href="' . get_link('organization', $item['id']) .  ' ">' . $item['name'] .  '</a>';
			else
				echo '<li><span><i class="fa fa-folder-o"></i></span> <a href="' . get_link('organization', $item['id']) .  ' ">' . $item['name'] .  '</a>';
			print_tree_html($item["children"]);
			echo '</li>';
		}
	echo '</ul>';
}

v(get_defined_functions());

