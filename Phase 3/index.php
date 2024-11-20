<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #F0EAD2;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        header {
            position: absolute;
            top: 0;
            left: 0;
            padding: 20px;
            background-color: #7F5539;
            width: 100%;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .logo {
            height: 80px;  /* Increase the height */
            width: auto;   /* Keep the width proportional */
        }

        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 100px;
        }

        .app-name {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .login-btn {
            padding: 12px 24px; /* Increased padding for a larger button */
            font-size: 1em;
            margin: 10px;
            cursor: pointer;
            border: 3px solid #7F5539; /* Added thicker border */
            color: #fbf6f6;
            background-color: #7F5539;
        }

        .signup-btn {
            padding: 10px 20px;
            font-size: 1em;
            margin: 10px;
            cursor: pointer;
            border: none;
            color: #070707;
            background-color: #F0EAD2;
            font-weight: bold;
            text-decoration: underline; /* Underline and bold the text */
        }

        .sign-up-section {
            display: flex;
            align-items: center;
            justify-content: center; /* Center them properly */
            margin-top: 20px;
        }

        .sign-up-section p {
            font-size: 1.1em;
            margin-right: 10px; /* Increase the margin to give more space */
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <img src="images/Web2-logo.svg" alt="Logo" class="logo">
    </header>

    <main>
        <h1 class="app-name">Journey to Discovery</h1>

        <!-- Link to Log-In Page -->
        <a href="login.php">
            <button class="login-btn">Log-In</button>
        </a>

        <div class="sign-up-section">
            <p>New user?</p>
            <!-- Link to Sign-Up Page -->
            <a href="signup.php">
                <button class="signup-btn">Sign-Up</button>
            </a>
        </div>
    </main>
</body>
</html>

