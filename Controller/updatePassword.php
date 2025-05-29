<?php
session_start();
require_once('../model/userModel.php');

$message = '';
$message_class = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['reset_user_id'])) {
        $message = "Session expired. Please restart the password reset process.";
        $message_class = 'error';
    } else {
        $userId = $_SESSION['reset_user_id'];
        $password = trim($_POST['password'] ?? '');
        $confirm_pass = trim($_POST['confirm_password'] ?? '');

        if (!$password || !$confirm_pass) {
            $message = "Please enter all required fields.";
            $message_class = 'error';
        } elseif ($password !== $confirm_pass) {
            $message = "Passwords do not match.";
            $message_class = 'error';
        } elseif (strlen($password) < 4) {
            $message = "Password must be at least 4 characters long.";
            $message_class = 'error';
        } else {
            $status = updatePassword($userId, $password);
            if ($status) {
                $message = "Password updated successfully! <a href='../view/login.html'>Login here</a>";
                $message_class = 'success';
                unset($_SESSION['reset_user_id']);
                unset($_SESSION['reset_question']);
                unset($_SESSION['can_reset_password']);
            } else {
                $message = "Failed to update password. Please try again.";
                $message_class = 'error';
            }
        }
    }
} else {
    header('Location: ../view/forgot_password.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Password Update Result</title>
<link rel="stylesheet" href="../asset/css/updatepass.css">
</head>
<body>
  <div class="container">
    <p class="<?= htmlspecialchars($message_class) ?>">
      <?= $message ?>
    </p>
  </div>
</body>
</html>
