<?php
// 1. Force Error Reporting (So we don't get blank pages)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Load the Bootloader
if (!file_exists('../app/bootloader.php')) {
    die("Critical Error: app/bootloader.php is missing.");
}
require_once '../app/bootloader.php';

// 3. Shim the URL (Crucial Fix for Railway without .htaccess)
// MVC apps expect $_GET['url']. We fake it here.
if (!isset($_GET['url'])) {
    // Get the request URI (e.g., /users/login)
    $url = $_SERVER['REQUEST_URI'];
    // Remove the leading slash
    $url = ltrim($url, '/');
    // Save it where Core.php expects it
    $_GET['url'] = $url;
}

// 4. Start the App
try {
    $init = new Core();
} catch (Throwable $e) {
    echo "<div style='background: #f8d7da; padding: 20px; font-family: sans-serif;'>";
    echo "<h2>Application Crash</h2>";
    echo "<b>Error:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " on line <b>" . $e->getLine() . "</b>";
    echo "</div>";
}
?>