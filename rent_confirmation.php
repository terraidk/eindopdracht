<?php
session_start();
require('database.php');
$database = new Database();
$pdo = $database->pdo;

if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];

    // Insert into the renting table to record the rent transaction
    $stmt = $pdo->prepare("INSERT INTO renting (car_id, rent_startdate, rent_enddate) VALUES (:car_id, NOW(), NOW() + INTERVAL 7 DAY)");
    $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Update car availability in the cars table
        $updateStmt = $pdo->prepare("UPDATE cars SET car_availability = 0 WHERE car_id = :car_id");
        $updateStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
        $updateStmt->execute();

        // Display rent confirmation
        echo "<h1>Rent Confirmed!</h1>";
        echo "<p>You have successfully rented the car. Enjoy your ride!</p>";
    } else {
        echo "Error renting the car.";
    }
} else {
    echo "No car selected";
    exit;
}
?>
