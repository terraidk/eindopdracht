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
                <?php if ($car['car_picture']): ?>
                                        <img class="Productimg" src="images/<?php echo base64_encode($car['car_picture']); ?>"
                                            alt="<?php echo $car['car_brand'] . ' ' . $car['car_model']; ?>">
                <?php else: ?>
                    <!-- Display a placeholder if no image is found -->
                    <img class="Productimg" src="images/placeholder.png" alt="Placeholder">
                <?php endif; ?>

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
                            <p>Car currentlyNot Available</p>
                        <?php endif; ?>

                    </div>

            <?php
    } else {
        // If the car with the specified car_id is not found, display an error or redirect to another page
        echo "Car not found";
        // Alternatively, redirect to another page:
        // header("Location: error_page.php");
        exit; // Terminate further code execution
    }
} else {
    // If no car_id is provided in the URL, display a message or redirect to another page
    echo "No car selected";
    // Alternatively, redirect to another page:
    // header("Location: select_car.php");
    exit; // Terminate further code execution
}
?>

<script>
    function handleRent(carId) {
        // Simulate payment processing (client-side)
        const paymentSuccessful = simulatePayment();

        if (paymentSuccessful) {
            // Simulate updating car availability (client-side)
            updateCarAvailability(carId);

            // Redirect to rent confirmation page with carId parameter
            window.location.href = 'rent_confirmation.php?car_id=' + carId;
        } else {
            // Handle payment failure
            alert('Payment unsuccessful. Please try again.');
        }
    }

    // Function to simulate payment (replace with actual payment processing logic)
    function simulatePayment() {
        // Simulate successful payment (return true for success, false for failure)
        return Math.random() < 0.8; // 80% success rate (adjust for simulation)
    }

    // Function to simulate updating car availability (replace with actual logic)
    function updateCarAvailability(carId) {
        // Simulate updating car availability in the database (client-side)
        // This could involve making an AJAX request to update the database, for example
        console.log('Updating car availability for car ID: ' + carId);
    }
</script>
</body>

</html>