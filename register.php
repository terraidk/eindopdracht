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
            background: url('images/revuelto.jpg') no-repeat;
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 420px;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .2);
            backdrop-filter: blur(20px);
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

$name = isset($_POST["name"]) ? $_POST["name"] : "";
$name = htmlspecialchars($name);
$email = isset($_POST["email"]) ? $_POST["email"] : "";
$address = isset($_POST["address"]) ? $_POST["address"] : "";
$password = isset($_POST["password1"]) ? $_POST["password1"] : "";
$password2 = isset($_POST["password2"]) ? $_POST["password2"] : "";

if (isset($_POST["register"])) {
    // Check if the email already exists
    $existingUser = $database->getUserByEmail($email);

    if ($existingUser) {
        $message = "<p style='color: red; text-align: center; font-size: 22px'>Email already exists. Please use a different email.</p>";
    } else {
        if ($password == $password2) {
            try {
                $database->registerUser($name, $email, $password, $address);
                $message = "<p style='color: green; text-align: center;>Registered successfully</p>";
                sleep(1);
                header("location: inloggen.php?registered=true");
            } catch (PDOException $e) {
                $message = "Error registering user: " . $e->getMessage();
            }
        } else {
            $message = "<p style='color: red; text-align: center; font-size: 22px'>Passwords do not match</p>";
        }
    }
}
?>

<body>
    <div class="wrapper">
        <form method="post">
            <h1>Register</h1>
            <div class="input-box">
                <input type="text" placeholder="Full Name" name="name" required>
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <input type="text" placeholder="E-mail" name="email" required>
                <i class='bx bx-envelope'></i>
            </div>

            <div class="input-box">
                <input type="text" placeholder="Address" name="address" required>
                <i class='bx bx-home'></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="Password" name="password1" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="Confirm Password" name="password2" required>
                <i class='bx bxs-lock-alt'></i>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox" id="rememberMe">Remember Me</label>
            </div>

            <button type="submit" name="register" class="btn">Register</button>

            <?php
            if (isset($message)) {
                echo "<p>$message</p>";
            }
            ?>
            <div class="error-message">
            </div>

            <div class="register-link">
                <p>Already have an account? <a href="inloggen.php">Back to login</a>.</p>
            </div>
        </form>
    </div>


</body>

</html>