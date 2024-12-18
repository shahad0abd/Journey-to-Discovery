<?php
    require_once 'database.php';
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php"); 
        exit();
    } 

    $user_id = $_SESSION['user_id'];

    // Retrieve user information
    $sqluser = "SELECT * FROM User WHERE id = $user_id";
    $result = mysqli_query($connection, $sqluser);
    $user = $result ? mysqli_fetch_assoc($result) : null;

    // Retrieve travels for the user
    $sqlTravel = "SELECT * FROM Travel WHERE userID = $user_id";
    $result2 = mysqli_query($connection, $sqlTravel);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User's Travel Page</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

        <link rel="stylesheet" href="usertravel.css">
    </head>
    <body>
        <header>
            <nav>
                <h4 id='name'><?php echo htmlspecialchars($user['firstName']); ?>'s Travels</h4>
                <ul class="links">
                    <li><a href="users_homepage.php">Back to Homepage</a></li>
                    <li><a href="index.php">Log Out</a></li>
                </ul>
            </nav>
        </header>

        <main> 
            <div class="main">
                <div class="caption">
                    <h2>All Travels</h2>
                    <a href="AddNewTravel1.php" class="addLink">Add New Travel</a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2" class="firstthree">Travel</th>
                                <th rowspan="2" class="firstthree">Travel Time</th>
                                <th rowspan="2" class="firstthree">Country</th>
                                <th colspan="6">Places</th>
                            </tr>
                            <tr>
                                <th>Place Name</th>
                                <th>Location</th>
                                <th>Description</th>
                                <th>Photo</th>
                                <th>Likes</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result2 && mysqli_num_rows($result2) > 0): ?>
                                <?php $index = 1; ?>
                                <?php while ($travel = mysqli_fetch_assoc($result2)): ?>
                                    <?php
                                        
                                        $sqlCountry = "SELECT country FROM Country WHERE id = " . $travel['countryID'];
                                        $countryResult = mysqli_query($connection, $sqlCountry);
                                        $country = $countryResult ? mysqli_fetch_assoc($countryResult)['country'] : 'Unknown';

                                        
                                        $sqlPlace = "SELECT * FROM Place WHERE travelID = " . $travel['id'];
                                        $placeResult = mysqli_query($connection, $sqlPlace);
                                    ?>
                                    <?php if ($placeResult && mysqli_num_rows($placeResult) > 0): ?>
                                        <?php $firstPlaceRow = true; ?>
                                        <?php while ($place = mysqli_fetch_assoc($placeResult)): ?>
                                            <tr>
                                                <?php if ($firstPlaceRow): ?>
                                                    <td rowspan="<?php echo mysqli_num_rows($placeResult); ?>">
                                                        <?php echo $index; ?><br><br>
                                                        <a href='edit_travel.php?travelID=<?php echo urlencode($travel['id']); ?>' class='editLinks'>Edit Travel Details</a> <br>
                                                        <a href='deleteTravel.php?travel_id=<?php echo urlencode($travel['id']); ?>' class='editLinks'>Delete Travel</a>
                                                    </td>
                                                    <td rowspan="<?php echo mysqli_num_rows($placeResult); ?>"><?php echo htmlspecialchars($travel['month'] . " " . $travel['year']); ?></td>
                                                    <td rowspan="<?php echo mysqli_num_rows($placeResult); ?>"><?php echo htmlspecialchars($country); ?></td>
                                                    <?php $firstPlaceRow = false; ?>
                                                <?php endif; ?>

                                                <td><?php echo htmlspecialchars($place['name']); ?></td>
                                                <td><?php echo htmlspecialchars($place['location']); ?></td>
                                                <td><?php echo htmlspecialchars($place['description']); ?></td>

                                                
                                               <td>
                                               <?php 
                                               $photoFilePath = "images/" . $place['photoFileName'];
                                               if (!empty($place['photoFileName']) && file_exists($photoFilePath)) {
                                                   echo "<img src='" . htmlspecialchars($photoFilePath) . "' alt='Place Photo' class='place-photo' width='100' height='100'>";
                                               } else {
                                                   echo "<p>No photo available</p>";
                                               }
                                               ?>
                                               </td>


                                                
                                                <?php
                                                    $sqlLikes = "SELECT COUNT(*) AS likeCount FROM `Like` WHERE placeID = " . $place['id'];
                                                    $likesResult = mysqli_query($connection, $sqlLikes);
                                                    $likes = $likesResult ? mysqli_fetch_assoc($likesResult)['likeCount'] : 0;
                                                ?>
                                                <td>&#9829; <?php echo $likes; ?></td>

                                                
                                                <?php
                                                    $allComments = "";
                                                    $sqlComments = "SELECT u.firstName, c.comment FROM `Comment` c JOIN `User` u ON c.userID = u.id WHERE c.placeID = " . $place['id'];
                                                    $commentsResult = mysqli_query($connection, $sqlComments);
                                                    while ($comment = mysqli_fetch_assoc($commentsResult)) {
                                                        $allComments .= "<strong>" . htmlspecialchars($comment['firstName']) . ":</strong> " . htmlspecialchars($comment['comment']) . "<br>";
                                                    }
                                                ?>
                                                <td><?php echo $allComments ?: 'No comments'; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td><?php echo $index; ?><br><br>
                                                <a href='edit_travel.php?travelID=<?php echo urlencode($travel['id']); ?>' class='editLinks'>Edit Travel Details</a>
                                                <a href='deleteTravel.php?travel_id=<?php echo urlencode($travel['id']); ?>' class='editLinks'>Delete Travel</a>
                                            </td>
                                            <td><?php echo htmlspecialchars($travel['month'] . " " . $travel['year']); ?></td>
                                            <td><?php echo htmlspecialchars($country); ?></td>
                                            <td colspan="6">No places found for this travel.</td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php $index++; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="9">No travels found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    <script>
        $(document).ready(function () {
            $('.editLinks[href*="deleteTravel.php"]').on('click', function (event) {
                event.preventDefault(); // Prevent the link from navigating

                if (confirm("Are you sure you want to delete this travel?")) {
                    const travelID = new URL(this.href).searchParams.get("travel_id");


                    $.ajax({
                        url: 'deleteTravel.php',
                        type: 'GET',
                        data: { travel_id: travelID },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                alert("Travel deleted successfully.");

                                location.reload();
                            } else {
                                alert("Failed to delete the travel. Please try again.");
                            }
                        },
                        error: function() {
                            alert("An error occurred. Please try again.");
                        }
                    });
                }
            });
        });
    </script>


    </body>
</html>

<?php
mysqli_close($connection);
?>
