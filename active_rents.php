<?php
session_start();

// Redirect if user is not an admin
if (!isset($_SESSION['loggedInAdmin']) && !isset($_SESSION['loggedInWorker'])) {
    header("Location: inloggen.php");
    session_destroy();
    exit;
}

require_once('database.php');


?>

<!DOCTYPE html>
<html>

<head>
    <title>Active Rents</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Active Rents</h2>
    <table>
        <thead>
            <tr>
                <th>Rent ID</th>
                <th>Rent Date</th>
                <th>User ID</th>
                <th>Car ID</th>
                <th>Rent Start Date</th>
                <th>Rent End Date</th>
                <th>Rent Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to your database
            require_once('database.php');
            $database = new Database();

            // Fetch data from the renting table
            $rents = $database->getAllRents(); // Assuming you have a method to fetch all rents
            
            foreach ($rents as $rent) {
                echo "<tr>";
                echo "<td>" . $rent['rent_id'] . "</td>";
                echo "<td>" . $rent['rent_date'] . "</td>";
                echo "<td>" . $rent['user_id'] . "</td>";
                echo "<td>" . $rent['car_id'] . "</td>";
                echo "<td>" . $rent['rent_startdate'] . "</td>";
                echo "<td>" . $rent['rent_enddate'] . "</td>";
                echo "<td>" . $rent['rent_price'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>