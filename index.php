<?php
// ROOT index.php Debugger
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Step 1: Root index.php started.<br>";

// Check if public folder exists
if (!is_dir('public')) {
    die("Error: Public folder not found!");
}
echo "Step 2: Public folder found.<br>";

// Enter public folder
chdir('public');
echo "Step 3: Entered public folder.<br>";

// Check if public/index.php exists
if (!file_exists('index.php')) {
    die("Error: public/index.php not found!");
}
echo "Step 4: Found public/index.php. Attempting to require it...<br>";

// Require the file
require_once 'index.php';

echo "<br>Step 5: public/index.php finished successfully.";
?>