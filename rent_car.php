<?php
session_start();
require('database.php');
$database = new Database();
$pdo = $database->pdo;

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: inloggen.php");
    exit;
}

if (isset($_GET['car_id'])) {
    $carId = $_GET['car_id'];

    // Fetch car data for the provided car_id
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE car_id = :car_id");
    $stmt->bindParam(':car_id', $carId, PDO::PARAM_INT);
    $stmt->execute();
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the car with the specified car_id is found, display its details and rent form
    if ($car) {
        ?>
                        <!DOCTYPE html>
                        <html lang="en">

                        <head>
                            <title>Rent Car</title>
                            <link rel="stylesheet" href="styles/rent_car.css">
                        </head>

                        <body>
                            <div class="container">
                                <h1><?php echo $car['car_brand'] . ' ' . $car['car_model']; ?>
                                        </h1>
                                        <?php if ($car['car_picture']): ?>
                                            <!-- Decode and display the image -->
                                            <img class="Productimg" src="data:images/jpeg;base64,<?php echo $car['car_picture']; ?>"
                                                            alt="<?php echo $car['car_brand'] . ' ' . $car['car_model']; ?>">
                                        <?php else: ?>
                                            <!-- Display a placeholder if no image is found -->
                                            <img class="Productimg" src="images/placeholder.png" alt="Placeholder">
                                        <?php endif; ?>
                                        <div class="car-details">
                                            <p>Year:
                                                <?php echo $car['car_year']; ?>
                                            </p>
                                            <p>License Plate:
                                                <?php echo $car['car_licenseplate']; ?>
                                            </p>
                                            <p>Availability:
                                                <?php echo $car['car_availability'] ? 'Available' : 'Not Available'; ?>
                                            </p>
                                            <p>Daily Price: €
                                                <?php echo $car['car_dailyprice']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <h2>Select Rent Dates</h2>
                                        <!-- Added the hidden input field for car_id -->
                                        <form id="rentForm" action="rent_confirmation.php?car_id=<?php echo $carId; ?>" method="POST">
                                            <input type="hidden" name="car_id" value="<?php echo $_GET['car_id']; ?>">
                                            <label for="start_date">Start Date:</label>
                                            <input type="date" id="rent_startdate" name="rent_startdate" required><br><br>
                                            <label for="end_date">End Date:</label>
                                            <input type="date" id="rent_enddate" name="rent_enddate" required><br><br>
                                            <label for="price">Total Price (€):</label> <br>
                                            <input type="text" class="price" name="rent_price" readonly> <br><br>
                                            <input type="submit" class="rentbutton" value="Rent Now">
                                        </form>
                                    </div>

                            <script>
                               document.addEventListener('DOMContentLoaded', function() {
                const rentForm = document.getElementById('rentForm');

                rentForm.addEventListener('input', function() {
                    const startDate = new Date(document.getElementById('rent_startdate').value);
                    const endDate = new Date(document.getElementById('rent_enddate').value);
                    const dailyPrice = <?php echo $car['car_dailyprice']; ?>;
                                        const totalPrice = (endDate - startDate) / (1000 * 60 * 60 * 24) * dailyPrice;
                                        document.querySelector('.price').value = '€' + totalPrice.toFixed(2);
                                    });
                                });
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
