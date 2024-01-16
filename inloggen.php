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

if ($email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(); // Fetch the user details

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_admin'] == 1) {
            $_SESSION["loggedInAdmin"] = $user["user_id"];
            header("Location: admin_panel.php");
            exit();
        } else if ($user['is_admin'] == 2) {
            // Assuming 2 is the role for a worker
            $_SESSION["loggedInWorker"] = $user["user_id"];
            header("Location: worker_panel.php");
            exit();
        } else if ($user['is_admin'] == 0) {
            // Assuming 0 is the role for a regular user
            $_SESSION["loggedInUser"] = $user["user_id"];
    
            $carId = isset($_GET['car_id']) ? $_GET['car_id'] : null;
            if ($carId !== null) {
                $_SESSION['carId'] = $carId; // Storing carId in session
                header("Location: rent_car.php?car_id=$carId");
                exit();
            } else {
                header("Location: eindopdracht.php");
                exit();
            }
        }
    } else {
        $error_message = "Invalid username/password combination";
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

                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <p style='color: red; text-align: center;'>
                            <?php echo $error_message; ?>
                        </p>
                    </div>
                <?php endif; ?>

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

                setTimeout(function () {
                    message.remove();
                }, 5000); 
            }
        });
    </script>
</body>

</html>