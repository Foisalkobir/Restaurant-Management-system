<?php
// forgotPasswordCheck.php - Handle password reset requests

require_once 'db.php';
require_once 'userModel.php';
session_start();

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$errors = [];

// Validate email
if (empty($email)) {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
} elseif (!UserModel::existsByEmail($email)) {
    $errors[] = 'No account found with that email.';
}

if (!empty($errors)) {
    $_SESSION['forgot_errors'] = $errors;
    header('Location: forgotPassword.html');
    exit;
}

// Generate reset token and expiry
$token = bin2hex(random_bytes(16)); $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour

// Store token in password_resets table
$pdo = Database::getInstance()->getConnection();
$stmt = $pdo->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires)');
$stmt->execute(['email' => $email, 'token' => $token, 'expires' => $expires]);

// Send reset email (simplified)
$resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/resetPassword.php?token=$token";
$subject = 'Password Reset Request';
$message = "Click the link to reset your password: $resetLink \nLink expires in 1 hour.";
$headers = 'From: no-reply@yourdomain.com' . "\r\n" .
           'Reply-To: no-reply@yourdomain.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

mail($email, $subject, $message, $headers);

$_SESSION['success'] = 'If that email is registered, a reset link has been sent.';
header('Location: login.html');
exit;
?>
