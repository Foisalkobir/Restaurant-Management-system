<?php
session_start();
require_once('../model/userModel.php');

if (!isset($_SESSION['status'])) {
    header('Location: login.html');
    exit();
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); 

    $errors = [];
    if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        if ($password === '') {
            $password = $user['password'];
        } else {
            // Optionally hash password here
            // $password = password_hash($password, PASSWORD_DEFAULT);
        }

        $updated = updateUser($user['id'], $username, $email, $password, $user['account_type']);
        if ($updated) {
            $_SESSION['user'] = getUserInfo($username);
            $success = "Profile updated successfully.";
            $user = $_SESSION['user']; // refresh user variable
        } else {
            $errors[] = "Failed to update profile. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Profile - RestaurantPro</title>
<link rel="stylesheet" href="../asset/css/profile.css">
</head>
<body>

<header>RestaurantPro Dashboard</header>

<nav>
  <a href="menu.php">Menu</a>
  <a href="cart.php">Cart</a>
  <a href="order_history.php">Orders</a>
  <a href="../controller/logout.php">Logout</a>
</nav>

<main>
  <h2>Edit Profile</h2>

  <?php if (!empty($errors)): ?>
    <div class="messages error">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php elseif (!empty($success)): ?>
    <div class="messages success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="POST">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required />

    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required />

    <label for="password">Password <small>(leave blank to keep current)</small></label>
    <input type="password" name="password" id="password" placeholder="New password" />

    <button type="submit">Save Changes</button>
  </form>
</main>

</body>
</html>
