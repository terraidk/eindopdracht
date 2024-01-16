<?php
session_start();

require("database.php"); // Include the PDO setup

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: eindopdracht.php");
    exit(); // Ensure the script stops if the user isn't valid
}

$db = new Database();

// Get user information
$id = $_SESSION['loggedInUser'];
$query = $db->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$query->bindParam(':user_id', $id);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    header("Location: eindopdracht.php");
    exit();
}

$res_id = $result['user_id'];
$res_name = $result['name'];
$res_Email = $result['email'];
$res_address = $result['address'];

// Fetch rented cars information
$rentedCarsQuery = $db->pdo->prepare("SELECT cars.*, renting.rent_startdate, renting.rent_enddate
                                      FROM renting
                                      JOIN cars ON renting.car_id = cars.car_id
                                      WHERE renting.user_id = :user_id");
$rentedCarsQuery->bindParam(':user_id', $res_id);
$rentedCarsQuery->execute();
$rentedCars = $rentedCarsQuery->fetchAll(PDO::FETCH_ASSOC);
?>  

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>

<body>
    <div class="nav">
        <div class="right-links">
            <a href='profileedit.php?id=<?php echo $res_id ?>'>Edit Profile</a>
            <a href="logout.php"> <button class="btn">Log Out</button> </a>
        </div>
    </div>
    <main>
        <div class="main-box">
            <div class="box">
                <p style="text-align: center;">Hello <b><?php echo $res_name ?? '' ?>
                    </b>, Welcome</p>
                </div>
                <div class="box">
                <p style="text-align: center;">Your email is <b><?php echo $res_Email ?? '' ?>
                    </b>.</p>
                </div>
            <div class="box">
                <p style="text-align: center;">Your address is: <b><?php echo $res_address ?? '' ?>
                    </b>.</p>
            </div>
            <div class="rented-cars">
                <h2>Rented Cars</h2>
                <?php if (isset($rentedCars) && is_array($rentedCars) && !empty($rentedCars)): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Car</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rentedCars as $car): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($car['car_picture']): ?>
                                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($car['car_picture']); ?>"
                                                            alt="<?php echo $car['car_brand'] . ' ' . $car['car_model']; ?>">
                                                    <?php else: ?>
                                                        <img src="images/placeholder.png" alt="Placeholder">
                                                    <?php endif; ?>
                                                    <?php echo $car['car_brand'] . ' ' . $car['car_model']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $car['rent_startdate']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $car['rent_enddate']; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                <?php else: ?>
                    <p>No cars rented.</p>
                <?php endif; ?>
                </div>
        </div>
    </main>
</body>

</html>
