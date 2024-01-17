<?php
session_start();

// Redirect if the user is not an admin
if (!isset($_SESSION['loggedInAdmin']) && !isset($_SESSION['loggedInWorker'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit;
}

require('database.php');

// Establish the database connection here
$database = new Database();
$error = "";

// Check if user_id is provided in the URL
if (!isset($_GET['user_id'])) {
    header("Location: manage_users.php");
    exit;
}

$user_id = $_GET['user_id'];

// Retrieve user details based on user_id
$user = $database->getUserById($user_id);

// Handle form submission for updating user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $licensenumber = $_POST['licensenumber'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Update user details in the database
    if ($database->updateUser($user_id, $name, $licensenumber, $email, $address)) {
        header("Location: manage_users.php?updatedUser=true");
        exit;
    } else {
        $error = "Error updating user details.";
    }
} else {
    echo "<h2 sytlye='text-align: center; font-size: 30px; color: rgb(140, 0, 140);' >User not available/not found</h2>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles/edit_user.css">
    <link rel="stylesheet" href="styles/nav.css">
</head>

<body>
    <nav>
        <!-- LOGO -->
        <div class="logo_top_left">
            <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
        </div>
        <div>
            <p style="font-size: 25px; color: white;">Edit User</p>
        </div>
    </nav>
    <br>

    <div class="edit-user-form">
        <h2>Edit User</h2>
        <br>

        <?php if ($error): ?>
            <p style='color: red; text-align: center; font-size: 20px;'>
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <form action="edit_user.php?user_id=<?php echo $user_id; ?>" method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>

            <label>License Number:</label>
            <input type="text" name="licensenumber" value="<?php echo $user['licensenumber']; ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

            <label>Address:</label>
            <textarea name="address" required><?php echo $user['address']; ?></textarea><br>

            <input type="submit" value="Update User">
        </form>
    </div>
</body>

</html>