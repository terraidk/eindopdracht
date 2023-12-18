<?php
require("database.php");
if (isset($_POST['submit'])) {
    $targetDir = "images/"; // Directory where uploaded images will be stored
    $targetFile = $targetDir . basename($_FILES["car_image"]["name"]); // Get the file name

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $targetFile)) {
        // Establish database connection
        $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

        // Insert the image path into the database
        $stmt = $pdo->prepare("INSERT INTO cars (car_picture) VALUES (?)");
        $stmt->bindParam(1, $targetFile);
        $stmt->execute();

        // Close database connection
        $pdo = null;

        echo "The image " . htmlspecialchars(basename($_FILES["car_image"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="car_image">
        <input type="submit" value="Upload Image" name="submit">
    </form>

</body>

</html>