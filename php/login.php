<?php

session_start();

$users = [
    ["email" => "admin@example.com", "password" => "admin123", "role" => "admin"],
    ["email" => "john@example.com", "password" => "user123", "role" => "user"]
];


$email = trim($_POST['email']);
$password = $_POST['password'];


$authenticatedUser = null;
foreach ($users as $user) {
    if ($user['email'] === $email && $user['password'] === $password) {
        $authenticatedUser = $user;
        break;
    }
}

if ($authenticatedUser) {

    $_SESSION['loggedInUser'] = $authenticatedUser['email'];
    $_SESSION['userRole'] = $authenticatedUser['role'];

    
    if ($authenticatedUser['role'] === 'admin') {
        header("Location: ../View/adminprofile.php");
    } else {
        header("Location: ../View/profile.php");
    }
    exit();
} else {
    exit();
}
?>