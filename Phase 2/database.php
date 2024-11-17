<?php

$servername = "localhost";
$username = "root";
$password = "root";
$database = "Journey_to_discovery";

$connection = mysqli_connect($servername, $username, $password, $database);

if(mysqli_connect_errno())
    die("Connection failed: " .mysqli_connect_error());

  
?>