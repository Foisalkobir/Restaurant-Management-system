<?php
session_start();
require_once('../model/userModel.php');

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $account_type = trim($_POST['account_type'] ?? '');

    // Basic validations
    if (!$username || strlen($username) < 4) {
        $error = 'Username must be at least 4 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (!$password || strlen($password) < 4) {
        $error = 'Password must be at least 4 characters.';
    } elseif (!in_array($account_type, ['admin', 'user'])) {
        $error = 'Invalid account type selected.';
    } elseif (userExists($username)) {
        $error = 'Username already exists.';
    } else {
        $status = addUser($username, $email, $password, $account_type, 'Default question?', 'Default answer');
        if ($status) {
            header('Location: userlist.php?msg=User+added+successfully');
            exit();
        } else {
            $error = 'Failed to add user. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add New User - Admin Panel</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<link rel="stylesheet" href="../asset/css/admin-add-user.css" />
</head>
<body>

<main>
  <h1>Add New User</h1>

  <?php if ($error): ?>
  <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST" novalidate>
    <label for="username">Username</label>
    <input type="text" name="username" id="username" required minlength="4" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />

    <label for="email">Email</label>
    <input type="email" name="email" id="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

    <label for="password">Password</label>
    <input type="password" name="password" id="password" required minlength="4" />

    <label for="account_type">Account Type</label>
    <select name="account_type" id="account_type" required>
      <option value="">-- Select --</option>
      <option value="admin" <?= (($_POST['account_type'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
      <option value="user" <?= (($_POST['account_type'] ?? '') === 'user') ? 'selected' : '' ?>>User</option>
    </select>

    <button type="submit">Add User</button>
  </form>

  <a href="userlist.php" class="back-link">Back to User List</a>
</main>

</body>
</html>
