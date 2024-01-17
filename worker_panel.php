<?php
session_start();

// Redirect if the user is not a worker
if (!isset($_SESSION['loggedInWorker'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit;
}

require('database.php');

// Establish the database connection here
$database = new Database();
$result = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['brand'])) {
        $brand = $_POST['brand'];
        $model = $_POST['model'];
        $year = $_POST['year'];
        $license_plate = $_POST['license_plate'];
        $availability = isset($_POST['availability']) ? 1 : 0;
        $daily_price = $_POST['daily_price'];

        // Check if a file was uploaded
        if (isset($_FILES['car_picture']) && $_FILES['car_picture']['error'] == 0) {
            // Get the uploaded image
            $imageTemp = $_FILES['car_picture']['tmp_name'];

            // Encode the image
            $fileContent = file_get_contents($imageTemp);
            $encodedImage = base64_encode($fileContent);

            // Debugging statements
            echo "Image successfully encoded!<br>";
            echo "Encoded Image Length: " . strlen($encodedImage) . "<br>";

            // Insert data into the database
            $stmt = $database->prepare("INSERT INTO cars (car_brand, car_model, car_year, car_licenseplate, car_availability, car_dailyprice, car_picture) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$brand, $model, $year, $license_plate, $availability, $daily_price, $encodedImage])) {
                header("Location: worker_panel.php?addedCar=true");
                exit;
            } else {
                echo "Error adding the car.<br>";
            }
        } else {
            $defaultImage = 'images/default_image.jpg';
            $image = file_get_contents($defaultImage);
        }
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Worker Panel</title>
    <link rel="stylesheet" href="styles/adminpanel.css">
    <link rel="stylesheet" href="styles/nav.css">
</head>

<nav>
    <!-- LOGO -->
    <div class="logo_top_left">
        <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
    </div>
    <div>
        <p style="font-size: 25px; color: white;">Worker panel</p>
    </div>
</nav>

<br>

<body>
    <div class="forms">
        <form action="worker_panel.php" method="POST" enctype="multipart/form-data">
            <h2 style="text-align: center;">Add a New Car</h2>
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
            <input type="file" name="car_picture" accept="images/*"><br><br>

            <input type="submit" value="Add Car">
            <?php if ($error): ?>
                <p style='color: red; text-align: center; font-size: 20px;'>
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>
        </form>
    </div>
        <a href="manage_rents.php" style="position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background-color: rgb(140, 0, 140); color: #fff; border-radius: 5px; text-decoration: none; z-index: 9999;">Manage Rents</a>

            <a href="manage_cars.php" style="position: fixed; bottom: 120px; right: 20px; padding: 10px 20px; background-color: rgb(140, 0, 140); color: #fff; border-radius: 5px; text-decoration: none; z-index: 9999;">Manage Cars</a>

            <a href="manage_users.php" style="position: fixed; bottom: 70px; right: 20px; padding: 10px 20px; background-color: rgb(140, 0, 140); color: #fff; border-radius: 5px; text-decoration: none; z-index: 9999;">Manage Users</a>



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

                    // Automatically remove the message after a 5 seconds 
                    setTimeout(function () {
                        message.remove();
                    }, 5000); 
                }
            });
        </script>
</body>

</html>