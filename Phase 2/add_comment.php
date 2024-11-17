<?php
session_start();
require 'database.php';

if (isset($_POST['comment']) && isset($_POST['placeID']) && isset($_POST['travelID']) && isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $placeID = $_POST['placeID'];
    $comment = $_POST['comment'];
    $travelID = $_POST['travelID'];

    // Insert the comment
    $query = $connection->prepare("INSERT INTO Comment (userID, placeID, comment) VALUES (?, ?, ?)");
    $query->bind_param("iis", $userID, $placeID, $comment);
    $query->execute();

    // Redirect back to the travel details page
    header("Location: TravelDetails.php?travelID=" . $travelID);
    exit();
} else {
    echo "Error: Missing parameters.";
}

?>
