<?php
session_start();
require 'include/db_connection.php';

function uploadPhoto($file) {
    if (isset($file) && $file['error'] == 0) {
        $photoPath = 'uploads/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $photoPath);
        return $photoPath;
    }
    return 'images/defaultphoto.png'; // Default photo if no file uploaded
}

/**
 * Checks if the email already exists in the database.
 *
 * @param mysqli $conn The database connection.
 * @param string $email The email to check.
 * @return bool True if the email exists, otherwise false.
 */
function isEmailExists($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close(); // Close the statement
    return $exists;
}

/**
 * Registers a new user in the database.
 *
 * @param mysqli $conn The database connection.
 * @param string $firstName The user's first name.
 * @param string $lastName The user's last name.
 * @param string $email The user's email address.
 * @param string $hashedPassword The hashed password.
 * @param string $photo The path to the user's photo.
 * @return bool True on success, false on failure.
 */
function registerUser($conn, $firstName, $lastName, $email, $hashedPassword, $photo) {
    $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, emailAddress, password, photoFileName) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $photo);
    $success = $stmt->execute();
    $stmt->close(); // Close the statement
    return $success;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Handle photo upload
    $photo = uploadPhoto($_FILES['photo']);

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    if (isEmailExists($conn, $email)) {
        header("Location: signup.php?error=Email already exists");
        exit();
    } else {
        // Insert the new user into the database
        if (registerUser($conn, $firstName, $lastName, $email, $hashedPassword, $photo)) {
            // Get the user's ID and store it in the session
            $_SESSION['user_id'] = $conn->insert_id; // Use insert_id from the connection
            header("Location: user_homepage.php"); // Redirect to user homepage
            exit();
        } else {
            echo "Error: Could not register user.";
        }
    }

    $conn->close(); // Close the database connection
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
     <script>
        document.getElementById("signup-form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevents the default form submission
            // Optionally, add some validation here for better user experience
            window.location.href = "User’shomepage.html"; // Redirect to user home page
        });
    </script>
</body>
</html>
