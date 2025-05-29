<?php
session_start();
if (!isset($_SESSION['can_reset_password']) || !$_SESSION['can_reset_password']) {
    header('Location: forgot_password.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Reset Password</title>
<link rel="stylesheet" href="../asset/css/info.css" />
<link rel="stylesheet" href="../asset/css/resetpass.css">
<script src="../asset/js/forgotPass.js" defer></script>
</head>
<body>

<form id="resetPasswordForm" method="POST" action="../controller/updatePassword.php" onsubmit="return validateResetPassword()">
  <fieldset>
    <legend>Reset Password</legend>
    <h2>Enter your new password</h2>

    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" required minlength="4" onkeyup="validatePassword()" />
    <p id="passwordMessage" class="validation-message"></p>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required minlength="4" onkeyup="validateConfirmPassword()"/>
    <p id="confirmPasswordMessage" class="validation-message"></p>

    <input type="submit" value="Reset Password" />
    <a href="login.html">Cancel</a>
  </fieldset>
</form>

</body>
</html>
