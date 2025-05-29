<?php
session_start();
require_once('../model/userModel.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['reset_user_id'])) {
    header('Location: forgot_password.html');
    exit();
}

$userId = $_SESSION['reset_user_id'];
$answer = trim($_POST['answer'] ?? '');

$user = getUserById($userId);

if (!$user) {
    echo "User not found.";
    exit();
}

if (strcasecmp($answer, $user['answer']) !== 0) {
    echo "Incorrect answer. <a href='securityQuestion.php'>Try again</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Reset Password</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<link rel="stylesheet" href="../css/confirm-reset-pass.css" />
</head>
<body>

<form method="POST" action="../controller/confirmresetpas.php" onsubmit="return validatePasswords();">
  <fieldset>
    <legend>Reset Password</legend>
    <h2>Enter your new password</h2>
    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" required minlength="4" />
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required minlength="4" />
    <input type="submit" value="Reset Password" />
    <a href="login.html">Cancel</a>
  </fieldset>
</form>

<script>
  function validatePasswords() {
    const pass = document.getElementById('password').value.trim();
    const confirmPass = document.getElementById('confirm_password').value.trim();
    if (pass.length < 4) {
      alert('Password must be at least 4 characters.');
      return false;
    }
    if (pass !== confirmPass) {
      alert('Passwords do not match.');
      return false;
    }
    return true;
  }
</script>

</body>
</html>
