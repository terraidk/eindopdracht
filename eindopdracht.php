<?php

session_start();
require('database.php');
$database = new Database();
$pdo = $database->pdo;
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
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <navbar>
        <!-- LOGO -->
        <div class="logo_top_left">
            <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
        </div>
        <div class="menu">
            <!-- NAVBAR CONTENTS -->
            <ul>
                <!-- Menu Items -->
                <li class="navbar-li"><a class="navbar-a" href="#" id="inventory">INVENTORY</a></li>
                <li class="navbar-li"><a class="navbar-a" href="#" id="services">SERVICES</a></li>
                <li class="navbar-li"><a class="navbar-a" href="#" id="location">LOCATION</a></li>
                <li class="navbar-li"><a class="navbar-a" href="#" id="contact">CONTACT</a></li>
                <!-- Search Bar -->
                </li>
            </ul>
        </div>

        <?php
        if (!isset($_SESSION["loggedInUser"])) { // Check if the user is not logged in
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
        if (isset($_GET['uitloggen'])) { // Check if the 'uitloggen' parameter is set
            session_destroy(); // Destroy the user's session
            header("location: eindopdracht.php"); // Redirect to the eindopdracht.php page
            exit; // Terminate further code execution
        }
        ?>

        <div class="hamburger-menu">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
    </navbar>

    <script>
        // This code will run when the DOM content is fully loaded
        document.addEventListener("DOMContentLoaded", function () {
            var inventoryLink = document.querySelector("#inventory");
            var servicesLink = document.querySelector("#services");
            var contactLink = document.querySelector("#contact");
            var locationLink = document.querySelector("#location");

            // Add event listeners to menu links
            if (inventoryLink) {
                inventoryLink.addEventListener("click", function (e) {
                    e.preventDefault(); // Prevent the default behavior of the link
                    var targetSection = document.querySelector("#inventory1"); // Get the target section
                    if (targetSection) {
                        targetSection.scrollIntoView({
                            behavior: "smooth", // Smooth scrolling animation
                            block: "start" // Scroll to the top of the target section
                        });
                    }
                }
        });

        if (servicesLink) {
            servicesLink.addEventListener("click", function (e) {
                e.preventDefault();
                var targetSection = document.querySelector("#services1");
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            });
        }

        if (contactLink) {
            contactLink.addEventListener("click", function (e) {
                e.preventDefault();
                var targetSection = document.querySelector("#contact1");
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            });
        }

        if (locationLink) {
            locationLink.addEventListener("click", function (e) {
                e.preventDefault();
                var targetSection = document.querySelector("#location1");
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            });
        }


        const hamburgermenu = document.querySelector(".hamburger-menu");
        const navMenu = document.querySelector(".menu ul"); // Select the specific ul element

        // Add a click event listener to the hamburger menu icon
        hamburgermenu.addEventListener("click", () => {
            hamburgermenu.classList.toggle("active"); // Toggle the 'active' class on the menu icon
            navMenu.classList.toggle("active"); // Toggle the 'active' class on the menu items
        });

        // Close the mobile menu when a link is clicked
        document.querySelectorAll(".frontface-a").forEach((link) => {
            link.addEventListener("click", () => {
                hamburgermenu.classList.remove("active"); // Remove the 'active' class from the menu icon
                navMenu.classList.remove("active"); // Remove the 'active' class from the menu items
            });
        });

        const winkel = document.getElementById("winkel");
        const winkelMand = document.getElementById("pr-winkel");

        // Show/hide the shopping cart when clicked
        winkel.addEventListener("click", () => {
            winkel.classList.toggle("active"); // Toggle the 'active' class on the shopping cart icon
            winkelMand.classList.toggle("active"); // Toggle the 'active' class on the shopping cart content
        });

        const boxlog = document.getElementById("boxlog");
        const uitloggen = document.getElementById("uitloggen");

        // Show/hide user profile and logout button
        boxlog.addEventListener("click", () => {
            boxlog.classList.toggle("drop"); // Toggle the 'drop' class on the user profile
            uitloggen.classList.toggle("drop"); // Toggle the 'drop' class on the logout button
        });
    </script>

</body>

</html>