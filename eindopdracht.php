<?php
session_start();

require('database.php');
$database = new Database();
$pdo = $database->pdo;

$stmt = $pdo->query("SELECT * FROM cars");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>snelle autos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-pzjw8f+ua7p8p3dpu5n5s5t3kmw1jh5f5bG5GfPQ1YzF+8Qch9i/A5Fw5+5L2t5zH" crossorigin="anonymous">
        
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <nav>

    <div class="hamburger-menu">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

        <!-- LOGO -->
        <div class="logo_top_left">
            <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
        </div>
        <div class="menu">
            <!-- NAVBAR CONTENTS -->
            <ul>
                <!-- Menu Items -->
                <li class="navbar-li"><a class="navbar-a" href="allcars.php" id="inventory">INVENTORY</a></li>
                <li class="navbar-li"><a class="navbar-a" href="#" id="services">SERVICES</a></li>
                <li class="navbar-li"><a class="navbar-a" href="#" id="location">LOCATION</a></li>
                <li class="navbar-li"><a class="navbar-a" href="#" id="contact">CONTACT</a></li>

                <!-- Search Bar -->
                <div class="search-container">
                    <form action="allcars.php" method="GET">
                        <input type="text" placeholder="Search cars..." name="search">
                        <button type="submit">Search</button>
                    </form>
                </div>
                </li>
            </ul>
        </div>

        <?php
        if (!isset($_SESSION["loggedInUser"])) {

            ?>
            <ul>
                <!-- Display the Login Link -->
                <li class="navbar-li"><a class="navbar-a" href="inloggen.php">LOGIN</a></li>
            </ul>
            <?php
        } else {
            ?>
            <ul class="boxlog" id="boxlog">
                <!-- Display User Profile -->
                <li class="ingelogd">
                    <!-- Add your profile picture image source -->
                    <i class='bx bxs-user'></i> 

                    <!-- Display Dropdown -->
                    <ul class="dropdown">
                        <li class="dropdown-item"><a href="profile.php">Profile</a></li>
                        <li class="dropdown-item"><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
            <?php
        }
        if (isset($_GET['uitloggen'])) { // Check if the 'uitloggen' parameter is set // Destroy the user's session
            header("Location: logout.php"); // Redirect to the eindopdracht.php page
            exit; // Terminate further code execution
        }
        ?>
    </nav>
    
    <div class="inventory" id="inventory1">
        <h1 class="text" style="text-align: center; color: rgb(140, 0, 140);">Inventory</h1>
        <div class="container">
            <?php
            foreach ($cars as $car) {
                echo '<div class="Productdiv">';
                echo '<div class="Imgdiv">';
                if ($car['car_picture']) {
                    echo '<img class="Productimg" src="data:image/jpeg;base64,' . base64_encode($car['car_picture']) . '">';
                } else {
                    // Display a placeholder if no image is found
                    echo '<img class="Productimg" src="images/default_image.png">';
                }
                // Remove this line that's causing the image filename to be printed
                // echo $car["car_picture"];
                echo '</div>';
                echo '<button class="rent" onclick="window.location.href=\'cars.php?car_id=' . $car["car_id"] . '\'">RENT NOW</button>';
                echo '<div class="Infodiv">';
                echo '<p class="item">' . $car["car_brand"] . ' ' . $car["car_model"] . '</p>';
                echo '<p class="prijs">€' . $car["car_dailyprice"] . ' per day.</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script src="js/eindopdracht.js"></script>

</body>

</html>