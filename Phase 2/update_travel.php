<?php
session_start();
require 'database.php';

echo "Starting update process...<br>";

// Check for required data
if (!isset($_POST['travelID'], $_POST['month'], $_POST['year'], $_POST['country'])) {
    die("Error: Missing required data.");
}

$travelID = $_POST['travelID'];
$month = $_POST['month'];
$year = $_POST['year'];
$countryID = $_POST['country'];

// Update travel details in the Travel table
$updateTravelQuery = $connection->prepare("UPDATE Travel SET month = ?, year = ?, countryID = ? WHERE id = ?");
$updateTravelQuery->bind_param("siii", $month, $year, $countryID, $travelID);
if ($updateTravelQuery->execute()) {
    echo "Travel details updated successfully.<br>";
} else {
    die("Failed to update travel details.");
}

// Process each place update
if (isset($_POST['places']) && is_array($_POST['places'])) {
    foreach ($_POST['places'] as $placeID => $placeData) {
        $name = $placeData['name'];
        $location = $placeData['location'];
        $description = $placeData['description'];
        $photoFileName = null;

        // Check if a new photo was uploaded for this place
        if (isset($_FILES['places']['name'][$placeID]['photo']) && $_FILES['places']['error'][$placeID]['photo'] == 0) {
            $photoFileName = basename($_FILES['places']['name'][$placeID]['photo']);
            $photoPath = 'images/' . $photoFileName;

            // Move the uploaded file to the images directory
            if (move_uploaded_file($_FILES['places']['tmp_name'][$placeID]['photo'], $photoPath)) {
                echo "Photo uploaded for place ID $placeID.<br>";
            } else {
                echo "Failed to upload photo for place ID $placeID.<br>";
                $photoFileName = null;
            }
        } else {
            // Keep the existing photo if no new photo was uploaded
            $photoFileName = $placeData['existing_photo'] ?? null;
        }

        // Update place information
        if ($photoFileName !== null) {
            $updatePlaceQuery = $connection->prepare("UPDATE Place SET name = ?, location = ?, description = ?, photoFileName = ? WHERE id = ?");
            $updatePlaceQuery->bind_param("ssssi", $name, $location, $description, $photoFileName, $placeID);
        } else {
            $updatePlaceQuery = $connection->prepare("UPDATE Place SET name = ?, location = ?, description = ? WHERE id = ?");
            $updatePlaceQuery->bind_param("sssi", $name, $location, $description, $placeID);
        }

        if ($updatePlaceQuery->execute()) {
            echo "Place ID $placeID updated successfully.<br>";
        } else {
            echo "Failed to update place ID $placeID.<br>";
        }
    }
}

// Redirect to the user's homepage
header("Location: users_homepage.php");
exit();
?>
