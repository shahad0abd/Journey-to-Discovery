<?php
require_once 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['travel_id'])) {
    $travel_id = $_POST['travel_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    $photoFileName = null;
    if (isset($_FILES['photoFileName']) && $_FILES['photoFileName']['error'] == 0) {
        // Store only the filename in the database
        $photoFileName = basename($_FILES['photoFileName']['name']);
        $photoPath = 'images/' . $photoFileName;
        
        // Move the uploaded file to the images directory
        if (!move_uploaded_file($_FILES['photoFileName']['tmp_name'], $photoPath)) {
            echo "Failed to upload photo.";
            exit();
        }
    }

    // Save only the filename in the database
    $stmtInsert = $connection->prepare("INSERT INTO Place (travelID, name, location, description, photoFileName) VALUES (?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("issss", $travel_id, $name, $location, $description, $photoFileName);
    if ($stmtInsert->execute()) {
        if ($_POST['isDone'] === "yes") {
            header("Location: users_homepage.php?added=success");
        } else {
            header("Location: AddNewTravel2.php?travel_id=" . urlencode($travel_id));
        }
        exit();
    } else {
        echo "Failed to add place.";
    }
}
?>
