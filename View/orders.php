<?php
// View/orders.php - Kitchen Order Tracking Interface
session_start();
// Only logged-in users
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Use correct Model paths
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/orderModel.php';

$model = new OrderModel($con);
// Fetch in-progress orders
$orders = $model->getAllOrders('In Progress');

// Handle feedback
$success = $_SESSION['order_success'] ?? '';
$error   = $_SESSION['order_error'] ?? '';
unset($_SESSION['order_success'], $_SESSION['order_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Tracking - Restaurant Management</title>
  <link rel="stylesheet" href="../Asset/css/style.css">
  <style>
    .order-queue { background:#fff; padding:20px; border-radius:8px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ddd; padding:8px; text-align:left; }
    th { background:#f4f4f4; }
    .btn { padding:6px 12px; margin-right:4px; }
    .status-In\ Progress { color: #ffc107; }
    .status-Ready { color: #28a745; }
    .status-Completed, .status-Cancelled { color: #6c757d; }
  </style>
</head>
<body>
  <?php include 'nav.php'; ?>
  <?php if ($success): ?>
    <p style="color:green; margin:10px 20px;"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>
  <?php if ($error): ?>
    <p style="color:red; margin:10px 20px;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <main class="container">
    <h1>Order Queue</h1>
    <div class="order-queue">
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Table</th>
            <th>Ordered At</th>
            <th>Items</th>
            <th>Status</th>
            <th>Age (min)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($orders)): ?>
          <tr><td colspan="7">No in-progress orders.</td></tr>
        <?php else: ?>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td><?= htmlspecialchars($order['id']) ?></td>
              <td><?= htmlspecialchars($order['table_number']) ?></td>
              <td><?= htmlspecialchars($order['order_datetime']) ?></td>
              <td><?= htmlspecialchars($order['items']) ?></td>
              <td class="status-<?= str_replace(' ', '_', htmlspecialchars($order['status'])) ?>"><?= htmlspecialchars($order['status']) ?></td>
              <td><?= htmlspecialchars($order['ticket_age']) ?></td>
              <td>
                <form method="POST" action="../Controller/orderActions.php" style="display:inline;">
                  <input type="hidden" name="action" value="updateStatus">
                  <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                  <input type="hidden" name="ticket_age" value="<?= $order['ticket_age'] ?>">
                  <button type="submit" name="status" value="Ready" class="btn">Mark Ready</button>
                  <button type="submit" name="status" value="Completed" class="btn btn-secondary">Complete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
