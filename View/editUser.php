<?php
session_start();
require_once('../model/userModel.php');

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid user ID.";
    exit();
}

$userId = (int)$_GET['id'];
$userData = getUser($userId);

if (!$userData) {
    echo "User not found.";
    exit();
}

$errors = [];
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $account_type = trim($_POST['account_type'] ?? '');

    if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (!in_array($account_type, ['admin', 'user', 'advertiser', 'webmaster'])) {
        $errors[] = "Invalid account type.";
    }

    if (empty($errors)) {
        $updated = updateUser($userId, $username, $email, $userData['password'], $account_type);
        if ($updated) {
            $successMsg = "User updated successfully.";
            $userData = getUser($userId);
        } else {
            $errors[] = "Failed to update user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit User - Admin Panel</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<link rel="stylesheet" href="../asset/css/edit-user.css" />
</head>
<body>

<header>
  <div class="header-content">
    <div class="logo">Admin Panel - RestaurantPro</div>
    <div class="nav-buttons">
      <a href="home.php" class="nav-btn">Dashboard</a>
      <a href="userlist.php" class="nav-btn">Users</a>
      <a href="menu_management.php" class="nav-btn">Menu Management</a>
      <a href="../controller/logout.php" class="nav-btn">Logout</a>
    </div>
  </div>
</header>

<div style="height:80px;"></div>

<main>
  <h1>Edit User #<?= htmlspecialchars($userData['id']) ?></h1>

  <?php if (!empty($errors)): ?>
    <div class="error-msg">
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($successMsg): ?>
    <div class="success-msg"><?= htmlspecialchars($successMsg) ?></div>
  <?php endif; ?>

  <form method="POST">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($userData['username']) ?>" required />

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required />

    <label for="account_type">Account Type</label>
    <select id="account_type" name="account_type" required>
      <option value="user" <?= $userData['account_type'] === 'user' ? 'selected' : '' ?>>User</option>
      <option value="admin" <?= $userData['account_type'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <button type="submit">Update User</button>
  </form>

  <a href="userlist.php" class="back-link">&larr; Back to User List</a>
</main>

</body>
</html>
