<?php
session_start();
require 'database.php';

header('Content-Type: application/json');

if (isset($_POST['placeID']) && isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $placeID = $_POST['placeID'];

    // Check if the user has already liked this place
    $checkQuery = $connection->prepare("SELECT 1 FROM `Like` WHERE userID = ? AND placeID = ?");
    $checkQuery->bind_param("ii", $userID, $placeID);
    $checkQuery->execute();
    if ($checkQuery->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'You have already liked this place.']);
        exit();
    }

    // Insert a new like record
    $insertQuery = $connection->prepare("INSERT INTO `Like` (userID, placeID) VALUES (?, ?)");
    $insertQuery->bind_param("ii", $userID, $placeID);
    if ($insertQuery->execute()) {
        // Fetch the updated like count
        $likeCountQuery = $connection->prepare("SELECT COUNT(*) AS likeCount FROM `Like` WHERE placeID = ?");
        $likeCountQuery->bind_param("i", $placeID);
        $likeCountQuery->execute();
        $likeCount = $likeCountQuery->get_result()->fetch_assoc()['likeCount'];

        echo json_encode([
            'success' => true,
            'likeCount' => $likeCount,
            'message' => "You liked this place successfully! Total likes: $likeCount"
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to like the place.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters.']);
}
?>
