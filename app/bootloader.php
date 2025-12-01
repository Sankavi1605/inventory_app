<?php
// 1. Load Composer Libraries (for third-party tools)
// We use dirname(__DIR__) to go up one level from 'app' to 'vendor'
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

// 2. Load Config
require_once 'config/config.php';

// 3. Load Helpers (Add any other helpers you have here)
// Check if file exists before requiring to prevent errors
if (file_exists(__DIR__ . '/helpers/url_helper.php')) require_once 'helpers/url_helper.php';
if (file_exists(__DIR__ . '/helpers/session_helper.php')) require_once 'helpers/session_helper.php';

// 4. MANUAL AUTOLOADER (The Magic Fix)
// This tells PHP: "If you can't find a class, look in these folders!"
spl_autoload_register(function($className){
    // List of folders where your classes live
    $folders = [
        'libraries/',
        'models/',
        'controllers/'
    ];

    // Loop through folders and try to load the file
    foreach($folders as $folder) {
        $file = __DIR__ . '/' . $folder . $className . '.php';
        if(file_exists($file)){
            require_once $file;
            return; // Stop looking once found
        }
    }
});