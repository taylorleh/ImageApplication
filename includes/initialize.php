<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS . 'var' . DS . 'www'  . DS  . 'intranet');
defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT . DS . 'includes');

// config first

require_once (LIB_PATH . DS . 'config.php');

// basic functions
require_once (LIB_PATH . DS . 'functions.php');

// core objects
require_once (LIB_PATH . DS . 'session.php');
require_once (LIB_PATH . DS . 'database.php');
require_once (LIB_PATH . DS . 'database_object.php');

// Database-related classes

require_once (LIB_PATH . DS . 'user.php');
require_once (LIB_PATH . DS . 'vendor.php');
require_once (LIB_PATH . DS . 'useredits.php');

?>