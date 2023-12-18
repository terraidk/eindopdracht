<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;

        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('images/imgr8.jpg') no-repeat;
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 420px;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .2);
            backdrop-filter: blur(25px);
            box-shadow: 0 0 10px rgba(0, 0, 0, .2);
            color: #fff;
            border-radius: 10px;
            padding: 30px 40px;
        }

        .wrapper h1 {
            font-size: 36px;
            text-align: center;
        }

        .wrapper .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 30px 0;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            outline: none;
            border: none;
            border: 2px solid rgba(255, 255, 255, .2);
            border-radius: 40px;
            font-size: 16px;
            color: #fff;
            padding: 20px 45px 20px 20px;
        }

        .input-box input::placeholder {
            color: #fff;
        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }

        .wrapper .remember-forgot {
            display: flex;
            justify-content: space-between;
            font-size: 14.5px;
            margin: -15px 0 15px;
        }

        .remember-forgot label input {
            accent-color: #fff;
            margin-right: 3px;
        }

        .remember-forgot a {
            color: #fff;
            text-decoration: none;
        }

        .remember-forgot a:hover {
            text-decoration: underline;
        }

        .wrapper .btn {
            width: 100%;
            height: 45px;
            background: #fff;
            border: none;
            outline: none;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, , 1);
            cursor: pointer;
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .wrapper .register-link {
            font-size: 14.5px;
            text-align: center;
            margin: 20px 0 15px;
        }

        .register-link p a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link p a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<?php
session_start();

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
        $_SESSION["loggedInUser"] = $user["user_id"];

        if (isset($_SESSION['carId'])) {
            $carId = $_SESSION['carId'];
            unset($_SESSION['carId']); // Clear the stored car ID

            // Redirect to the original cars.php page with the same car_id
            header("Location: cars.php?car_id=$carId");
            exit;
        } else {
            // If there's no specific car ID, redirect to a default page
            header("Location: allcars.php");
            exit;
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