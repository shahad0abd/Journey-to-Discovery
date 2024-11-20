<?php
require_once 'database.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit();
}

if (isset($_GET['travel_id'])) {
    $travel_id = $_GET['travel_id'];

    
    $sqlDeleteComments = "DELETE c FROM Comment c INNER JOIN Place p ON c.placeID = p.id WHERE p.travelID = ?";
    $stmt = mysqli_prepare($connection, $sqlDeleteComments);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    
    $sqlDeleteLikes = "DELETE l FROM `Like` l INNER JOIN Place p ON l.placeID = p.id WHERE p.travelID = ?";
    $stmt = mysqli_prepare($connection, $sqlDeleteLikes);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    
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

    
    $sqlDeletePlaces = "DELETE FROM Place WHERE travelID = ?";
    $stmt = mysqli_prepare($connection, $sqlDeletePlaces);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    
    $sqlDeleteTravel = "DELETE FROM Travel WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sqlDeleteTravel);
    mysqli_stmt_bind_param($stmt, "i", $travel_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($connection);
    echo json_encode(['success' => $success]);
    exit();
} else {
    echo json_encode(['success' => false]);
    exit();
}
?>
