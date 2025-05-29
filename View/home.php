<?php
session_start();
if (!isset($_SESSION['status'])) {
    header('Location: login.html');
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard - RestaurantPro</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<link rel="stylesheet" href="../asset/css/home.css" />
</head>
<body>

<header>
  <div class="header-content">
    <div class="logo">RestaurantPro Dashboard</div>
    <nav class="nav-buttons">
      <?php if ($user['account_type'] === 'admin'): ?>
        <a href="userlist.php" class="nav-btn">Users</a>
        <a href="menu_management.php" class="nav-btn">Manage Menu</a>
        <a href="order_management.php" class="nav-btn">Orders</a>
        <a href="infoedit.php" class="nav-btn">Info Update</a>
        <a href="reservation_management.php" class="nav-btn">Reservations</a>
      <?php else: ?>
        <a href="menu.php" class="nav-btn">Menu</a>
        <a href="cart.php" class="nav-btn">Cart</a>
        <a href="profile.php?id=<?= htmlspecialchars($user['id']) ?>" class="nav-btn">Profile</a>
        <a href="order_history.php" class="nav-btn">Orders</a>
        <a href="reservation.php" class="nav-btn">Reservations</a>
      <?php endif; ?>
      <a href="../controller/logout.php" class="nav-btn">Logout</a>
    </nav>
  </div>
</header>

<main class="container" style="margin-top: 100px; padding-bottom: 40px;">
  <div class="welcome">
    <h2>Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>
    <p>Your role: <?= htmlspecialchars(ucfirst($user['account_type'])) ?></p>
  </div>

  <?php if ($user['account_type'] !== 'admin'): ?>
  <section class="reservation-section">
    <h3>Book a Table</h3>
    <p>Reserve your table easily with our online booking system.</p>
    <button class="interactive-btn" onclick="window.location.href='reservation.php'">Make a Reservation</button>
  </section>
  <?php else: ?>
  <section class="reservation-section">
    <p>Admin controls will appear here.</p>
  </section>
  <?php endif; ?>
</main>

</body>
</html>
