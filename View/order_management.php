<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

require_once('../model/orderModel.php');

if (isset($_GET['delete'])) {
    $orderId = (int)$_GET['delete'];
    if (deleteOrder($orderId)) {
        header('Location: order_management.php?msg=deleted');
        exit();
    } else {
        $error = "Failed to delete order #$orderId.";
    }
}

$orders = getAllOrders();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Order Management - Admin Dashboard</title>
<link rel="stylesheet" href="../asset/css/order_management.css" />
</head>
<body>

<h1>Order Management</h1>

<?php if (!empty($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
  <p class="success">Order deleted successfully.</p>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<p style="text-align:center; margin-bottom: 15px;">
    <a class="button" href="add_order.php">Add New Order</a>
</p>

<?php if (empty($orders)): ?>
  <p style="text-align:center; color:white; font-size:1.2rem;">No orders found.</p>
<?php else: ?>
<table>
  <thead>
    <tr>
      <th>Order ID</th>
      <th>User</th>
      <th>Date</th>
      <th>Payment Method</th>
      <th>Address</th>
      <th>Phone</th>
      <th>Total Amount</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($orders as $order): ?>
    <tr>
      <td><?= htmlspecialchars($order['order_id']) ?></td>
      <td><?= htmlspecialchars($order['username']) ?></td>
      <td><?= htmlspecialchars($order['order_date']) ?></td>
      <td><?= htmlspecialchars($order['payment_method']) ?></td>
      <td><?= htmlspecialchars($order['address']) ?></td>
      <td><?= htmlspecialchars($order['phone']) ?></td>
      <td>$<?= number_format($order['total_amount'], 2) ?></td>
      <td><?= htmlspecialchars($order['status']) ?></td>
      <td>
        <a class="button" href="edit_order.php?id=<?= $order['order_id'] ?>">Edit</a>
        <a class="button" href="?delete=<?= $order['order_id'] ?>" onclick="return confirm('Are you sure to delete this order?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<a class="back" href="home.php">Back to Dashboard</a>

</body>
</html>
