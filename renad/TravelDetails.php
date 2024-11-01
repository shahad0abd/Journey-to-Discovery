<?php
// Start session and include database connection
session_start();
include 'database_connection.php';

// Get travel ID from query string
if (!isset($_GET['travelID'])) {
    die("Travel ID not specified.");
}

$travelID = $_GET['travelID'];

// Retrieve travel details, places, likes, and comments from the database
// (Replace table and column names based on your schema)
$query = $conn->prepare("SELECT T.id AS travelID, U.firstName, U.lastName, U.photoFileName, C.country, T.month, T.year 
                         FROM Travel T
                         JOIN User U ON T.userID = U.id
                         JOIN Country C ON T.countryID = C.id
                         WHERE T.id = ?");
$query->bind_param("i", $travelID);
$query->execute();
$travel = $query->get_result()->fetch_assoc();

$query = $conn->prepare("SELECT P.id AS placeID, P.name, P.location, P.description, P.photoFileName,
                         (SELECT COUNT(*) FROM `Like` WHERE placeID = P.id) AS likes
                         FROM Place P WHERE P.travelID = ?");
$query->bind_param("i", $travelID);
$query->execute();
$places = $query->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Details</title>
    <style>
        /* Embedded CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #F0EAD2;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            background-color: #7F5539;
            color: #fbf6f6;
            padding: 20px;
            width: 100%;
            text-align: center;
        }
        main {
            width: 80%;
            margin: 20px auto;
            background-color: #fbf6f6;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .traveler-info, .place, .comment-section, .like-btn {
            /* Your additional styles here */
        }
        /* More CSS based on your original styles */
    </style>
</head>
<body>
    <header>
        <h1>Travel Details</h1>
    </header>
    <main>
        <section class="traveler-info">
            <img src="images/<?php echo htmlspecialchars($travel['photoFileName']); ?>" alt="Traveler Photo" class="traveler-photo">
            <div class="traveler-details">
                <h2><?php echo htmlspecialchars($travel['firstName'] . " " . $travel['lastName']); ?></h2>
                <p>Country: <?php echo htmlspecialchars($travel['country']); ?></p>
                <p>Travel Time: <?php echo htmlspecialchars($travel['month'] . " " . $travel['year']); ?></p>
            </div>
        </section>

        <section class="visited-places">
            <?php foreach ($places as $place): ?>
                <div class="place">
                    <button class="like-btn" data-place-id="<?php echo $place['placeID']; ?>">
                        Like (<span id="like-count-<?php echo $place['placeID']; ?>"><?php echo $place['likes']; ?></span>)
                    </button>
                    <h3><?php echo htmlspecialchars($place['name']); ?></h3>
                    <img src="images/<?php echo htmlspecialchars($place['photoFileName']); ?>" alt="Place Photo" class="place-photo">
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($place['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($place['description']); ?></p>

                    <div class="comment-section">
                        <form id="comment-form-<?php echo $place['placeID']; ?>" class="comment-form">
                            <textarea id="new-comment-<?php echo $place['placeID']; ?>" placeholder="Add a comment..." required></textarea>
                            <button type="submit">Add Comment</button>
                        </form>
                        
                        <div class="comment-list" id="comment-list-<?php echo $place['placeID']; ?>">
                            <?php
                            // Fetch comments for each place
                            $commentQuery = $conn->prepare("SELECT U.firstName, C.comment 
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
        // JavaScript for handling AJAX requests for likes and comments
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const placeID = this.getAttribute('data-place-id');

                    fetch('add_like.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ placeID: placeID })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`like-count-${placeID}`).textContent = data.likes;
                            this.disabled = true;
                        } else {
                            alert(data.message || 'Unable to add like.');
                        }
                    });
                });
            });

            document.querySelectorAll('.comment-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const placeID = this.id.split('-')[2];
                    const commentText = document.getElementById(`new-comment-${placeID}`).value;

                    fetch('add_comment.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ placeID: placeID, comment: commentText })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const commentList = document.getElementById(`comment-list-${placeID}`);
                            const newComment = document.createElement('p');
                            newComment.innerHTML = `<strong>You:</strong> ${commentText}`;
                            commentList.appendChild(newComment);
                            document.getElementById(`new-comment-${placeID}`).value = '';
                        } else {
                            alert(data.message || 'Unable to add comment.');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
