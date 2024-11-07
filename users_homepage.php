<?php 
session_start();
require 'database.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve user information, including photo filename
$userQuery = $connection->prepare("SELECT firstName, lastName, emailAddress, photoFileName FROM user WHERE id = ?");
$userQuery->bind_param("i", $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();

// Handle filtering request for travels by country
$countryFilter = isset($_POST['country']) ? $_POST['country'] : 'all';

// Prepare query for all travels or filtered by country
$travelQuery = "SELECT t.*, u.firstName, u.photoFileName AS userPhotoFileName, c.country,
                (SELECT COUNT(*) FROM `like` l WHERE l.placeID = p.id) as totalLikes,
                p.photoFileName AS placePhotoFileName
                FROM travel t
                JOIN user u ON t.userID = u.id
                JOIN country c ON t.countryID = c.id
                JOIN place p ON p.travelID = t.id";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $countryFilter != 'all') {
    $travelQuery .= " WHERE t.countryID = ?";
    $stmt = $connection->prepare($travelQuery);
    $stmt->bind_param("i", $countryFilter);
} else {
    $stmt = $connection->prepare($travelQuery);
}

$stmt->execute();
$travelsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User's Homepage</title>
    <link rel="stylesheet" href="User’shomepage.css">
</head>
<body>
<header>
    <div class="header-container">
        <nav>
            <div class="nav-left">
                <a>Welcome <?php echo htmlspecialchars($user['firstName']); ?></a>
            </div>
            <div class="nav-right">
                <ul class="nav-links">
                    <li><a href="UsersTravelPage.php">My Travels</a></li>
                    <li><a href="index.php">Log-out</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<div class="container">
    <div class="user-info">
        <div class="info-text">
            <h2>User Information</h2>
            <ul>
                <li><strong>Name:</strong> <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($user['emailAddress']); ?></li>
            </ul>
        </div>
        <div class="user-photo">
            <?php 
                $userPhotoPath = "images/" . $user['photoFileName'];
                if (!file_exists($userPhotoPath) || empty($user['photoFileName'])) {
                    $userPhotoPath = 'images/defultphoto.jpg';
                }
            ?>
            <img src="<?php echo htmlspecialchars($userPhotoPath); ?>" alt="User Photo">
        </div>
    </div>

    <div class="travels">
        <h2>All Travels</h2>
        <form method="POST">
            <div class="filter-section">
                <label for="country-filter">Select Country: </label>
                <select id="country-filter" name="country">
                    <option value="all">All</option>
                    <?php
                    $countryQuery = "SELECT * FROM country";
                    $countries = $connection->query($countryQuery);
                    while ($country = $countries->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($country['id']) . '">' . htmlspecialchars($country['country']) . '</option>';
                    }
                    ?>
                </select>
                <button type="submit">Filter</button>
            </div>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Traveller</th>
                        <th>Country</th>
                        <th>Travel Time</th>
                        <th>Total Likes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($travel = $travelsResult->fetch_assoc()) { 
                        $placePhotoPath = "images/" . $travel['placePhotoFileName'];
                        $displayPhotoPath = !empty($travel['placePhotoFileName']) && file_exists($placePhotoPath) ? $placePhotoPath : 'images/defaultphoto.png';
                    ?>
                    <tr>
                        <td class="traveller-info">
                            <!-- Traveller's name with link to travel details -->
                            <a href="javascript:void(0);" onclick="redirectToDetails(<?php echo htmlspecialchars($travel['id']); ?>)">
                                <?php echo htmlspecialchars($travel['firstName']); ?>
                            </a>

                            <!-- Place photo that opens in a new tab showing the full image -->
                            <a href="<?php echo htmlspecialchars($displayPhotoPath); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars($displayPhotoPath); ?>" alt="Place Photo">
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($travel['country']); ?></td>
                        <td><?php echo htmlspecialchars($travel['month'] . ' ' . $travel['year']); ?></td>
                        <td><?php echo htmlspecialchars($travel['totalLikes']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>            
    </div>
</div>

<script>
function redirectToDetails(travelId) {
    var form = document.createElement("form");
    form.method = "POST";
    form.action = "TravelDetails.php";

    var input = document.createElement("input");
    input.type = "hidden";
    input.name = "travel_id";
    input.value = travelId;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>
