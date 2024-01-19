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
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Available Cars</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-pzjw8f+ua7p8p3dpu5n5s5t3kmw1jh5f5bG5GfPQ1YzF+8Qch9i/A5Fw5+5L2t5zH" crossorigin="anonymous">
            <link rel="stylesheet" href="styles/cars.css">
        </head>

        <body>
        <div class="car-container">
            <div class="car-item">
                <h1>Car details - <?php echo $car['car_brand'] . ' ' . $car['car_model']; ?>
                        </h1>
                    <?php if ($car['car_picture']) {
                                echo '<img class="Productimg" src="data:images/jpeg;base64,' . $car['car_picture'] . '">';
                            } else {
                                // Display a placeholder if no image is found
                                echo '<img class="Productimg" src="images/default_image.png">';
                            }
                            ?>
                <!-- Displaying Car Details -->
                <h2>
                    <?php echo $car['car_brand'] . ' ' . $car['car_model']; ?>
                </h2>
                <p>
                    Year: <?php echo $car['car_year']; ?>
                        </p>
                        <p>License Plate:
                            <?php echo $car['car_licenseplate']; ?>
                        </p>
                        <p>Daily Price: €
                            <?php echo $car['car_dailyprice']; ?>
                        </p>
                        <?php if ($car['car_availability'] == 1): ?>
                            <button onclick="window.location.href='rent_car.php?car_id=<?php echo $car['car_id']; ?>'">Rent Now</button>
                        <?php else: ?>
                                                                                        <p>This car is currently <b>unavailable.</b></p>
                        <?php endif; ?>

                    </div>

            <?php
    } else {
        // If the car with the specified car_id is not found, display an error or redirect to another page
        echo "Car not found";
        exit;
    }
} else {
    // If no car_id is provided in the URL, display a message or redirect to another page
    echo "No car selected";
    exit; // Terminate further code execution
}
?>

<script>
    function handleRent(carId) {
        // Simulate payment processing (client-side)
        const paymentSuccessful = simulatePayment();

        if (paymentSuccessful) {
            // Updating car availability in the database
            updateCarAvailability(carId);

            // Redirect to rent confirmation page with carId parameter
            window.location.href = 'rent_confirmation.php?car_id=' + carId;
        } else {
            // Handle payment failure
            alert('Payment unsuccessful. Please try again.');
        }
    }

</script>
</body>

</html>