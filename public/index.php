<?php
echo "<h1>Public Folder is Reachable!</h1>";
echo "If you see this, the server routing is perfect.<br>";

// Now let's try to load the bootloader safely
echo "Attempting to load bootloader...<br>";

if (file_exists('../app/bootloader.php')) {
    require_once '../app/bootloader.php';
    echo "Bootloader loaded successfully!<br>";
} else {
    die("CRITICAL: Can't find ../app/bootloader.php");
}

// Check for the Core class
echo "Checking for Core library...<br>";
if (class_exists('Core')) {
    echo "Class 'Core' Found! Starting app...<br>";
    // $init = new Core(); // We keep this OFF for now
} else {
    echo "<span style='color:red'>Error: Class 'Core' NOT found. Autoloader is failing.</span><br>";
    
    // Debug: List what IS in libraries
    echo "<br><b>Debug: Listing app/libraries folder:</b><br>";
    $libs = scandir('../app/libraries');
    print_r($libs);
}
?>