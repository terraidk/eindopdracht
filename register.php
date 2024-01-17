<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/register.css">
</head>
<?php
session_start();

require("database.php");
$database = new Database();
$pdo = $database->pdo;

$name = isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : "";
$email = isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : "";
$licensenumber = isset($_POST["licensenumber"]) ? htmlspecialchars($_POST["licensenumber"]) : "";
$phonenumber = isset($_POST["phonenumber"]) ? ($_POST["phonenumber"]) : "";
$address = isset($_POST["address"]) ? htmlspecialchars($_POST["address"]) : "";
$password = isset($_POST["password1"]) ? $_POST["password1"] : "";
$password2 = isset($_POST["password2"]) ? $_POST["password2"] : "";

if (isset($_POST["register"])) {
    // Check if the email already exists
    $existingUser = $database->getUserByEmail($email);

    if ($existingUser) {
        $message = "<p style='color: red; text-align: center; font-size: 22px'>Email already exists. Please use a different email.</p>";
    } else {
        if ($password == $password2) {

            $password = trim($_POST["password1"]);
            $hashedPassword = md5($password);

            try {
                $database->registerUser($name, $email, $licensenumber, $phonenumber, $hashedPassword, $address);
                $message = "<p style='color: green; text-align: center;'>Registered successfully</p>";
                sleep(1);
                header("location: inloggen.php?registered=true");
                exit(); // Add exit after header to prevent further execution
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
                <input type="text" name="licensenumber" placeholder="Licensenumber" name="licensenumber" required>
                <i class='bx bx-id-card'></i>
            </div>

            <div class="input-box">
                <input type="text" placeholder="Address" name="address" required>
                <i class='bx bx-home'></i>
            </div>

            <div class="input-box">
                <input type="text" placeholder="Phone Number" name="phonenumber" required>
                <i class='bx bxs-phone'></i>
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