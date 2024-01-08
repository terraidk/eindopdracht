<!-- rent_car.php -->

<?php
session_start();
require('database.php'); // Include your database connection file
$database = new Database();
$pdo = $database->pdo;

// Check if the car_id is provided in the URL
if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];

    // Fetching car data for the provided car_id
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE car_id = :car_id");
    $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
    $stmt->execute();
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the car with the specified car_id is found, display its details
    if ($car) {
        ?>
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <title>Rent Car</title>
                            <!-- Your CSS and other headers -->
                        </head>
                        <body>
                            <!-- Car details display -->
                            <h1><?php echo $car['car_brand'] . ' ' . $car['car_model']; ?></h1>
                            <!-- Display other car details -->
                            <button onclick="handleRent(<?php echo $carId; ?>)">Rent Now</button>

                            <script>
                                function handleRent(carId) {
                                    // For demonstration, redirecting directly
                                    window.location.href = `rent_confirmation.php?car_id=${carId}`;
                                }
                            </script>
                        </body>
                        </html>
<?php
    } else {
        echo "Car not found";
        exit;
    }
} else {
    echo "No car selected";
    exit;
}
?>
