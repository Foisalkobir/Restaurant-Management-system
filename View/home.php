<?php
// home.php - Landing page after login
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Restaurant Management System</title>
  <link rel="stylesheet" href="../Asset/css/style.css">
</head>
<body>
  <?php include 'nav.php'; ?>

  <main class="container">
    <h1>Dashboard</h1>
    <p>Select an option from the menu to get started.</p>
    <div class="dashboard-widgets">
      <a href="menuEditor.php" class="widget-link"><div class="widget">Digital Menu</div></a>
      <a href="reservations.php" class="widget-link"><div class="widget">Table Reservations</div></a>
      <a href="orders.php" class="widget-link"><div class="widget">Order Tracking</div></a>
      <a href="staffScheduling.php" class="widget-link"><div class="widget">Staff Scheduling</div></a>
      <a href="inventory.php" class="widget-link"><div class="widget">Inventory</div></a>
      <a href="customerFeedback.php" class="widget-link"><div class="widget">Customer Feedback</div></a>
      <a href="salesReports.php" class="widget-link"><div class="widget">Sales Reports</div></a>
    </div>
  </main>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> Restaurant Management System</p>
  </footer>
</body>
</html>
