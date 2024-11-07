<?php
session_start();
require 'database.php';

// Ensure travel ID is specified
if (!isset($_GET['travelID'])) {
    die("Travel ID not specified.");
}

$travelID = $_GET['travelID'];

// Retrieve travel details
$query = $connection->prepare("SELECT T.*, C.country FROM Travel T JOIN Country C ON T.countryID = C.id WHERE T.id = ?");
$query->bind_param("i", $travelID);
$query->execute();
$travel = $query->get_result()->fetch_assoc();

if (!$travel) {
    die("Travel not found.");
}

// Fetch list of countries for dropdown
$countriesResult = $connection->query("SELECT * FROM Country");

// Retrieve places associated with this travel
$placesQuery = $connection->prepare("SELECT * FROM Place WHERE travelID = ?");
$placesQuery->bind_param("i", $travelID);
$placesQuery->execute();
$places = $placesQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Travel Details</title>
    <link rel="stylesheet" href="edit_travel_details.css">
</head>
<body>
    <header>
        <h1>Edit Travel Details</h1>
    </header>
    <main>
        <form action="update_travel.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="travelID" value="<?php echo htmlspecialchars($travelID); ?>">

            <!-- Travel Information Section -->
            <div class="travel-info">
                <label for="month">Travel Time:</label>
                <select name="month" id="month">
                    <?php
                    $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    foreach ($months as $month) {
                        $selected = ($month == $travel['month']) ? "selected" : "";
                        echo "<option value='$month' $selected>$month</option>";
                    }
                    ?>
                </select>
                <input type="number" name="year" id="year" value="<?php echo htmlspecialchars($travel['year']); ?>" required>

                <label for="country">Country:</label>
                <select name="country" id="country">
                    <?php while ($country = $countriesResult->fetch_assoc()) {
                        $selected = ($country['id'] == $travel['countryID']) ? "selected" : "";
                        echo "<option value='{$country['id']}' $selected>{$country['country']}</option>";
                    } ?>
                </select>
            </div>

            <!-- Places Section -->
            <?php $placeIndex = 1; ?>
            <?php while ($place = $places->fetch_assoc()): ?>
                <fieldset>
                    <legend>Place <?php echo $placeIndex; ?></legend>

                    <label for="place_name_<?php echo $place['id']; ?>">Place Name:</label>
                    <input type="text" name="places[<?php echo $place['id']; ?>][name]" id="place_name_<?php echo $place['id']; ?>" value="<?php echo htmlspecialchars($place['name']); ?>">

                    <label for="location_<?php echo $place['id']; ?>">Location/City:</label>
                    <input type="text" name="places[<?php echo $place['id']; ?>][location]" id="location_<?php echo $place['id']; ?>" value="<?php echo htmlspecialchars($place['location']); ?>">

                    <label for="description_<?php echo $place['id']; ?>">Description:</label>
                    <textarea name="places[<?php echo $place['id']; ?>][description]" id="description_<?php echo $place['id']; ?>"><?php echo htmlspecialchars($place['description']); ?></textarea>

                    <label for="photo_<?php echo $place['id']; ?>">Upload Photo:</label>
                    <input type="file" name="places[<?php echo $place['id']; ?>][photo]" id="photo_<?php echo $place['id']; ?>">

                    <!-- Display current photo -->
                    <?php
                    $photoFilePath = "images/" . $place['photoFileName'];
                    if (!empty($place['photoFileName']) && file_exists($photoFilePath)) {
                        echo "<p>Current Photo:</p><img src='" . htmlspecialchars($photoFilePath) . "' alt='Place Photo' width='100'>";
                    } else {
                        echo "<p>No current photo available</p>";
                    }
                    ?>
                </fieldset>
                <?php $placeIndex++; ?>
            <?php endwhile; ?>

            <input type="submit" value="Save Changes">
        </form>
    </main>
</body>
</html>
