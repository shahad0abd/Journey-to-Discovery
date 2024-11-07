<?php
session_start();
require 'database.php';

if (isset($_POST['placeID']) && isset($_POST['travelID']) && isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $placeID = $_POST['placeID'];
    $travelID = $_POST['travelID'];
    
    // Insert a like if it doesn't already exist
    $query = $connection->prepare("INSERT INTO `Like` (userID, placeID) SELECT ?, ? WHERE NOT EXISTS 
                                   (SELECT 1 FROM `Like` WHERE userID = ? AND placeID = ?)");
    $query->bind_param("iiii", $userID, $placeID, $userID, $placeID);
    $query->execute();

    // Redirect back to the travel details page
    header("Location: TravelDetails.php?travelID=" . $travelID);
    exit();
} else {
    echo "Error: Missing parameters.";
}
?>
