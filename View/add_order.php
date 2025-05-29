<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

require_once('../model/orderModel.php');
require_once('../model/userModel.php');

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $total_amount = filter_input(INPUT_POST, 'total_amount', FILTER_VALIDATE_FLOAT);
    $order_status = filter_input(INPUT_POST, 'order_status', FILTER_SANITIZE_STRING) ?? 'Pending';

    if (!$user_id || !$payment_method || !$address || !$phone || !$total_amount) {
        $errorMsg = "Please fill all required fields correctly.";
    } else {
        $orderId = addOrder($user_id, $payment_method, $address, $phone, $total_amount, $order_status);
        if ($orderId) {
            $successMsg = "Order added successfully. <a href='order_management.php'>Go back</a>";
        } else {
            $errorMsg = "Failed to add order.";
        }
    }
}

$users = getAllUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add New Order</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<link rel="stylesheet" href="../asset/css/add-order.css" />
</head>
<body>

<h1>Add New Order</h1>

<?php if ($errorMsg): ?>
  <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
<?php endif; ?>
<?php if ($successMsg): ?>
  <div class="success"><?= $successMsg ?></div>
<?php endif; ?>

<form method="POST" novalidate>
  <label for="user_id">
    User:
    <select name="user_id" id="user_id" required>
      <option value="">Select User</option>
      <?php foreach ($users as $user): ?>
        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>

  <label for="payment_method">
    Payment Method:
    <input type="text" name="payment_method" id="payment_method" required />
  </label>

  <label for="address">
    Address:
    <textarea name="address" id="address" rows="3" required></textarea>
  </label>

  <label for="phone">
    Phone:
    <input type="text" name="phone" id="phone" required />
  </label>

  <label for="total_amount">
    Total Amount:
    <input type="number" name="total_amount" id="total_amount" step="0.01" required />
  </label>

  <label for="order_status">
    Status:
    <select name="order_status" id="order_status">
      <option value="Pending">Pending</option>
      <option value="Preparing">Preparing</option>
      <option value="Ready">Ready</option>
      <option value="Served">Served</option>
      <option value="Cancelled">Cancelled</option>
    </select>
  </label>

  <button type="submit">Add Order</button>
</form>

<p><a href="order_management.php">Back to Order Management</a></p>

</body>
</html>
