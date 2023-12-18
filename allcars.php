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
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

        /* Custom CSS */
        body {
            background-color: #f8f9fa;
            font-family: "Poppins", sans-serif;
        }

        .car-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .car-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .car-item {
            background: linear-gradient(to top, rgba(140, 0, 140, 0.2), white, rgba(140, 0, 140, 0.2));
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            max-width: 90%;
            /* Adjust the maximum width as needed */
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            /* Align items to the start (top) */
        }

        .car-item img {
            max-width: 100%;
            height: 200px;
            border-radius: 5px;
        }

        h1 {
            color: rgb(140, 0, 140);
            text-align: center;
            margin-top: 20px;

        }

        button {
            background-color: rgb(140, 0, 140);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #8c008c;
        }

        .item {
            display: flex;
            text-align: center;
            font-size: 20px;
            margin: 5px;
            font-weight: 1000;
        }
    </style>
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