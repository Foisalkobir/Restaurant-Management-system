<?php
session_start();
require_once('../model/userModel.php');

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid user ID.";
    exit();
}

$userId = (int)$_GET['id'];

if ($userId === $_SESSION['user']['id']) {
    // Prevent admin from deleting themselves
    echo "You cannot delete your own account.";
    exit();
}

$deleted = deleteUser($userId);

if ($deleted) {
    header("Location: userlist.php?msg=User+deleted+successfully");
    exit();
} else {
    echo "Failed to delete user.";
}
?>
