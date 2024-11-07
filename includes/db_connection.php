<?php 
    $host= 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'journey_to_discovery';

    $conn = new mysqli($host, $username, $password, $database, 3306);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
?>

