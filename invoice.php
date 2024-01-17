<?php
require('database.php');
$database = new Database();
$pdo = $database->pdo;

$rentId = $_GET['rent_id'] ?? null;
$userId = $_GET['user_id'] ?? null;

// Fetch rent information
$rentStmt = $pdo->prepare("SELECT renting.*, users.name FROM renting 
                          JOIN users ON renting.user_id = users.user_id
                          WHERE rent_id = :rent_id AND renting.user_id = :user_id");
$rentStmt->bindParam(':rent_id', $rentId, PDO::PARAM_INT);
$rentStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$rentStmt->execute();

$rent = $rentStmt->fetch(PDO::FETCH_ASSOC);

if ($rent) {
    $name = $rent['name'];
    $carId = $rent['car_id'];
    $startDate = $rent['rent_startdate'];
    $endDate = $rent['rent_enddate'];
    $totalPrice = $rent['rent_price'];

    // Fetch car information
    $carStmt = $pdo->prepare("SELECT * FROM cars WHERE car_id = :car_id");
    $carStmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
    $carStmt->execute();

    $car = $carStmt->fetch(PDO::FETCH_ASSOC);

    if ($car) {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Invoice</title>
            <link rel="stylesheet" href="styles/invoice.css">
        </head>

        <body>
            <div class="container">
                <h1>Invoice</h1>
                <p>Invoice Number:
                    <?php echo $rentId; ?>
                </p>
                <p>Renter:
                    <?php echo $name; ?>
                </p>
                <p>Car:
                    <?php echo $car['car_brand'] . ' ' . $car['car_model']; ?>
                </p>
                <p>Rental Start Date:
                    <?php echo $startDate; ?>
                </p>
                <p>Rental End Date:
                    <?php echo $endDate; ?>
                </p>
                <p>Total Price: €
                    <?php echo number_format($totalPrice, 2); ?>
                </p>
                <p>Rent issued at:
                    <?php echo $rent['rent_date']; ?>
                </p>
            </div>
            <a href="profile.php"
                style="position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background-color: rgb(140, 0, 140); color: #fff; border-radius: 5px; text-decoration: none; z-index: 9999;">Back
                to profile</a>

        </body>

        </html>
        <?php
    } else {
        echo "Car not found";
    }
} else {
    echo "Rent not found";
}
?>