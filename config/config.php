<?php
//The main config file
define('BASE_URL', 'http://localhost/sca/');
define('ENV', 'live');
define('DEFAULT_MODULE', 'homepage');
define('DEFAULT_CONTROLLER', 'Homepage');
define('DEFAULT_METHOD', 'index');
define('APPPATH', dirname(dirname(__FILE__)).'/');
define('REQUEST_TYPE', $_SERVER['REQUEST_METHOD']);
define('MODULE_ASSETS_TRIGGER', '_module');