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

// Process form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated data from the form
    $updateData = $_POST;

    // Create the SET part of the SQL query dynamically
    $setClause = [];
    $values = [];
    foreach ($updateData as $key => $value) {
        // Exclude the rent_id from the update
        if ($key != 'rent_id') {
            $setClause[] = "$key = ?";
            $values[] = $value;
        }
    }

    // Update the rent record in the database
    $values[] = $rent_id; // Add the rent_id for WHERE clause
    $update_stmt = $database->prepare("UPDATE renting SET " . implode(', ', $setClause) . " WHERE rent_id = ?");
    $update_stmt->execute($values);

    // Redirect to the manage rents page after updating
    header("Location: manage_rents.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel - Edit Rent</title>
    <link rel="stylesheet" href="styles/edit_rent.css">
</head>

<body>
    <div class="edit-rent-form">
        <h2>Edit Rent</h2>
        <form action="edit_rent.php?rent_id=<?php echo $rent_id; ?>" method="POST">
            <?php foreach ($rent as $key => $value): ?>
                <label for="<?php echo $key; ?>">
                    <?php echo ucfirst($key); ?>:
                </label>
                <input type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $value; ?>"
                    required> <br> <br>
            <?php endforeach; ?>

            <input type="submit" value="Save Changes">
        </form>
    </div>
</body>

</html>