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
    <link rel="stylesheet" href="styles/profileedit.css">
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