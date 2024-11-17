<?php
require_once 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['travel_id'])) {
    $travel_id = $_GET['travel_id'];

    // Delete comments associated with places in this travel
    $sqlDeleteComments = "DELETE c
                          FROM Comment c
                          INNER JOIN Place p ON c.placeID = p.id
                          WHERE p.travelID = ?";
    $stmt = mysqli_prepare($connection, $sqlDeleteComments);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delete likes associated with places in this travel
    $sqlDeleteLikes = "DELETE l
                       FROM `Like` l
                       INNER JOIN Place p ON l.placeID = p.id
                       WHERE p.travelID = ?";
    $stmt = mysqli_prepare($connection, $sqlDeleteLikes);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delete photos and places for this travel
    $sqlGetPlaces = "SELECT photoFileName FROM Place WHERE travelID = ?";
    $stmt = mysqli_prepare($connection, $sqlGetPlaces);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($place = mysqli_fetch_assoc($result)) {
        $photoPath = 'images/' . $place['photoFileName'];
        if (file_exists($photoPath)) {
            unlink($photoPath);  // Delete the photo file
        }
    }
    mysqli_stmt_close($stmt);

    // Now delete the places
    $sqlDeletePlaces = "DELETE FROM Place WHERE travelID = ?";
    $stmt = mysqli_prepare($connection, $sqlDeletePlaces);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Finally, delete the travel record itself
    $sqlDeleteTravel = "DELETE FROM Travel WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sqlDeleteTravel);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "Travel and related data deleted successfully.";

    // Redirect back to the user's homepage
    header("Location: UsersTravelPage.php?deleted=success");
    exit();
} else {
    header("Location: UsersTravelPage.php?deleted=fail");
    exit();
}
?>
