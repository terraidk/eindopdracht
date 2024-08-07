<?php
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['loggedInAdmin'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit; // Terminate further execution
}

require('database.php');
$database = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $license_plate = $_POST['license_plate'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $daily_price = $_POST['daily_price'];

    // Check if a file was uploaded
    if (isset($_FILES['car_picture']) && $_FILES['car_picture']['error'] == 0) {
        $imageTmpName = $_FILES['car_picture']['tmp_name'];
        $imageData = file_get_contents($imageTmpName); // Read image data from file
    } else {
        // Handle no file uploaded scenario
        $imageData = file_get_contents('default_image.jpg'); // Load default image
    }

    // Insert data into the database
    $stmt = $database->prepare("INSERT INTO cars (car_brand, car_model, car_year, car_licenseplate, car_availability, car_dailyprice, car_picture) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $brand);
    $stmt->bindParam(2, $model);
    $stmt->bindParam(3, $year);
    $stmt->bindParam(4, $license_plate);
    $stmt->bindParam(5, $availability);
    $stmt->bindParam(6, $daily_price);
    $stmt->bindParam(7, $imageData, PDO::PARAM_LOB); // Ensure correct binding

    if ($stmt->execute()) {
        header("Location: admin_panel.php?addedCar=true");
        exit; // Terminate further execution after redirection
    } else {
        echo "Error adding the car.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel - Add Car</title>
    <link rel="stylesheet" href="styles/adminpanel.css">
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const addedCar = urlParams.get('addedCar');

            if (addedCar === 'true') {
                const message = document.createElement('div');
                message.textContent = 'Car added successfully!';
                message.style.position = 'fixed';
                message.style.bottom = '20px';
                message.style.right = '20px';
                message.style.padding = '10px 20px';
                message.style.backgroundColor = '#42b883';
                message.style.color = '#fff';
                message.style.borderRadius = '5px';
                message.style.zIndex = '9999';

                document.body.appendChild(message);

                setTimeout(function () {
                    message.remove();
                }, 5000); 
            }
        });
    </script>
</body>

</html>