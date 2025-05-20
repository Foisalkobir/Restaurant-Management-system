<?php

session_start();
require_once __DIR__ . '/../Model/db.php';  

$name     = trim($_POST['name']               ?? '');
$email    = trim($_POST['email']              ?? '');
$password =           $_POST['password']       ?? '';
$confirm  =           $_POST['confirm_password'] ?? '';

$errors = [];

if ($name === '') {
    $errors[] = 'Name is required.';
}
if ($email === '') {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
}

$stmt = mysqli_prepare($con, 'SELECT COUNT(*) FROM users WHERE email = ?');
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $count);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
if ($count > 0) {
    $errors[] = 'Email is already registered.';
}

if (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}
if ($password !== $confirm) {
    $errors[] = 'Passwords do not match.';
}


if (!empty($errors)) {
    $_SESSION['signup_errors'] = $errors;
    $_SESSION['old'] = ['name' => $name, 'email' => $email];
    header('Location: ../View/signup.php');
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare(
    $con,
    'INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())'
);
mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hash);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($ok) {
    $_SESSION['success'] = 'Account created successfully. Please log in.';
    header('Location: ../View/login.php');
    exit;
} else {
    $_SESSION['signup_errors'] = ['Database error: ' . mysqli_error($con)];
    header('Location: ../View/signup.php');
    exit;
}
