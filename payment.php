<?php
session_start();
require('database.php');

if (isset($_GET['user']) && isset($_GET['car'])) {
    // Retrieve user and car details from GET parameters
    $loggedInUser = $_GET['user'];
    $carId = $_GET['car'];

    // Simulate successful payment confirmation (you would integrate with an actual payment gateway here)
    $paymentSuccessful = true;

    if ($paymentSuccessful) {
        // Update car availability to 0 in the database
        $database = new Database();
        $pdo = $database->pdo;

        $updateStmt = $pdo->prepare("UPDATE cars SET car_availability = 0 WHERE car_id = :car_id");
        $updateStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
        $updateSuccess = $updateStmt->execute();

        if ($updateSuccess) {
            // Payment and car rental successful
            // Redirect to confirmation page with car_id parameter
            header("Location: rent_confirmation.php?car_id=$carId");
            exit;
        } else {
            // Handle database update failure
            echo "Failed to update car availability.";
            exit;
        }
    } else {
        // Handle payment failure
        echo "Payment unsuccessful. Please try again.";
        exit;
    }
} else {
    // Redirect if user or car details are missing
    header("Location: inloggen.php");
    exit;
}
?>