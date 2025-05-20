<?php
session_start();
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/userModel.php';

try {
    // 1) Only handle POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../View/login.php');
        exit;
    }

    // 2) Gather & validate inputs
    $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    $errors = [];
    if (!$email)    $errors[] = 'Please enter a valid email.';
    if (!$password) $errors[] = 'Please enter your password.';

    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        header('Location: ../View/login.php');
        exit;
    }

    // 3) Fetch user via the model
    $model = new UserModel($con); // Use $con from db.php
    $user  = $model->findByEmail($email); // Expects ['id','name','email','password','role'] or null

    if (!$user || !password_verify($password, $user['password'])) {
        // generic error 
        $_SESSION['login_errors'] = ['Invalid email or password.'];
        header('Location: ../View/login.php');
        exit;
    }

    // 4) Login success – store id, name, and role
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];

    header('Location: ../View/home.php');
    exit;

} catch (Exception $e) {
    // Log it, then show a generic error
    error_log($e->getMessage());
    $_SESSION['login_errors'] = ['Server error—please try again later.'];
    header('Location: ../View/login.php');
    exit;
}
