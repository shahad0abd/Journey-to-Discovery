<?php

require_once 'database.php';
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['travel_id'])) {
    $travel_id = $_POST['travel_id'];
} else {
    echo "Error: Travel ID not found.2";
    exit;
}
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $isDone = $_POST['isDone'];
    
    
    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $photoFileName = $_FILES['photoFileName'];
    
    if (isset($_FILES['photoFileName']) && $_FILES['photoFileName']['error'] == 0) {
    $photoFileName = $_FILES['photoFileName'];
    $photoPath = 'images/' . basename($photoFileName['name']);
    move_uploaded_file($photoFileName['tmp_name'], $photoPath);
}
    
    $stmtInsert = mysqli_prepare($connection, "INSERT INTO Place (travelID, name, location, description, photoFileName) VALUES (?,?,?,?,?)");
    
    if($stmtInsert === false){
    die("Error preparing insert statement: ". mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmtInsert, "issss", $travel_id, $name, $location, $description, $photoPath);
    mysqli_stmt_execute($stmtInsert);
    
    echo "Insert executed. Rows affected: " . mysqli_stmt_affected_rows($stmtInsert);
    
    if(mysqli_stmt_affected_rows($stmtInsert) > 0){
        if($isDone === "yes"){
            echo "Redirecting to user's homepage...";
            mysqli_stmt_close($stmtInsert);
            header("Location: User'shomepage.php?added=success");
            exit();}
        else{
            echo "Redirecting to add another place...";
            mysqli_stmt_close($stmtInsert);
            header("Location: AddNewTravel2.php?travel_id=" . urlencode($travel_id));
            exit();  
        }
    }else{
        mysqli_stmt_close($stmtInsert);
        echo "Insert failed. Redirecting to user's homepage with error...";
        header("Location: User'shomepage.php?added=fail");
        exit();
    }
    

}
mysqli_close($connection);
?>