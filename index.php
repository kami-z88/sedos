<?php

define('BASE_DIR', dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/');
define('VERSION', '1.0.0'); 

require_once BASE_DIR.'config.php';
require_once BASE_DIR.'functions.php';
require_once BASE_DIR.'modules/db/db.php';
require_once BASE_DIR.'modules/datetime/jdatetime.class.php';
require_once BASE_DIR.'classes/user.php';
require_once BASE_DIR.'classes/group.php';
require_once BASE_DIR.'classes/organization.php';
require_once BASE_DIR.'classes/message.php';


require BASE_DIR.'pages/base.php';