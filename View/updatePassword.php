<?php
session_start();
require_once('../model/userModel.php');

if (isset($_POST['submit'])) {
    $userId = $_POST['user_id'] ?? null;
    $password = trim($_POST['password']);
    $confirm_pass = trim($_POST['confirm_password']);

    if (!$userId) {
        echo "Session expired. Please try again.";
        exit();
    }

    if (empty($password) || empty($confirm_pass)) {
        echo "Please enter all fields.";
        exit();
    }

    if ($password !== $confirm_pass) {
        echo "Passwords do not match.";
        exit();
    }

    if (strlen($password) < 4) {
        echo "Password must be at least 4 characters long.";
        exit();
    }
    $status = updatePassword($userId, $password);

    if ($status) {
        echo "Password updated successfully! <a href='../view/login.html'>Login here</a>";
        unset($_SESSION['reset_user_id']);
    } else {
        echo "Failed to update password. Try again.";
    }
} else {
    header('Location: forgot_password.html');
    exit();
}
