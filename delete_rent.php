<?php
session_start();

require_once('database.php');

$database = new Database();
$pdo = $database->pdo;

if (!isset($_SESSION['loggedInAdmin']) && !isset($_SESSION['loggedInWorker'])) {
    header("Location: inloggen.php");
    exit;
}

// Check if rent_id is provided in the URL
if (!isset($_GET['rent_id'])) {
    header("Location: manage_rents.php");
    exit;
}

$rent_id = $_GET['rent_id'];

// Retrieve the rent details based on rent_id
$stmt = $database->prepare("SELECT * FROM renting WHERE rent_id = ?");
$stmt->execute([$rent_id]);
$rent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rent) {
    echo "Rent not found.";
    exit;
}

// Process form submission for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete the rent record from the database
    $delete_stmt = $database->prepare("DELETE FROM renting WHERE rent_id = ?");
    $delete_stmt->execute([$rent_id]);

    // Update the car's availability to make it available again
    $update_car_stmt = $database->prepare("UPDATE cars SET car_availability = 1 WHERE car_id = ?");
    $update_car_stmt->execute([$rent['car_id']]);

    // Redirect to the manage rents page after deletion
    header("Location: manage_rents.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel - Delete Rent</title>
    <link rel="stylesheet" href="styles/delete_rent.css">
</head>

<body>
    <div class="delete-rent-confirmation">
        <h2>Delete Rent</h2>
        <p>Are you sure you want to delete this rent?</p>
        <p>Rent ID: <?php echo $rent['rent_id']; ?>
        </p>
        <p>Rent Date:
            <?php echo $rent['rent_date']; ?>
        </p>
        <p>
            Car ID: <?php echo $rent['car_id']; ?>
        </p>
        <p>
            Customer User ID:
            <?php echo $rent['user_id']; ?>
        </p>
        <!-- Display more details as needed -->

        <form action="delete_rent.php?rent_id=<?php echo $rent_id; ?>" method="POST">
            <input type="submit" value="Confirm Delete">
        </form>
    </div>
</body>

</html>
