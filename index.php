<?php
// This file acts as a gateway.
// It tells the server: "Go into the public folder and run the real site."

// 1. Change the current directory to 'public' so all your links work
chdir('public');

// 2. Load the actual index file
require_once 'index.php';
?>