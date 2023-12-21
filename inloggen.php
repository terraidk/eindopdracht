<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/inloggen.css">

</head>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require("database.php");
$database = new Database();
$pdo = $database->pdo;

$email = isset($_POST["email"]) ? $_POST["email"] : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";

$error_message = ""; // Initialize an empty error message

if (isset($_POST["login"])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_admin'] == 1) {
            // Check if the user logging in is an admin
            $_SESSION["loggedInAdmin"] = $user["user_id"];
            // Redirect to the admin panel
            header("Location: admin_panel.php");
        } else if ($user['is_admin'] == 0) {
            // Regular user login
            $_SESSION["loggedInUser"] = $user["user_id"];
            // Redirect to a relevant page for regular users
            header("Location: eindopdracht.php");
        }
    } else {
        $error_message = "Invalid username/password combination";
        // Output the error message for debugging purposes
        echo $error_message;
        // You might also consider logging this error for better tracking/debugging
    }
}

if (isset($_POST["register"])) {
    header("location: register.php");
}
?>

<body>
    <div class="wrapper">
        <form action="inloggen.php" method="post">
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" placeholder="email" name="email" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="password" name="password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox">Remember Me</label>
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" name="login" class="btn">Login</button>

            <div class="error-message">
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <p style='color: red; text-align: center;'>
                            <?php echo $error_message; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a> </p>
            </div>
            <div class="register-link">
                <p><a href="eindopdracht.php">Back to Homepage</a></p>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const registered = urlParams.get('registered');

            if (registered === 'true') {
                // Display a message (you can customize this)
                const message = document.createElement('div');
                message.textContent = 'Registration successful! Please log in.';
                message.style.position = 'fixed';
                message.style.bottom = '20px';
                message.style.right = '20px';
                message.style.padding = '10px 20px';
                message.style.backgroundColor = '#42b883';
                message.style.color = '#fff';
                message.style.borderRadius = '5px';
                message.style.zIndex = '9999';

                document.body.appendChild(message);

                // Automatically remove the message after a few seconds (optional)
                setTimeout(function () {
                    message.remove();
                }, 5000); // Adjust the time as needed (here it's set to 5 seconds)
            }
        });
    </script>
</body>

</html>