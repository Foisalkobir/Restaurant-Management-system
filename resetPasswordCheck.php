<?php
// resetPasswordCheck.php - Handle password reset submissions
require_once 'db.php';
session_start();

$token = isset($_POST['token']) ? trim($_POST['token']) : ''; $password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

$errors = [];

// Validate inputs
if (empty($token)) {
    $errors[] = 'Invalid request.';
}
if (empty($password) || strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}
if ($password !== $confirm) {
    $errors[] = 'Passwords do not match.';
}

if (!empty($errors)) {
    $_SESSION['reset_errors'] = $errors;
    header("Location: resetPassword.php?token=$token");
    exit;
}

// Verify token
$pdo = Database::getInstance()->getConnection();
$stmt = $pdo->prepare('SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW() LIMIT 1');
$stmt->execute(['token' => $token]);
$reset = $stmt->fetch();

if (!$reset) {
    $_SESSION['reset_errors'] = ['Token is invalid or has expired.'];
    header('Location: forgotPassword.html');
    exit;
}

$email = $reset['email'];

// Update user password
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('UPDATE users SET password = :password WHERE email = :email');
$stmt->execute(['password' => $hash, 'email' => $email]);

// Delete the token
$stmt = $pdo->prepare('DELETE FROM password_resets WHERE token = :token');
$stmt->execute(['token' => $token]);

$_SESSION['success'] = 'Your password has been updated. Please log in.';
header('Location: login.html');
exit;
?>
