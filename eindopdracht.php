<?php
session_start();

require('database.php');
$database = new Database();
$pdo = $database->pdo;

$stmt = $pdo->query("SELECT * FROM cars");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['loggedInAdmin']) && isset($_SESSION['loggedInWorker'])) {
    session_destroy();
}

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

    <script src="https://cdn.jsdelivr.net/npm/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

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
                <li class="navbar-li"><a class="navbar-a" href="#services1" id="services">SERVICES</a></li>
                <li class="navbar-li"><a class="navbar-a" href="#location1" id="location">LOCATION</a></li>
                <li class="navbar-li"><a class="navbar-a" href="#contact1" id="contact">CONTACT</a></li>

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
                <!-- Display the Login Link if nog logged in -->
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
        if (isset($_GET['uitloggen'])) { // Check if the 'uitloggen' parameter is set / Destroy the user's session
            header("Location: logout.php"); // Redirect to the eindopdracht.php page
            exit;
        }
        ?>
    </nav>
    
    <div class="inventory" id="inventory1">
        <h1 class="text" style="text-align: center; color: rgb(140, 0, 140);">Inventory</h1>
        <div class="container">
            <?php
            foreach ($cars as $car) {
                $carName = $car["car_brand"] . ' ' . $car["car_model"];
                $maxNameLength = 14; // Maximum length for the car name
            
                // Check if the car name exceeds the maximum length
                if (strlen($carName) > $maxNameLength) {
                    $displayCarName = substr($carName, 0, $maxNameLength) . '...';
                } else {
                    $displayCarName = $carName;
                }

                echo '<div class="Productdiv">';
                echo '<div class="Imgdiv">';
                if ($car['car_picture']) {
                    echo '<img class="Productimg" src="data:images/jpeg;base64,' . $car['car_picture'] . '">';
                } else {
                    // Display a placeholder if no image is found
                    echo '<img class="Productimg" src="images/default_image.png">';
                }
                echo '</div>';
                echo '<button class="rent" onclick="window.location.href=\'cars.php?car_id=' . $car["car_id"] . '\'">RENT NOW</button>';
                echo '<div class="Infodiv">';
                echo '<p class="item">' . $displayCarName . '</p>';
                echo '<p class="prijs">€' . $car["car_dailyprice"] . ' daily.</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <div class="services-section" id="services1">
    <h1 class="text" style="text-align: center; color: rgb(140, 0, 140);">Our Services</h1>
    <div class="service-item">
        <h2 style="text-align: center">Wide Range of Cars</h2>
        <p style="text-align: center">We offer a diverse selection of cars, from compact cars to SUVs, ensuring there's a perfect vehicle for every occasion.</p>
    </div>
    
    <div class="service-item">
        <h2 style="text-align: center">Flexible Rental Periods</h2>
        <p style="text-align: center">Choose the rental period that suits your needs, whether it's a day, a week, or even a month. We provide flexibility to accommodate your schedule.</p>
    </div>

    <div class="service-item">
        <h2 style="text-align: center">Easy Booking Process</h2>
        <p style="text-align: center">Our user-friendly online booking system makes it convenient for you to reserve a car at your fingertips. Book anytime, anywhere.</p>
    </div>

    <div class="service-item">
        <h2 style="text-align: center">Competitive Pricing</h2>
        <p style="text-align: center">Enjoy competitive and transparent pricing with no hidden fees. We strive to provide cost-effective solutions for your car rental needs.</p>
    </div>

    <div class="location-section" id="location1">
        <h1 class="text">Our Location</h1>

        <div class="location-info">
            <h2>Main Office</h2>
            <p>Fraijlemaborg 186<br> Amsterdam, Nederland</p>
        </div>

        <div class="opening-times">
            <h2>Opening Times</h2>
            <table>
                <tr>
                    <th>Day</th>
                    <th>Opening Hours</th>
                </tr>
                <tr>
                    <td>Monday</td>
                    <td>9:00 AM - 6:00 PM</td>
                </tr>
                <tr>
                    <td>Tuesday</td>
                    <td>9:00 AM - 6:00 PM</td>
                </tr>
                <tr>
                    <td>Wednesday</td>
                    <td>9:00 AM - 6:00 PM</td>
                </tr>
                <tr>
                    <td>Thursday</td>
                    <td>9:00 AM - 6:00 PM</td>
                </tr>
                <tr>
                    <td>Friday</td>
                    <td>9:00 AM - 6:00 PM</td>
                </tr>
                <tr>
                    <td>Saturday</td>
                    <td>10:00 AM - 4:00 PM</td>
                </tr>
                <tr>
                    <td>Sunday</td>
                    <td>Closed</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="contact-section" id="contact1">
        <h1 class="text">Contact Us</h1>

        <div class="contact-form">
            <form>
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
    </div>

    <footer>
    <div class="footer-content">
        <div class="footer-logo">
            <img src="images/logo.png" alt="logo">
        </div>
        <div class="footer-links">
            <ul>
                <li><a href="allcars.php">Inventory</a></li>
                <li><a href="#services1">Services</a></li>
                <li><a href="#location1">Location</a></li>
                <li><a href="#contact1">Contact</a></li>
            </ul>
        </div>
        <div class="footer-social">
            <a href="https://facebook.com/" class="social-icon"><i class='bx bxl-facebook'></i></a>
            <a href="https://twitter.com/" class="social-icon"><i class='bx bxl-twitter'></i></a>
            <a href="https://instagram.com/" class="social-icon"><i class='bx bxl-instagram'></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Snelle Autos. All rights reserved.</p>
    </div>
</footer>

    <script src="js/eindopdracht.js"></script>

</body>

</html>