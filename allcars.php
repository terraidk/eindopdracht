<?php

session_start();

require('database.php');

$database = new Database();
$pdo = $database->pdo;

// Check if a search term is provided
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Fetch car details based on the search term
    $query = "SELECT * FROM cars WHERE 
              car_brand LIKE :searchTerm OR
              car_model LIKE :searchTerm OR
              car_licenseplate LIKE :searchTerm OR
              car_dailyprice = :searchPrice";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    $stmt->bindValue(':searchPrice', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If no search term provided, fetch all cars
    $stmt = $pdo->query("SELECT * FROM cars");
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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
    <div class="search-container">
        <form action="allcars.php" method="GET">
            <input type="text" placeholder="Search cars..." name="search">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="car-list">
        <?php
        // Display cars based on search results or all cars if no search term
        if ($cars && count($cars) > 0) {
            foreach ($cars as $car) {
                echo '<div class="car-item">';
                echo '<div class="Imgdiv">';
                if ($car['car_picture']) {
                    echo '<img class="Productimg" src="data:image/jpeg;base64,' . $car["car_picture"] . '">';
                } else {
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
            echo '<p>No cars match your search.</p>';
        }
        ?>
    </div>

    <footer class="back-to-home">
        <a href="eindopdracht.php" class="back-to-home-btn">Back to Homepage</a>
    </footer>


</body>

</html>
