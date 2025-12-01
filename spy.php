<?php
echo "<h1>File System Spy</h1>";

// 1. Check Root Folder
echo "<h3>Root Folder Content:</h3>";
$files = scandir(__DIR__);
foreach($files as $file) {
    if($file == '.' || $file == '..') continue;
    echo $file . "<br>";
}

// 2. Check App Folder
echo "<h3>App Folder Content:</h3>";
if (is_dir(__DIR__ . '/app')) {
    $appFiles = scandir(__DIR__ . '/app');
    foreach($appFiles as $file) {
        if($file == '.' || $file == '..') continue;
        echo $file . "<br>";
    }
} else {
    echo "<span style='color:red'>CRITICAL: 'app' folder is MISSING!</span>";
}

// 3. Check Models Folder (The one that failed earlier)
echo "<h3>App/Models Folder Content:</h3>";
if (is_dir(__DIR__ . '/app/models')) {
    $modelFiles = scandir(__DIR__ . '/app/models');
    foreach($modelFiles as $file) {
        echo $file . "<br>";
    }
} else {
    echo "<span style='color:red'>CRITICAL: 'app/models' folder is MISSING!</span><br>";
    echo "Checking for 'Models' (Capital M)... ";
    if (is_dir(__DIR__ . '/app/Models')) {
        echo "<span style='color:green'>FOUND 'Models' (Capitalized)!</span>";
    } else {
        echo "Not found.";
    }
}
?>