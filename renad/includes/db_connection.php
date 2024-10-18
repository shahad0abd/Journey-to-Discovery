<?php 
    // Connect to the database (here we are going to put our database credentials)
	
    $host= 'localhost'; //Mysql Host
	
	$username = 'root'; //Mysql UserName
	
	$password = ''; //MYsql Password
	
	$database = 'Journey_to_discovery'; //Database Name
	
    $conn = new mysqli($host, $username, $password, $database,3306);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
