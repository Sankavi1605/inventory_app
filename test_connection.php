<?php
echo "<h1>Railway Connection Debugger</h1>";

// 1. Check if variables exist
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD'); // This is the one failing
$port = getenv('DB_PORT');

echo "<b>Environment Variables Check:</b><br>";
echo "DB_HOST: " . ($host ? "FOUND (Ends in ...ipv6...)" : "MISSING") . "<br>";
echo "DB_USER: " . ($user ? $user : "MISSING") . "<br>";
echo "DB_PORT: " . ($port ? $port : "MISSING") . "<br>";

// We hide the actual password for security, just check length
echo "DB_PASSWORD: " . (strlen($pass) > 0 ? "FOUND (Length: " . strlen($pass) . ")" : "<span style='color:red'>MISSING / EMPTY</span>") . "<br>";

echo "<hr>";

// 2. Try Raw Connection
echo "<b>Attempting Connection...</b><br>";
$conn = new mysqli($host, $user, $pass, getenv('DB_NAME'), $port);

if ($conn->connect_error) {
    echo "<span style='color:red'>Connection Failed: " . $conn->connect_error . "</span>";
} else {
    echo "<span style='color:green'>SUCCESS! Connected to database.</span>";
}
?>