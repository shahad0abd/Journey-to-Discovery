<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
require 'database.php';

if ($connection) {
    echo "Connected successfully!";
} else {
    echo "Connection failed!";
}
?>
