<?php
session_start();

if (!isset($_SESSION['loggedInUser'])) {
    $_SESSION['carId'] = $_GET['car_id'] ?? null;
    header("Location: inloggen.php");
    exit;
}

require('database.php');
$database = new Database();
$pdo = $database->pdo;

// Check if user is logged in
if (isset($_SESSION['loggedInUser'])) {
    if (isset($_GET['car_id'])) {
        $carId = $_GET['car_id'];

        // Fetch car data for the provided car_id
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE car_id = :car_id");
        $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
        $stmt->execute();

        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($car) {
            // Calculate total price
            $totalPrice = 0; // Default price if dates not provided

            if (isset($_POST['rent_startdate']) && isset($_POST['rent_enddate'])) {
                $startDate = new DateTime($_POST['rent_startdate']);
                $endDate = new DateTime($_POST['rent_enddate']);

                $dailyPrice = $car['car_dailyprice'];
                $interval = $endDate->diff($startDate);
                $days = $interval->format('%a');
                $totalPrice = $dailyPrice * $days;
            }

            // Convert dates to the desired format
            $rent_startdate = isset($startDate) ? $startDate->format('Y-m-d') : null;
            $rent_enddate = isset($endDate) ? $endDate->format('Y-m-d') : null;

            // Display rent confirmation information
            ?>
                                                                        <div class="container">
                                                                            <h1>Rent Confirmation</h1>
                                                                            <p>You have selected:</p>
                                                                            <p><?php echo $car['car_brand'] . ' ' . $car['car_model']; ?></p>
                                                                            <p>Car ID: <?php echo $car['car_id']; ?></p>
                                                                            <p>Total Price: €<?php echo number_format($totalPrice, 2); ?></p>
                                                                            <p>Rental Start Date: <?php echo $rent_startdate; ?></p>
                                                                            <p>Rental End Date: <?php echo $rent_enddate; ?></p>
                                                                            <a href="profile.php" class="back-button">Back to your Profile</a>
                                                                        </div>

                                                <?php
                                                            // Bind logged-in user ID to the rent
                                                            $userId = $_SESSION['loggedInUser'];
                                                            $rentStmt = $pdo->prepare("INSERT INTO renting (car_id, user_id, rent_startdate, rent_enddate, rent_price) VALUES (:car_id, :user_id, :rent_startdate, :rent_enddate, :rent_price)");
                                                            $rentStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
                                                            $rentStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                                                            $rentStmt->bindParam(':rent_startdate', $rent_startdate);
                                                            $rentStmt->bindParam(':rent_enddate', $rent_enddate);
                                                            $rentStmt->bindParam(':rent_price', $totalPrice, PDO::PARAM_INT);

                                                            // Execute the query
                                                            if ($rentStmt->execute()) {
                                                                // Update car_availability to 0
                                                                $updateAvailabilityStmt = $pdo->prepare("UPDATE cars SET car_availability = 0 WHERE car_id = :car_id");
                                                                $updateAvailabilityStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
                                                                $updateAvailabilityStmt->execute();

                                                            } else {
                                                                echo "An error occured whilst renting the car. Please try again later or contact an administrator.";
                                                            }
        } else {
            echo "Car not found";
            exit;
        }
    } else {
        echo "No car selected";
        exit;
    }
} else {
    echo "Please log in to confirm the rent.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rent Confirmation</title>
    <link rel="stylesheet" href="styles/rent_confirmation.css">
</head>

<body>

</body>

</html>
