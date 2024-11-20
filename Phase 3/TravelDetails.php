<?php 
session_start();
require 'database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure travel ID is received as a POST or GET parameter
if (isset($_POST['travel_id'])) {
    $travelID = $_POST['travel_id'];
} elseif (isset($_GET['travelID'])) { 
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <button id="like-btn-<?php echo $place['placeID']; ?>" class="like-btn" 
    <?php echo userAlreadyLiked($connection, $userID, $place['placeID']) ? 'disabled' : ''; ?>
    onclick="likePlace(<?php echo $place['placeID']; ?>)">
    Like (<span id="like-count-<?php echo $place['placeID']; ?>"><?php echo $place['likes']; ?></span>)
</button>

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
    <textarea id="comment-<?php echo $place['placeID']; ?>" placeholder="Add a comment..." required></textarea>
    <button onclick="addComment(<?php echo $place['placeID']; ?>)">Add Comment</button>

    <div id="comments-<?php echo $place['placeID']; ?>">
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

    <script>
        // Function to add a comment via AJAX
        function addComment(placeID) {
    const comment = $(`#comment-${placeID}`).val();
    if (comment.trim() === '') {
        alert("Comment cannot be empty.");
        return;
    }

    $.ajax({
        url: "add_comment.php",
        type: "POST",
        data: { comment: comment, placeID: placeID },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                // Append the new comment to the comment list
                const commentHTML = `<p><strong>${response.userName}:</strong> ${response.comment}</p>`;
                $(`#comments-${placeID}`).append(commentHTML);

                // Clear the comment textarea
                $(`#comment-${placeID}`).val('');

                // Show success message
                alert(response.message);
            } else {
                alert("Failed to add comment: " + (response.error || "Unknown error."));
            }
        },
        error: function () {
            alert("An error occurred while adding the comment.");
        }
    });
}


        // Function to like a place via AJAX
       function likePlace(placeID) {
    $.ajax({
        url: "add_like.php",
        type: "POST",
        data: { placeID: placeID },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                // Update the like count on the button
                const likeButton = $(`#like-btn-${placeID}`);
                const likeCountElement = $(`#like-count-${placeID}`);

                // Update the button's like count
                likeCountElement.text(response.likeCount);

                // Disable the button and show "Liked"
                likeButton.prop("disabled", true).text(`Liked (${response.likeCount})`);

                // Show success message
                alert(response.message);
            } else {
                alert("Failed to like the place: " + (response.error || "Unknown error."));
            }
        },
        error: function (xhr, status, error) {
            alert("An unexpected error occurred: " + error);
        }
    });
}

    </script>
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
