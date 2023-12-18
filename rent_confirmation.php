<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="confirmation-container">
        <h1>Rent Confirmation</h1>
        <p>Congratulations! You have successfully rented the car.</p>
        <p>Your rental details:</p>
        <ul>
            <!-- Display rental details -->
            <li>User:
                <?php echo isset($_SESSION['loggedInUser']) ? $_SESSION['loggedInUser'] : ''; ?>
            </li>
            <li>Car ID:
                <?php echo isset($_GET['car_id']) ? $_GET['car_id'] : 'N/A'; ?>
            </li>
        </ul>
        <p>Thank you for using our services!</p>
        <a href="eindopdracht.php">Go to Dashboard</a>
    </div>
</body>

</html>