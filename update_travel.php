<?php 
session_start();
require 'database.php';

// Ensure travel ID is received
if (!isset($_POST['travelID'])) {
    die("Travel ID not specified.");
}

$travelID = $_POST['travelID'];
$month = $_POST['month'];
$year = $_POST['year'];
$countryID = $_POST['country'];
$photoPath = null;

// Check if a new photo was uploaded
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    // Define path and move the uploaded file
    $photoPath = 'images/' . basename($_FILES['photo']['name']);
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
        echo "File uploaded successfully: $photoPath<br>";
    } else {
        echo "File upload failed.<br>";
    }
} else {
    echo "No new photo uploaded.<br>";
}

// Update travel details in the Travel table
$updateTravelQuery = $connection->prepare("UPDATE Travel SET month = ?, year = ?, countryID = ? WHERE id = ?");
$updateTravelQuery->bind_param("siii", $month, $year, $countryID, $travelID);
if ($updateTravelQuery->execute()) {
    echo "Travel details updated successfully.<br>";
} else {
    echo "Travel details update failed: " . $updateTravelQuery->error . "<br>";
}

// Update the Place table if a new photo was uploaded
if ($photoPath) {
    $placeQuery = $connection->prepare("SELECT id FROM Place WHERE travelID = ? LIMIT 1");
    $placeQuery->bind_param("i", $travelID);
    $placeQuery->execute();
    $placeResult = $placeQuery->get_result();
    
    if ($placeRow = $placeResult->fetch_assoc()) {
        $placeID = $placeRow['id'];
        $updatePlaceQuery = $connection->prepare("UPDATE Place SET photoFileName = ? WHERE id = ?");
        $updatePlaceQuery->bind_param("si", $photoPath, $placeID);
        
        if ($updatePlaceQuery->execute()) {
            echo "Photo path updated in database for place ID $placeID: $photoPath<br>";
        } else {
            echo "Photo path update failed for place ID $placeID: " . $updatePlaceQuery->error . "<br>";
        }
    } else {
        echo "No place found for travel ID $travelID.<br>";
    }
} else {
    echo "No photo update required.<br>";
}

// Redirect to the user's homepage (comment out for debugging)
header("Location: users_homepage.php");
exit();
?>
