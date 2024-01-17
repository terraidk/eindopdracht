<?php
session_start();

if (!isset($_SESSION['loggedInAdmin']) && !isset($_SESSION['loggedInWorker'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit;
}

require('database.php');

// Establish the database connection here
$database = new Database();
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $licensenumber = $_POST['licensenumber'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $address = $_POST['address'];

    $stmt = $database->prepare("INSERT INTO users (name, licensenumber, email, password, address) VALUES (?, ?, ?, ?, ?)");

    if ($stmt->execute([$name, $licensenumber, $email, $password, $address])) {
        header("Location: manage_users.php?addedUser=true");
        exit;
    } else {
        $error = "Error adding user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add user</title>
    <link rel="stylesheet" href="styles/nav.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");


        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Poppins", sans-serif;
        }

        .add-user {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .add-user h2 {
            text-align: center;
            color: #8c008c;
        }

        .add-user form {
            display: flex;
            flex-direction: column;
        }

        .add-user label {
            margin-top: 10px;
            font-weight: bold;
        }

        .add-user input {
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
            width: 100%;
        }

        .add-user button {
            background-color: #8c008c;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .add-user button:hover {
            background-color: #6a006a;
        }
    </style>
</head>

<body>
    <nav>
        <!-- LOGO -->
        <div class="logo_top_left">
            <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
        </div>
        <div>
            <p style="font-size: 25px; color: white;">Add User</p>
        </div>
    </nav>

    <div class="add-user">
        <h2>Add User</h2>

        <?php if ($error): ?>
            <p style='color: red; text-align: center; font-size: 20px;'>
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <form action="add_user.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="licensenumber">License Number:</label>
            <input type="text" id="licensenumber" name="licensenumber" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <button type="submit">Add User</button>
        </form>
    </div>
</body>

</html>

</html>