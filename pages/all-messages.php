<?php
$uid = $content['user']['id'];
$user = new User($uid);


$content["messages"] = msg()->get_user_messages($uid);


require BASE_DIR . 'view/header.php';
require 'view/all-messages.php';
require BASE_DIR . 'view/footer.php';