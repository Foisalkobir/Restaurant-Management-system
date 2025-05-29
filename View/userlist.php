<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

// Fetch users from DB
require_once('../model/userModel.php');
$users = getAllUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User Management - Admin</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<style>
  main {
    max-width: 1200px;
    margin: 6rem auto 3rem; 
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
  }
  th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
  }
  th {
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
    color: white;
    font-weight: 600;
  }
  tr:hover {
    background: #f9f9f9;
  }
  a.action-link {
    color: #ff6b6b;
    font-weight: 600;
    margin-right: 10px;
    text-decoration: none;
  }
  a.action-link:hover {
    text-decoration: underline;
  }
  .topbar-spacer {
    height: 80px; 
  }
</style>
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

<div class="topbar-spacer"></div>

<main>
  <h1>User Management</h1>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Username</th><th>Email</th><th>Account Type</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['account_type']) ?></td>
        <td>
          <a href="editUser.php?id=<?= htmlspecialchars($user['id']) ?>" class="action-link">Edit</a>
          <a href="deleteUser.php?id=<?= htmlspecialchars($user['id']) ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p style="margin-top: 1.5rem;">
    <a href="addUser.php" class="cta-btn primary" style="padding: 10px 20px; font-size: 1rem;">Add New User</a>
    <a href="home.php" class="cta-btn" style="padding: 10px 20px; font-size: 1rem; margin-left: 10px;">Back to Dashboard</a>
  </p>
</main>

</body>
</html>
