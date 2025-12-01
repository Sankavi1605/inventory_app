<?php



//database configuration

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'inventory');

// Debug mode
define('DEBUG_MODE', getenv('DEBUG_MODE') === 'true' ? true : false);

// Error reporting
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}


//APPROOT
define('APPROOT', dirname(dirname(__FILE__)));


//URLROOT
define('URLROOT', getenv('APP_URL') ?: 'http://localhost/inventory');


//WEBSITE name
define('SITENAME', 'inventory');


require_once APPROOT . '/helpers/url_helper.php';
require_once APPROOT . '/helpers/session_helper.php';
