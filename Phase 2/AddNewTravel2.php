<?php
if (isset($_POST['travel_id'])) {
    $travel_id = $_POST['travel_id'];
} elseif (isset($_GET['travel_id'])) {
    $travel_id = $_GET['travel_id'];
} else {
    echo "Error: Travel ID not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Visited Places in Your Travel</title>
    <link rel="stylesheet" href="usertravel.css">
</head>
<body>
    <main>
        <div class="addPlace">
            <h1>Add Visited Places in Your Travel</h1>
            <form method="POST" action="addPlace.php" enctype="multipart/form-data">
                <input type="hidden" name="travel_id" value="<?php echo htmlspecialchars($travel_id); ?>">
                <label for="name">Place Name: </label>
                <input type="text" name="name" id="name" required>
                <label for="location">Location/City: </label>
                <input type="text" name="location" id="city" required>
                <label for="description">Description: </label>
                <textarea name="description" rows="6" cols="64" id="description" required></textarea>
                <label for="photoFileName">Upload Photo: </label>
                <input type="file" name="photoFileName" id="photoFileName" accept="image/*">
                <div class="buttons">
                    <button type="submit" name="isDone" value="no">Add Another Place</button>
                    <button type="submit" name="isDone" value="yes">Done</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
