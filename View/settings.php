<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['status'])) {
    header('Location: login.html');
    exit();
}

$user = $_SESSION['user'];
$isAdmin = ($user['account_type'] === 'admin');

require_once('../model/userModel.php');
require_once('../model/settingsModel.php');  

$successMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Sanitize and validate inputs
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if (!$email) {
            $errorMsg = "Please enter a valid email address.";
        } elseif ($password !== $password_confirm) {
            $errorMsg = "Passwords do not match.";
        } else {
            $updateStatus = updateUserProfile($user['id'], $email, $password);
            if ($updateStatus) {
                $successMsg = "Profile updated successfully.";
                $_SESSION['user']['email'] = $email;  // update session
            } else {
                $errorMsg = "Failed to update profile.";
            }
        }
    } elseif ($isAdmin && isset($_POST['update_settings'])) {
        $time_format = ($_POST['time_format'] === '12h') ? '12h' : '24h';

        $settingsUpdated = updateTimeFormat($user['username'], $time_format);
        if ($settingsUpdated) {
            $successMsg = "Settings updated successfully.";
        } else {
            $errorMsg = "Failed to update settings.";
        }
    }
}

$currentTimeFormat = $isAdmin ? getTimeFormat($user['username']) : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Settings - RestaurantPro</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9fafb;
    padding: 2rem;
    max-width: 600px;
    margin: auto;
  }
  h1 {
    text-align: center;
    margin-bottom: 1rem;
  }
  form {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
  }
  label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
  }
  input[type="email"], input[type="password"], select {
    width: 100%;
    padding: 8px;
    margin-bottom: 1rem;
    border: 2px solid #764ba2;
    border-radius: 5px;
    box-sizing: border-box;
  }
  button {
    background: linear-gradient(45deg, #ff6b6b, #764ba2);
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 600;
  }
  button:hover {
    background: linear-gradient(45deg, #764ba2, #ff6b6b);
  }
  .message {
    text-align: center;
    margin-bottom: 1rem;
  }
  .success {
    color: green;
  }
  .error {
    color: red;
  }
</style>
</head>
<body>

<h1>Settings</h1>

<?php if ($successMsg): ?>
    <p class="message success"><?= htmlspecialchars($successMsg) ?></p>
<?php endif; ?>
<?php if ($errorMsg): ?>
    <p class="message error"><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<form method="POST">
  <h2>Profile Settings</h2>
  <label for="email">Email</label>
  <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />

  <label for="password">New Password</label>
  <input type="password" id="password" name="password" placeholder="Leave blank to keep current password" />

  <label for="password_confirm">Confirm New Password</label>
  <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm new password" />

  <button type="submit" name="update_profile">Update Profile</button>
</form>

<?php if ($isAdmin): ?>
<form method="POST">
  <h2>Admin Settings</h2>
  <label for="time_format">Time Format</label>
  <select name="time_format" id="time_format" required>
    <option value="24h" <?= $currentTimeFormat === '24h' ? 'selected' : '' ?>>24-hour</option>
    <option value="12h" <?= $currentTimeFormat === '12h' ? 'selected' : '' ?>>12-hour</option>
  </select>

  <button type="submit" name="update_settings">Save Settings</button>
</form>
<?php endif; ?>

</body>
</html>
