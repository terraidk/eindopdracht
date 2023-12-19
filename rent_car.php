<?php
session_start();
require('database.php');

if (isset($_SESSION['loggedInUser']) && isset($_GET['car_id'])) {
    // Get user and car details from the session and URL
    $loggedInUser = $_SESSION['loggedInUser'];
    $carId = $_GET['car_id'];

    // Store car ID in session before redirection
    $_SESSION['carId'] = $carId;

    // Redirect to payment page with user and car details
    header("Location: payment.php?user=$loggedInUser&car=$carId");
    exit;
} else {
    $carId = $_GET['car_id'];
    // Redirect if user is not logged in or car is not selected
    header("Location: inloggen.php?car_id=$carId");
    exit;
}
?>