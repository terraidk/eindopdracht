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

// Handle actions (Update, Delete) if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $action = $_POST['action'];
        $user_id = $_POST['user_id'];

        if ($action === 'update') {
            // Redirect to edit_user.php with user_id parameter
            header("Location: edit_user.php?user_id=$user_id");
            exit;
        } elseif ($action === 'delete') {
            $stmtDeleteRenting = $database->prepare("DELETE FROM renting WHERE user_id = ?");
            if ($stmtDeleteRenting->execute([$user_id])) {
                // Now, delete the user
                $stmtDeleteUser = $database->prepare("DELETE FROM users WHERE user_id = ?");
                if ($stmtDeleteUser->execute([$user_id])) {
                    header("Location: manage_users.php?deletedUser=true");
                    exit;
                } else {
                    $error = "Error deleting the user.";
                }
            } else {
                $error = "Error deleting associated renting records.";
            }
        }
    }
}

// Retrieve the list of users
$users = $database->getUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles/manage_users.css">
    <link rel="stylesheet" href="styles/nav.css">
</head>

<body>
    <nav>
        <!-- LOGO -->
        <div class="logo_top_left">
            <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
        </div>
        <div>
            <p style="font-size: 25px; color: white;">Manage users</p>
        </div>
    </nav>

    <div class="users">
        <h2 style="text-align: center;">Manage Users</h2>

        <?php if ($error): ?>
            <p style='color: red; text-align: center; font-size: 20px;'>
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>License Number</th>
                <th>Email</th>
                <th>Address</th>
                <th colspan="2">Action</th>
            </tr>

            <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <?php echo $user['user_id']; ?>
                    </td>
                    <td>
                        <?php echo $user['name']; ?>
                    </td>
                    <td>
                        <?php echo $user['licensenumber']; ?>
                    </td>
                    <td>
                        <?php echo $user['email']; ?>
                    </td>
                    <td>
                        <?php echo $user['address']; ?>
                    </td>
                    <td>
                        <form action="edit_user.php" method="GET">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="submit" value="Edit">
                        </form>

                        <form action="manage_users.php" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const addedCar = urlParams.get('updatedUser');

            if (addedCar === 'true') {
                const message = document.createElement('div');
                message.textContent = ' Updated user successfully!';
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

        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const addedCar = urlParams.get('deletedUser');

            if (addedCar === 'true') {
                const message = document.createElement('div');
                message.textContent = ' Deleted user successfully!';
                message.style.position = 'fixed';
                message.style.bottom = '20px';
                message.style.right = '20px';
                message.style.padding = '10px 20px';
                message.style.backgroundColor = '#ff0000';
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