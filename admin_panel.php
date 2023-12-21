<?php
// start your session
session_start();
// if the user is not an admin, redirect to the login page
if (!isset($_SESSION['loggedInAdmin'])) {
    header("Location: inloggen.php");
    session_destroy();
}
require('database.php');

$database = new Database();

// Establish the database connection here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $license_plate = $_POST['license_plate'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $daily_price = $_POST['daily_price'];

    // Check if a file was uploaded
    if (isset($_FILES['car_picture']) && $_FILES['car_picture']['error'] == 0) {
        $image = file_get_contents($_FILES['car_picture']['tmp_name']);
    } else {
        // If no file was uploaded, set a default image or handle the case as needed
        $image = file_get_contents('default_image.jpg');
    }

    // Insert data into the database
    $stmt = $pdo->prepare("INSERT INTO cars (car_brand, car_model, car_year, car_licenseplate, car_availability, car_dailyprice, car_picture) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $brand);
    $stmt->bindParam(2, $model);
    $stmt->bindParam(3, $year);
    $stmt->bindParam(4, $license_plate);
    $stmt->bindParam(5, $availability);
    $stmt->bindParam(6, $daily_price);
    $stmt->bindParam(7, $image, PDO::PARAM_LOB);

    if ($stmt->execute()) {
        echo "Car added successfully!";
    } else {
        echo "Error adding the car.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel - Add Car</title>
</head>

<body>
    <h2>Add a New Car</h2>
    <form action="add_car.php" method="POST" enctype="multipart/form-data">
        <label>Brand:</label>
        <input type="text" name="brand" required><br><br>

        <label>Model:</label>
        <input type="text" name="model" required><br><br>

        <label>Year:</label>
        <input type="number" name="year" required><br><br>

        <label>License Plate:</label>
        <input type="text" name="license_plate" required><br><br>

        <label>Available?</label>
        <input type="checkbox" name="availability" checked><br><br>

        <label>Daily Price:</label>
        <input type="number" name="daily_price" step="0.01"><br><br>

        <label>Upload Picture:</label>
        <input type="file" name="car_picture" accept="image/*"><br><br>

        <input type="submit" value="Add Car">
    </form>
</body>

</html>