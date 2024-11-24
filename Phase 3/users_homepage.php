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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User's Homepage</title>
    <link rel="stylesheet" href="User’shomepage.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    $userPhotoPath = 'images/defaultphoto.jpg';
                }
            ?>
            <img src="<?php echo htmlspecialchars($userPhotoPath); ?>" alt="User Photo">
        </div>
    </div>

    <div class="travels">
        <h2>All Travels</h2>
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
        </div>

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
                    <!-- AJAX will populate this -->
                </tbody>
            </table>
        </div>            
    </div>
</div>

<script>
// Fetch and display all travels on page load
$(document).ready(function () {
    fetchTravels('all'); // Fetch all travels

    // Add event listener for country filter changes
    $('#country-filter').on('change', function () {
        const countryID = $(this).val(); // Get selected country ID
        fetchTravels(countryID); // Fetch travels based on the selected country
    });
});

// Function to fetch travels using jQuery's $.ajax()
function fetchTravels(countryID) {
    $.ajax({
        url: 'fetch_filtered_travels.php',
        type: 'GET',
        data: { country: countryID },
        dataType: 'json',
        success: function (data) {
            const tableBody = $('.table-container tbody');
            tableBody.empty(); // Clear the table

            if (data.length === 0) {
                tableBody.append('<tr><td colspan="4">No travels found.</td></tr>');
                return;
            }

            // Populate the table with new data
            data.forEach(travel => {
                const row = `
                    <tr>
                        <td class="traveller-info">
                            <a href="javascript:void(0);" onclick="redirectToDetails(${travel.id})">
                                ${travel.firstName}
                            </a>
                            <a href="images/${travel.placePhotoFileName}" target="_blank">
                                <img src="images/${travel.placePhotoFileName}" alt="Place Photo">
                            </a>
                        </td>
                        <td>${travel.country}</td>
                        <td>${travel.month} ${travel.year}</td>
                        <td>${travel.totalLikes}</td>
                    </tr>
                `;
                tableBody.append(row);
            });
        },
        error: function (xhr, status, error) {
            console.error('Error fetching travels:', error);
        }
    });
}

function redirectToDetails(travelId) {
    const form = $('<form>', { method: 'POST', action: 'TravelDetails.php' });
    $('<input>', { type: 'hidden', name: 'travel_id', value: travelId }).appendTo(form);
    $('body').append(form);
    form.submit();
}

</script>
</body>
</html>
