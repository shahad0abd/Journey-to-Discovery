<?php
require 'database.php';

// Set header to return JSON
header('Content-Type: application/json');

// Get the country filter from the request
$countryID = $_GET['country'] ?? 'all';

$query = "SELECT t.id, t.month, t.year, u.firstName, c.country, 
                 (SELECT COUNT(*) FROM `like` l WHERE l.placeID = p.id) as totalLikes,
                 p.photoFileName AS placePhotoFileName
          FROM travel t
          JOIN user u ON t.userID = u.id
          JOIN country c ON t.countryID = c.id
          LEFT JOIN place p ON p.travelID = t.id";

if ($countryID !== 'all') {
    $query .= " WHERE t.countryID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $countryID);
} else {
    $stmt = $connection->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

$travels = [];
while ($row = $result->fetch_assoc()) {
    $travels[] = $row;
}

echo json_encode($travels);
?>
