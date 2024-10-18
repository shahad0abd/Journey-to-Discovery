<?php
session_start();
include 'include/db_connection.php'; // Include your database connection file


// Function to authenticate the user
function authenticateUser($conn, $email, $password) {
    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
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

        if (authenticateUser($conn, $email, $password)) {
            header("Location: user_homepage.php"); // Redirect to user homepage
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
        <img src="\images\Web2-logo.svg" alt="Logo" class="logo">
    </header>
    <main>
        <h1>Login</h1>
        <form id="login-form" action="user_homepage.html" method="post">
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
    </main>

    <script>
        document.getElementById("login-form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevents the default form submission
            // Optionally, add some validation here for better user experience
            window.location.href = "User’shomepage.html"; // Redirect to user home page
        });
    </script>
    
</body>
</html>

