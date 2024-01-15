<?php
session_start();

require_once('database.php');

$database = new Database();
$pdo = $database->pdo;

if (!isset($_SESSION['loggedInAdmin'])) {
    header("Location: inloggen.php");
    exit;
}

$stmt = $database->prepare("SELECT * FROM renting");
$stmt->execute();
$rents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel - Manage Rents</title>
    <link rel="stylesheet" href="styles/manage_rents.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="rents-table">
        <h2 style="text-align: center;">Manage Rents</h2>
        <table border="1">
            <tr>
                <th>Rent ID</th>
                <th>Rent Date</th>
                <th>User ID</th>
                <th>Car ID</th>
                <th>Rent Start Date</th>
                <th>Rent End Date</th>
                <th>Rent Price</th>
                <th colspan="2">Action</th>
            </tr>

            <?php foreach ($rents as $rent): ?>
                <tr>
                    <td>
                        <?php echo $rent['rent_id']; ?>
                    </td>
                    <td>
                        <?php echo $rent['rent_date']; ?>
                    </td>
                    <td>
                        <?php echo $rent['user_id']; ?>
                    </td>
                    <td>
                        <?php echo $rent['car_id']; ?>
                    </td>
                    <td>
                        <?php echo $rent['rent_startdate']; ?>
                    </td>
                    <td>
                        <?php echo $rent['rent_enddate']; ?>
                    </td>
                    <td>
                        <?php echo $rent['rent_price']; ?>
                    </td>
                    <td>
                        <!-- Edit Button -->
                        <a class="btn btn-info" href="edit_rent.php?rent_id=<?php echo $rent['rent_id']; ?>">Edit</a>
                    </td>
                    <td>
                        <!-- Delete Button -->
                        <a class="btn btn-danger" href="delete_rent.php?rent_id=<?php echo $rent['rent_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>