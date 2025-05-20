<?php
// resetPassword.php - Show password reset form
require_once 'db.php';
session_start();

// Validate token
$token = isset($_GET['token']) ? trim($_GET['token']) : '';
if (empty($token)) {
    $_SESSION['reset_error'] = 'Invalid or missing token.';
    header('Location: login.html');
    exit;
}

$pdo = Database::getInstance()->getConnection();
$stmt = $pdo->prepare('SELECT email, expires_at FROM password_resets WHERE token = :token LIMIT 1');
$stmt->execute(['token' => $token]);
$reset = $stmt->fetch();

if (!$reset || strtotime($reset['expires_at']) < time()) {
    $_SESSION['reset_error'] = 'Token is invalid or has expired.';
    header('Location: forgotPassword.html');
    exit;
}

$email = htmlspecialchars($reset['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Restaurant Management System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="auth-container">
    <form id="resetForm" action="resetPasswordCheck.php" method="POST" novalidate>
      <h2>Reset Password</h2>
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required minlength="6" placeholder="••••••">
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="••••••">
      </div>
      <button type="submit" class="btn">Update Password</button>
      <p class="small-text"><a href="login.html">Back to Login</a></p>
    </form>
  </div>
  <script>
    document.getElementById('resetForm').addEventListener('submit', function(e) {
      var form = e.target;
      if (!form.checkValidity()) {
        e.preventDefault();
        alert('Please fill out the form correctly.');
        return;
      }
      if (form.password.value !== form.confirm_password.value) {
        e.preventDefault();
        alert('Passwords do not match.');
      }
    });
  </script>
</body>
</html>
