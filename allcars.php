<?php
session_start();
require('database.php');
$database = new Database();
$pdo = $database->pdo;

// Fetch car details from the database
$stmt = $pdo->query("SELECT * FROM cars");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the user is logged in
if (isset($_SESSION['loggedInUser'])) {
    $loggedInUser = $_SESSION['loggedInUser'];
} else {
    $loggedInUser = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All cars</title>
    <link rel="stylesheet" href="styles/allcars.css">
</head>

<body>
    <div class="car-list">
        <?php
        // Check if cars are fetched successfully
        if ($cars && count($cars) > 0) {
            foreach ($cars as $car) {
                echo '<div class="car-item">';
                // Display car details
                // ... Display car image, brand, model, price, etc.
                echo '<div class="Imgdiv">';
                if ($car['car_picture']) {
                    echo '<img class="Productimg" src="images/' . $car["car_picture"] . '">';
                } else {
                    // Display a placeholder if no image is found
                    echo '<img class="Productimg" src="images/placeholder.png">';
                }
                echo '</div>';
                echo '<button class="rent" onclick="window.location.href=\'cars.php?car_id=' . $car["car_id"] . '\'">RENT NOW</button>';
                echo '<div class="Infodiv">';
                echo '<p class="item">' . $car["car_brand"] . ' ' . $car["car_model"] . '</p>';
                echo '<p class="prijs">€' . $car["car_dailyprice"] . ' per day.</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No cars available.</p>';
        }
        ?>
    </div>

</body>

</html>