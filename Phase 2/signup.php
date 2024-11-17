<?php 
session_start();
require 'database.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to handle file upload and return photo filename
function uploadPhoto($file, $allowDefault = true) {
    if (isset($file) && $file['error'] == 0) {
        $photoPath = 'images/' . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $photoPath)) {
            return basename($file['name']); // Store only the filename
        }
    }
    return $allowDefault ? 'defultphoto.jpg' : null; // Just the default filename
}

// Check if email already exists
function isEmailExists($connection, $email) {
    $stmt = $connection->prepare("SELECT id FROM user WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Register the user in the database
function registerUser($connection, $firstName, $lastName, $email, $hashedPassword, $photo) {
    $stmt = $connection->prepare("INSERT INTO user (firstName, lastName, emailAddress, password, photoFileName) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $photo);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call uploadPhoto function with $allowDefault = true to use default photo if none is uploaded
    $photo = uploadPhoto($_FILES['photo'], true);
    
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    if (isEmailExists($connection, $email)) {
        // Redirect back to signup with an error message if email exists
        header("Location: signup.php?error=Email already exists");
        exit();
    } else {
        // Register the user and redirect to user's homepage if successful
        if (registerUser($connection, $firstName, $lastName, $email, $hashedPassword, $photo)) {
            $_SESSION['user_id'] = $connection->insert_id;
            header("Location: users_homepage.php"); // Redirect to user homepage
            exit();
        } else {
            echo "Error: Could not register user.";
        }
    }
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <title>Sign Up</title>
</head>
<body>
    <main>
        <h1>Create Your Account</h1>
        <form id="signup-form" action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" required>
            </div>
            <div class="form-group">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="photo">Upload Photo (optional)</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <div style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
    </main>
</body>
</html>
