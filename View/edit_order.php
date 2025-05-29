<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

require_once('../model/orderModel.php');

$orderId = $_GET['id'] ?? null;
if (!$orderId || !is_numeric($orderId)) {
    die("Invalid order ID.");
}

$order = getOrderById($orderId);
if (!$order) {
    die("Order not found.");
}

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $status = $_POST['status'] ?? '';

    // Basic validation
    if (!$payment_method || !$address || !$phone || !$status) {
        $errorMsg = "Please fill all required fields.";
    } else {
        $updated = updateOrder($orderId, $payment_method, $address, $phone, $status);
        if ($updated) {
            $successMsg = "Order updated successfully.";
            // Refresh order info
            $order = getOrderById($orderId);
        } else {
            $errorMsg = "Failed to update order.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Order #<?= htmlspecialchars($orderId) ?> - RestaurantPro Admin</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<link rel="stylesheet" href="../asset/css/edit-order.css" />
</head>
<body>

<h1>Edit Order #<?= htmlspecialchars($orderId) ?></h1>

<?php if ($errorMsg): ?>
  <p class="error"><?= htmlspecialchars($errorMsg) ?></p>
<?php elseif ($successMsg): ?>
  <p class="success"><?= htmlspecialchars($successMsg) ?></p>
<?php endif; ?>

<form method="POST">
  <label for="payment_method">Payment Method</label>
  <select id="payment_method" name="payment_method" required>
    <option value="">-- Select Payment Method --</option>
    <option value="cash" <?= $order['payment_method'] === 'cash' ? 'selected' : '' ?>>Cash on Delivery</option>
    <option value="card" <?= $order['payment_method'] === 'card' ? 'selected' : '' ?>>Credit/Debit Card</option>
    <option value="mobile" <?= $order['payment_method'] === 'mobile' ? 'selected' : '' ?>>Mobile Payment</option>
  </select>

  <label for="address">Delivery Address</label>
  <textarea id="address" name="address" rows="3" required><?= htmlspecialchars($order['address']) ?></textarea>

  <label for="phone">Contact Phone Number</label>
  <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($order['phone']) ?>" required />

  <label for="status">Order Status</label>
  <select id="status" name="status" required>
    <option value="Pending" <?= $order['order_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
    <option value="Preparing" <?= $order['order_status'] === 'Preparing' ? 'selected' : '' ?>>Preparing</option>
    <option value="Ready" <?= $order['order_status'] === 'Ready' ? 'selected' : '' ?>>Ready</option>
    <option value="Served" <?= $order['order_status'] === 'Served' ? 'selected' : '' ?>>Served</option>
    <option value="Cancelled" <?= $order['order_status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
  </select>

  <button type="submit">Update Order</button>
</form>

<a href="order_management.php" class="back">Back to Order Management</a>

</body>
</html>
