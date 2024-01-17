<?php
session_start();

// Redirect if the user is not an admin
if (!isset($_SESSION['loggedInAdmin'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit;
}

require('database.php');

// Establish the database connection here
$database = new Database();
$result = "";
$error = "";
$error2 = "";

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
                header("Location: admin_panel.php?addedCar=true");
                exit;
            } else {
                echo "Error adding the car.<br>";
            }
        } else {
            $defaultImage = 'images/default_image.jpg';
            $image = file_get_contents($defaultImage);
        }
    }

    if (isset($_POST['addAdmin'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password for security

        $existingUser = $database->getUserByEmail($email);

        if (!$existingUser) {
            if ($database->addAdmin($name, $email, $password)) {
                header("Location: admin_panel.php?addedAdmin=true");
                exit;
            } else {
                $error = "Error adding the admin.";
            }
        } else {
            $error = "Email already in use by someone else.";
        }
    }

    if (isset($_POST['addWorker'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password for security
        $address = ''; // Add the default value for the address

        $existingUser = $database->getUserByEmail($email);

        if (!$existingUser) {
            if ($database->addWorker($name, $email, $password, $address)) {
                header("Location: admin_panel.php?addedWorker=true");
                exit;
            } else {
                $error2 = "Error adding the worker.";
            }
        } else {
            $error2 = "Email already in use by someone else.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Car/Worker/Admin</title>
    <link rel="stylesheet" href="styles/adminpanel.css">
    <link rel="stylesheet" href="styles/nav.css">
</head>

<body>

<nav>
    <!-- LOGO -->
    <div class="logo_top_left">
        <a href="eindopdracht.php"><img class="logo_navbar" src="images/logo.png" alt="logo"></a>
    </div>
    <div>
        <p style="font-size: 25px; color: white;">Admin panel</p>
    </div>
</nav>

    <div class="forms">
        <form action="admin_panel.php" method="POST" enctype="multipart/form-data">
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

        </form>

        

        <form action="admin_panel.php" method="POST">
            <h2 style="text-align: center;">Add a New Admin User</h2>
            <label>Name:</label>
            <input type="text" name="name" required><br><br>

            <label>Email:</label>
            <input type="text" name="email" required><br><br>

            <label>Password:</label>
            <input type="password" name="password" required><br><br>

            <input type="submit" value="Add Admin User">
            <?php if ($error): ?>
                <p style='color: red; text-align: center; font-size: 20px;'>
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>
            </form>
                
                <form action="admin_panel.php" method="POST">
                    <h2 style="text-align: center;">Add a New Worker</h2>
                    <input type="hidden" name="addWorker" value="true">
                
                    <label>Name:</label>
                    <input type="text" name="name" required><br><br>
                
                    <label>Email:</label>
                    <input type="text" name="email" required><br><br>
                
                    <label>Password:</label>
                    <input type="password" name="password" required><br><br>
                
                    <input type="submit" value="Add Worker">
                    <?php if ($error2): ?>
                        <p style='color: red; text-align: center; font-size: 20px;'>
                            <?php echo $error2; ?>
                        </p>
                    <?php endif; ?>
                </form>
            </div>

    <a href="manage_rents.php" style="position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background-color: rgb(140, 0, 140); color: #fff; border-radius: 5px; text-decoration: none; z-index: 9999;">Manage Rents</a>

c
    <a href="manage_cars.php" style="position: fixed; bottom: 120px; right: 20px; padding: 10px 20px; background-color: rgb(140, 0, 140); color: #fff; border-radius: 5px; text-decoration: none; z-index: 9999;">Manage Cars</a>


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

                // Automatically remove the message after a few seconds (optional)
                setTimeout(function () {
                    message.remove();
                }, 5000); // Adjust the time as needed (here it's set to 5 seconds)
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const addedAdmin = urlParams.get('addedAdmin'); // Corrected variable name

        if (addedAdmin === 'true') {
            const message = document.createElement('div');
            message.textContent = 'Admin added successfully!';
            message.style.position = 'fixed';
            message.style.top = '20px';
            message.style.right = '20px';
            message.style.padding = '10px 20px';
            message.style.backgroundColor = '#42b883';
            message.style.color = '#fff';
            message.style.borderRadius = '5px';
            message.style.zIndex = '9999';

            document.body.appendChild(message);

            // Automatically remove the message after a few seconds (optional)
            setTimeout(function () {
                message.remove();
            }, 5000); // Adjust the time as needed (here it's set to 5 seconds)
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const addedWorker = urlParams.get('addedWorker'); // Corrected variable name

    if (addedWorker === 'true') {
        const message = document.createElement('div');
        message.textContent = 'Worker added successfully!';
        message.style.position = 'fixed';
        message.style.top = '20px';
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