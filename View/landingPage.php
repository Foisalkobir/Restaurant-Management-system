<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/db.php');
require_once('../model/menuModel.php');
require_once('../model/orderModel.php');

$con = getConnection();

$today = date('Y-m-d');
$sqlMenu = "SELECT name, price FROM menu_items 
  WHERE (is_seasonal = 0 OR (availability_start <= ? AND availability_end >= ?))";
  $stmt = mysqli_prepare($con, $sqlMenu);
  mysqli_stmt_bind_param($stmt, "ss", $today, $today);
  mysqli_stmt_execute($stmt);
  $resultMenu = mysqli_stmt_get_result($stmt);
  $menuItems = mysqli_fetch_all($resultMenu, MYSQLI_ASSOC);
  mysqli_stmt_close($stmt);

$sqlOrders = "SELECT order_id, table_number, order_status FROM orders 
              WHERE order_status NOT IN ('Served', 'Cancelled')
              ORDER BY order_date DESC LIMIT 5";
$resultOrders = mysqli_query($con, $sqlOrders);
$liveOrders = mysqli_fetch_all($resultOrders, MYSQLI_ASSOC);

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>RestaurantPro - Complete Restaurant Management</title>
  <link rel="stylesheet" href="../asset/css/landingpage.css" />
</head>
<body>
  <section class="demo">
    <div class="container">
      <h2 class="section-title">See It In Action</h2>
      <div class="demo-grid">

        <div class="demo-card">
          <h3>📱 Today's Menu</h3>
          <?php if (empty($menuItems)): ?>
            <p>No menu items available today.</p>
          <?php else: ?>
            <?php foreach ($menuItems as $item): ?>
            <div class="menu-item">
              <span class="menu-item-name"><?= htmlspecialchars($item['name']) ?></span>
              <span class="menu-item-price">$<?= number_format($item['price'], 2) ?></span>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
          <button class="interactive-btn" onclick="updateMenu()">Update Menu</button>
        </div>

        <div class="demo-card">
          <h3>Live Orders</h3>
          <?php if (empty($liveOrders)): ?>
            <p>No live orders at the moment.</p>
          <?php else: ?>
            <?php foreach ($liveOrders as $order): ?>
            <div class="order-item">
              <span><?= $order['table_number'] ? "Table " . htmlspecialchars($order['table_number']) : "Takeout" ?> - Order #<?= htmlspecialchars($order['order_id']) ?></span>
              <span class="order-status status-<?= strtolower($order['order_status']) ?>"><?= htmlspecialchars($order['order_status']) ?></span>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
          <button class="interactive-btn" onclick="addOrder()">New Order</button>
        </div>

      </div>
    </div>
  </section>

  <script>
  </script>
</body>
</html>
