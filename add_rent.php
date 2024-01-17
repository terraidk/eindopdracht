<?php
session_start();

// Redirect if the user is not an admin
if (!isset($_SESSION['loggedInAdmin']) && !isset($_SESSION['loggedInWorker'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit;
}

require('database.php');

// Establish the database connection here
$database = new Database();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id']) && isset($_POST['car_id']) && isset($_POST['rent_startdate']) && isset($_POST['rent_enddate']) && isset($_POST['rent_price'])) {
        $user_id = $_POST['user_id'];
        $car_id = $_POST['car_id'];
        $rent_startdate = $_POST['rent_startdate'];
        $rent_enddate = $_POST['rent_enddate'];
        $rent_price = $_POST['rent_price'];

        // Insert data into the database
        $stmt = $database->prepare("INSERT INTO renting (user_id, car_id, rent_startdate, rent_enddate, rent_price) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $car_id, $rent_startdate, $rent_enddate, $rent_price])) {
            header("Location: manage_rents.php?addedRent=true");
            exit;
        } else {
            $error = "Error adding the rent entry.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rent Entry</title>
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
            <p style="font-size: 25px; color: white;"> Add a rent</p>
        </div>
    </nav>

    <div class="forms">
        <form action="add_rent.php" method="POST">
            <h2 style="text-align: center;">Add a New Rent Entry</h2>

            <label>User ID:</label>
            <input type="number" name="user_id" required><br><br>

            <label>Car ID:</label>
            <input type="number" name="car_id" required><br><br>

            <label>Rent Start Date:</label>
            <input type="datetime-local" name="rent_startdate" required><br><br>

            <label>Rent End Date:</label>
            <input type="datetime-local" name="rent_enddate" required><br><br>

            <label>Rent Price:</label>
            <input type="number" name="rent_price" step="0.01" required><br><br>

            <input type="submit" value="Add Rent Entry">
        </form>

        <?php if ($error): ?>
            <p style='color: red; text-align: center; font-size: 20px;'>
                <?php echo $error; ?>
            </p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const addedCar = urlParams.get('addedRent');

            if (addedCar === 'true') {
                const message = document.createElement('div');
                message.textContent = 'Rent added successfully!';
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
    </script>

</body>

</html>