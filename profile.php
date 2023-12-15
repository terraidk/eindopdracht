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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #e4e9f7;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
        }

        .box {
            background: #fdfdfd;
            display: flex;
            flex-direction: column;
            padding: 25px 25px;
            border-radius: 20px;
            box-shadow: 0 0 128px 0 rgba(0, 0, 0, 0.1),
                0 32px 64px -48px rgba(0, 0, 0, 0.5);
        }

        .form-box {
            width: 450px;
            margin: 0px 10px;
        }

        .form-box header {
            font-size: 25px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #e6e6e6;
            margin-bottom: 10px;
        }

        .form-box form .field {
            display: flex;
            margin-bottom: 10px;
            flex-direction: column;
        }

        .form-box form .input input {
            height: 40px;
            width: 100%;
            font-size: 16px;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }

        .btn {
            height: 35px;
            background: rgba(140, 0, 140, 0.808);
            border: 0;
            border-radius: 5px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
            transition: all .3s;
            margin-top: 10px;
            padding: 0px 10px;
        }

        .btn:hover {
            opacity: 0.82;
        }

        .submit {
            width: 100%;
        }

        .links {
            margin-bottom: 15px;
        }

        .nav {
            background: #fff;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            line-height: 60px;
            z-index: 100;
        }

        .logo {
            font-size: 25px;
            font-weight: 900;

        }

        .logo a {
            text-decoration: none;
            color: #000;
        }

        .right-links a {
            padding: 0 10px;
        }

        main {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 60px;
        }

        .main-box {
            display: flex;
            flex-direction: column;
            width: 70%;
        }

        .main-box .top {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .bottom {
            width: 100%;
            margin-top: 20px;
        }

        .message {
            text-align: center;
            background: #f9eded;
            padding: 15px 0px;
            border: 1px solid #699053;
            border-radius: 5px;
            margin-bottom: 10px;
            color: red;
        }

        @media only screen and (max-width:840px) {
            .main-box .top {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .top .box {
                margin: 10px 10px;
            }

            .bottom {
                margin-top: 0;
            }
        }
    </style>
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