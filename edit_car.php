<?php
session_start();

// Redirect if the user is not an admin
if (!isset($_SESSION['loggedInAdmin']) && !isset($_SESSION['loggedInWorker'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit;
}

require('database.php');

$database = new Database();
$error = "";

if (!isset($_GET['car_id'])) {
    header("Location: manage_cars.php");
    exit;
}

$car_id = $_GET['car_id'];

$car = $database->getCarById($car_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $license_plate = $_POST['license_plate'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $daily_price = $_POST['daily_price'];

    // Check if a new file was uploaded
    if (isset($_FILES['car_picture']) && $_FILES['car_picture']['error'] == 0) {
        $imageTemp = $_FILES['car_picture']['tmp_name'];
        $fileContent = file_get_contents($imageTemp);
        $picture = base64_encode($fileContent);
    } else {
        // Keep the existing picture if no new file was uploaded
        $picture = $car['car_picture'];
    }

    // Update car details in the database
    if ($database->updateCar($car_id, $brand, $model, $year, $license_plate, $availability, $daily_price, $picture)) {
        header("Location: manage_cars.php?updatedCar=true");
        exit;
    } else {
        $error = "Error updating car details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link rel="stylesheet" href="styles/edit_car.css">
    <link rel="stylesheet" href="styles/nav.css">
</head>

<body>
    <nav>
        <!-- LOGO -->
        <div class="logo_top_left">
            <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
        </div>
        <div>
            <p style="font-size: 25px; color: white;">Edit Car</p>
        </div>
    </nav>
    <br>

    <div class="edit-car-form">
        <h2>Edit Car</h2>
        <br>

        <?php if ($error): ?>
            <p style='color: red; text-align: center; font-size: 20px;'>
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <form action="edit_car.php?car_id=<?php echo $car_id; ?>" method="POST" enctype="multipart/form-data">
            <label>Brand:</label>
            <input type="text" name="brand" value="<?php echo $car['car_brand']; ?>" required><br>

            <label>Model:</label>
            <input type="text" name="model" value="<?php echo $car['car_model']; ?>" required><br>

            <label>Year:</label>
            <input type="number" name="year" value="<?php echo $car['car_year']; ?>" required><br>

            <label>License Plate:</label>
            <input type="text" name="license_plate" value="<?php echo $car['car_licenseplate']; ?>" required><br>

            <label>Available?</label>
            <input type="checkbox" name="availability" <?php echo $car['car_availability'] ? 'checked' : ''; ?>><br>

            <label>Daily Price:</label>
            <input type="number" name="daily_price" step="0.01" value="<?php echo $car['car_dailyprice']; ?>"
                required><br>

            <label>New Picture if needed:</label>
            <input type="file" name="car_picture" accept="image/*"><br>

            <input type="submit" value="Update Car">
        </form>
    </div>
    <script>

    </script>
</body>

</html>