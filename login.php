<?php
session_start();
require 'database.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to authenticate the user
function authenticateUser($connection, $email, $password) {
    // Use correct table and column names
    $stmt = $connection->prepare("SELECT * FROM user WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION["user_id"] = $row['id'];
            return true;
        }
    }
    return false;
}

// Process the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"], $_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if (authenticateUser($connection, $email, $password)) {
            header("Location: users_homepage.php"); // Redirect to user homepage
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <img src="images/Web2-logo.svg" alt="Logo" class="logo">
    </header>
    <main>
        <h1>Login</h1>
        <form id="login-form" action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Log In</button>
        </form>
        <?php if (isset($error_message)): ?>
            <div style="color: red;"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
    </main>
</body>
</html>
