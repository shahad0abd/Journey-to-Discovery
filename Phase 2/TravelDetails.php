<?php 
session_start();
require 'database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure travel ID is received as a POST parameter
if (isset($_POST['travel_id'])) {
    $travelID = $_POST['travel_id'];
} elseif (isset($_GET['travelID'])) { // Optional: if you want to support GET for direct links
    $travelID = $_GET['travelID'];
} else {
    die("Travel ID not specified.");
}

$userID = $_SESSION['user_id'];

// Retrieve travel and user details
$travelQuery = $connection->prepare("SELECT T.id AS travelID, U.firstName, U.lastName, U.photoFileName, C.country, T.month, T.year 
                                     FROM Travel T
                                     JOIN User U ON T.userID = U.id
                                     JOIN Country C ON T.countryID = C.id
                                     WHERE T.id = ?");
$travelQuery->bind_param("i", $travelID);
$travelQuery->execute();
$travel = $travelQuery->get_result()->fetch_assoc();

if (!$travel) {
    die("Travel not found.");
}

// Set default photo if none exists
$travelerPhotoPath = !empty($travel['photoFileName']) && file_exists("images/" . $travel['photoFileName'])
    ? "images/" . $travel['photoFileName']
    : "images/defaultphoto.jpg";

// Retrieve places and likes for each place
$placesQuery = $connection->prepare("SELECT P.id AS placeID, P.name, P.location, P.description, P.photoFileName,
                                     (SELECT COUNT(*) FROM `Like` WHERE placeID = P.id) AS likes
                                     FROM Place P WHERE P.travelID = ?");
$placesQuery->bind_param("i", $travelID);
$placesQuery->execute();
$places = $placesQuery->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Details</title>
    <link rel="stylesheet" href="travel_details.css">
</head>
<body>
    <header>
        <h1>Travel Details</h1>
    </header>
    <main>
        <!-- Traveler Info -->
        <section class="traveler-info">
            <img src="<?php echo htmlspecialchars($travelerPhotoPath); ?>" alt="Traveler Photo" class="traveler-photo">
            <div class="traveler-details">
                <h2><?php echo htmlspecialchars($travel['firstName'] . " " . $travel['lastName']); ?></h2>
                <p>Country: <?php echo htmlspecialchars($travel['country']); ?></p>
                <p>Travel Time: <?php echo htmlspecialchars($travel['month'] . " " . $travel['year']); ?></p>
            </div>
        </section>

        <!-- Places Visited -->
        <section class="visited-places">
            <?php foreach ($places as $place): ?>
                <div class="place">
                    <!-- Like Button -->
                    <form action="add_like.php" method="post" style="display: inline;">
                        <input type="hidden" name="placeID" value="<?php echo htmlspecialchars($place['placeID']); ?>">
                        <input type="hidden" name="travelID" value="<?php echo htmlspecialchars($travelID); ?>">
                        <button type="submit" class="like-btn" <?php echo userAlreadyLiked($connection, $userID, $place['placeID']) ? 'disabled' : ''; ?>>
                            Like (<span id="like-count-<?php echo $place['placeID']; ?>"><?php echo $place['likes']; ?></span>)
                        </button>
                    </form>

                    <h3><?php echo htmlspecialchars($place['name']); ?></h3>

                    <!-- Conditionally display place photo -->
                    <?php
                    $photoFilePath = "images/" . $place['photoFileName'];
                    if (!empty($place['photoFileName']) && file_exists($photoFilePath)) {
                        echo "<img src='" . htmlspecialchars($photoFilePath) . "' alt='Place Photo' class='place-photo'>";
                    } else {
                        echo "<p>No photo available</p>";
                    }
                    ?>

                    <p><strong>Location:</strong> <?php echo htmlspecialchars($place['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($place['description']); ?></p>

                    <!-- Comment Section -->
                    <div class="comment-section">
                        <form action="add_comment.php" method="post" class="comment-form">
                            <input type="hidden" name="travelID" value="<?php echo $travelID; ?>">
                            <input type="hidden" name="placeID" value="<?php echo $place['placeID']; ?>">
                            <textarea name="comment" placeholder="Add a comment..." required></textarea>
                            <button type="submit">Add Comment</button>
                        </form>

                        <div class="comment-list">
                            <?php
                            $commentQuery = $connection->prepare("SELECT U.firstName, C.comment 
                                                                 FROM Comment C 
                                                                 JOIN User U ON C.userID = U.id 
                                                                 WHERE C.placeID = ?");
                            $commentQuery->bind_param("i", $place['placeID']);
                            $commentQuery->execute();
                            $comments = $commentQuery->get_result();

                            while ($comment = $comments->fetch_assoc()):
                            ?>
                                <p><strong><?php echo htmlspecialchars($comment['firstName']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></p>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>

<?php
// Function to check if the user has already liked a specific place
function userAlreadyLiked($connection, $userID, $placeID) {
    $query = $connection->prepare("SELECT * FROM `Like` WHERE userID = ? AND placeID = ?");
    $query->bind_param("ii", $userID, $placeID);
    $query->execute();
    return $query->get_result()->num_rows > 0;
}
?>
