<?php
require 'database.php';

$pdo = new Database();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'] ?? null;
    $email = $_POST['email'] ?? null;
    $currentPassword = $_POST['password'] ?? null;
    $newPassword = $_POST['passwordnew'] ?? null;
    $confirmNewPassword = $_POST['passwordconfirm'] ?? null;
    $id = $_GET['id'] ?? null;

    if ($address && $email && $currentPassword && $id) {
        $user = $pdo->getUserById($id);

        if ($user && password_verify($currentPassword, $user['password'])) {
            if ($newPassword === $confirmNewPassword) {
                // Check if the updated email is not already in use
                $existingUser = $pdo->getUserByEmail($email);

                if (!$existingUser || $existingUser['user_id'] == $id) {
                    $pdo->editUser($id, $email, $newPassword, $address);
                    header("Location: profile.php");
                    exit;
                } else {
                    $error = "<p style='color: red; text-align: center; font-size: 20px;'>Email already in use by another user.</p>";
                }
            } else {
                $error = "<p style='color: red; text-align: center; font-size: 20px;'>New passwords don't match.<p>";
            }
        } else {
            $error = "<p style='color: red; text-align: center; font-size: 20px;'>Incorrect current password.<p>";
        }
    } else {
        $error = "<p style='color: red; text-align: center; font-size: 20px;'>Missing required information.<p>";
    }
}

$id = $_GET['id'] ?? null;

if ($id) {
    $user = $pdo->getUserById($id);
} else {
    $user = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");

        * {
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to bottom, rgba(140, 0, 140), rgba(140, 0, 140, 0.3)) no-repeat;
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        #submitbutton {
            background: linear-gradient(to left, rgba(140, 0, 140), rgba(140, 0, 140, 0.4), rgba(140, 0, 140)) no-repeat;
        }

        .btn-edit {
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($user): ?>
            <h2>Edit User</h2>
            <hr>
            <?php if ($error): ?>
                <p class="error-message">
                    <?php echo $error; ?>
                </p>
            <?php endif; ?>
            <form action="profileedit.php?id=<?php echo $id; ?>" method="POST">
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" value="<?php echo $user['address']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Current password</label>
                    <input type="password" class="form-control" name="password" value="" required>
                </div>

                <div class="form-group">
                    <label for="passwordnew">New password</label>
                    <input type="password" class="form-control" name="passwordnew" value="">
                </div>

                <div class="form-group">
                    <label for="passwordconfirm">Confirm new password</label>
                    <input type="password" class="form-control" name="passwordconfirm" value="">
                    <?php if (isset($newPasswordError)): ?>
                        <p class="error-message">
                            <?php echo $newPasswordError; ?>
                        </p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-edit btn-block" id="submitbutton">Edit</button>
                <?php if (isset($errorMessage)): ?>
                    <p class="error-message">
                        <?php echo $errorMessage; ?>
                    </p>
                <?php endif; ?>
            </form>
        <?php else: ?>
            <p>No person found with the specified ID.</p>
        <?php endif; ?>
    </div>
</body>

</html>