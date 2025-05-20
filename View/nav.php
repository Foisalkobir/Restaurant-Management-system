<?php
$userName = $_SESSION['user_name'] ?? 'Guest';
$userRole = $_SESSION['user_role'] ?? 'user';
?>
<nav class="navbar">
  <div class="nav-left">
    <a href="home.php">Dashboard</a>

    <?php if ($userRole === 'admin'): ?>
      <a href="menuEditor.php">Digital Menu</a>
    <?php else: ?>
      <a href="customerMenu.php">Digital Menu</a>
    <?php endif; ?>

    <a href="reservations.php">Reservations</a>
    <a href="orders.php">Orders</a>
    <?php if ($userRole === 'admin'): ?>
      <a href="staffScheduling.php">Staff Scheduling</a>
      <a href="inventory.php">Inventory</a>
      <a href="customerFeedback.php">Customer Feedback</a>
      <a href="salesReports.php">Sales Reports</a>
    <?php else: ?>
      <a href="customerFeedback.php">Customer Feedback</a>
    <?php endif; ?>
  </div>
  <div class="nav-right">
    <span>Welcome, <?= htmlspecialchars($userName) ?>!</span>
    <a href="../Controller/logout.php" class="btn-logout">Logout</a>
  </div>
</nav>

<style>
.navbar {
  background: #343a40;
  color: #fff;
  padding: 10px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.navbar a {
  color: #fff;
  margin-right: 15px;
  font-weight: bold;
  text-decoration: none;
}
.navbar a:hover {
  text-decoration: underline;
}
.btn-logout {
  background: #dc3545;
  color: #fff;
  padding: 6px 12px;
  border-radius: 4px;
  text-decoration: none;
  margin-left: 10px;
}
.btn-logout:hover {
  background: #c82333;
}
</style>
