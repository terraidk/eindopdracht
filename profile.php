<?php
session_start();

require("database.php"); // Include the PDO setup

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: eindopdracht.php");
    exit(); // Ensure script stops if the user isn't valid
}

$db = new Database();

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
            <?php
            // Get user information using PDO
            $id = $_SESSION['loggedInUser'];
            $query = $db->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
            $query->bindParam(':user_id', $id);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $res_id = $result['user_id'];
                $res_name = $result['name'];
                $res_Email = $result['email'];
                $res_address = $result['address'];

                echo "<a href='profileedit.php?id=$res_id'>Edit Profile</a>";
            }
            ?>
            <a href="logout.php"> <button class="btn">Log Out</button> </a>
        </div>
    </div>
    <main>
        <div class="main-box top">
            <div class="top">
                <div class="box">
                    <p>Hello <b>
                            <?php echo $res_name ?? '' ?>
                        </b>, Welcome</p>
                </div>
                <div class="box">
                    <p>Your email is <b>
                            <?php echo $res_Email ?? '' ?>
                        </b>.</p>
                </div>
            </div>
            <div class="bottom">
                <div class="box">
                    <p style="text-align: center;">Your address is: <b>
                            <?php echo $res_address ?? '' ?>
                        </b>.</p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>