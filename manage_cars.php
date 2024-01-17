<?php
session_start();

if (!isset($_SESSION['loggedInAdmin']) && !isset($_SESSION['loggedInWorker'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit;
}

require('database.php');

// Establish the database connection here
$database = new Database();
$error = "";

// Handle actions (Update, Delete) if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['car_id'])) {
        $action = $_POST['action'];
        $car_id = $_POST['car_id'];

        if ($action === 'update') {
            // Redirect to edit_car.php with car_id parameter
            header("Location: edit_car.php?car_id=$car_id");
            exit;
        } elseif ($action === 'delete') {
            // Handle delete action
            $stmt = $database->prepare("DELETE FROM cars WHERE car_id = ?");
            if ($stmt->execute([$car_id])) {
                header("Location: manage_cars.php?deletedCar=true");
                exit;
            } else {
                $error = "Error deleting the car.";
            }
        }
    }
}

// Retrieve the list of cars
$cars = $database->getCars();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cars</title>
    <link rel="stylesheet" href="styles/manage_cars.css">
    <link rel="stylesheet" href="styles/nav.css">
</head>

<body>
    <nav>
        <!-- LOGO -->
        <div class="logo_top_left">
            <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
        </div>
        <div>
            <p style="font-size: 25px; color: white;">Manage Cars</p>
        </div>
    </nav>

    <div class="cars">
        <h2 style="text-align: center;">Manage Cars</h2>

        <?php if ($error): ?>
            <p style='color: red; text-align: center; font-size: 20px;'>
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <table>
            <tr>
                <th>Car ID</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Year</th>
                <th>License Plate</th>
                <th>Availability</th>
                <th>Daily Price</th>
                <th colspan="2">Action</th>
            </tr>

            <?php foreach ($cars as $car): ?>
                <tr>
                    <td>
                        <?php echo $car['car_id']; ?>
                    </td>
                    <td>
                        <?php echo $car['car_brand']; ?>
                    </td>
                    <td>
                        <?php echo $car['car_model']; ?>
                    </td>
                    <td>
                        <?php echo $car['car_year']; ?>
                    </td>
                    <td>
                        <?php echo $car['car_licenseplate']; ?>
                    </td>
                    <td>
                        <?php echo $car['car_availability'] ? 'Available' : 'Not Available'; ?>
                    </td>
                    <td>
                        <?php echo $car['car_dailyprice']; ?>
                    </td>
                    <td>
                        <form action="edit_car.php" method="GET">
                            <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                            <input type="submit" value="Edit">
                        </form>
                    </td>
                    <td>
                        <form action="manage_cars.php" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this car?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const addedCar = urlParams.get('updatedCar');

            if (addedCar === 'true') {
                const message = document.createElement('div');
                message.textContent = 'Car edited successfully!';
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

        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const addedCar = urlParams.get('deletedCar');

            if (addedCar === 'true') {
                const message = document.createElement('div');
                message.textContent = 'Car deleted successfully!';
                message.style.position = 'fixed';
                message.style.bottom = '20px';
                message.style.right = '20px';
                message.style.padding = '10px 20px';
                message.style.backgroundColor = '#ff0000';
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