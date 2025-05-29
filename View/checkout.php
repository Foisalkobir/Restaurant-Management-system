<?php
session_start();
require_once('../model/db.php');

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'user') {
    header('Location: login.html');
    exit();
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header('Location: menu.php');
    exit();
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$orderPlaced = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (!$payment_method || !$address || !$phone) {
        $errorMsg = "Please fill all required fields.";
    } else {
        $conn = getConnection();
        mysqli_begin_transaction($conn);

        try {
            $userId = $_SESSION['user']['id'];

            $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, order_date, payment_method, address, phone, total_amount, status) VALUES (?, NOW(), ?, ?, ?, ?, 'Pending')");
            mysqli_stmt_bind_param($stmt, "isssd", $userId, $payment_method, $address, $phone, $total);
            mysqli_stmt_execute($stmt);

            $orderId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            $stmtItem = mysqli_prepare($conn, "INSERT INTO order_items (order_id, menu_item_id, name, price, quantity) VALUES (?, ?, ?, ?, ?)");

            foreach ($cart as $item) {
                mysqli_stmt_bind_param($stmtItem, "iissd", $orderId, $item['id'], $item['name'], $item['price'], $item['quantity']);
                mysqli_stmt_execute($stmtItem);
            }
            mysqli_stmt_close($stmtItem);

            mysqli_commit($conn);
            mysqli_close($conn);

            $orderPlaced = true;
            unset($_SESSION['cart']); // clear cart after order

        } catch (Exception $e) {
            mysqli_rollback($conn);
            $errorMsg = "Order failed: " . $e->getMessage();
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Checkout - RestaurantPro</title>
<style>
body { font-family: Arial, sans-serif; background: #fafafa; margin: 0; padding: 2rem; max-width: 800px; margin: auto; }
h1 { color: #ff6b6b; }
form { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
label { display: block; margin-top: 1rem; font-weight: bold; }
input[type="text"], textarea, select {
    width: 100%; padding: 10px; margin-top: 5px; border: 2px solid #764ba2; border-radius: 5px; font-size: 1rem;
    box-sizing: border-box;
}
textarea { resize: vertical; }
.error { color: #e74c3c; margin-top: 10px; }
.success { color: #27ae60; font-weight: bold; font-size: 1.2rem; margin-bottom: 1rem; }
button {
    margin-top: 2rem; background: linear-gradient(45deg, #ff6b6b, #764ba2);
    color: white; padding: 12px 20px; border: none; border-radius: 30px; font-size: 1.1rem; cursor: pointer;
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.6);
    transition: all 0.3s ease;
}
button:hover {
    box-shadow: 0 8px 20px rgba(255, 107, 107, 0.85);
    transform: translateY(-3px);
}
.order-summary {
    margin-top: 2rem;
    background: #fff;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.order-summary h2 {
    margin-bottom: 1rem;
    color: #764ba2;
}
.order-summary ul {
    list-style-type: none;
    padding-left: 0;
}
.order-summary li {
    margin-bottom: 0.5rem;
}
</style>
<script>
function validateForm() {
  const payment = document.getElementById('payment_method').value;
  const address = document.getElementById('address').value.trim();
  const phone = document.getElementById('phone').value.trim();

  if (!payment || !address || !phone) {
    alert("Please fill all required fields.");
    return false;
  }

  if (!/^\d{10,15}$/.test(phone)) {
    alert("Please enter a valid phone number (digits only).");
    return false;
  }

  return true;
}
</script>
</head>
<body>

<h1>Checkout</h1>

<?php if ($orderPlaced): ?>
  <p class="success">Thank you! Your order has been placed successfully.</p>
  <p><a href="menu.php">Continue Shopping</a></p>
<?php else: ?>

  <?php if ($errorMsg): ?>
    <p class="error"><?= htmlspecialchars($errorMsg) ?></p>
  <?php endif; ?>

  <form method="POST" onsubmit="return validateForm();">
    <label for="payment_method">Payment Method *</label>
    <select id="payment_method" name="payment_method" required>
      <option value="">Select a payment method</option>
      <option value="cash">Cash on Delivery</option>
      <option value="card">Credit/Debit Card</option>
      <option value="mobile">Mobile Payment (Bkash, etc.)</option>
    </select>

    <label for="address">Delivery Address *</label>
    <textarea id="address" name="address" rows="3" placeholder="Enter your delivery address..." required></textarea>

    <label for="phone">Contact Phone Number *</label>
    <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required />

    <div class="order-summary">
      <h2>Your Order</h2>
      <ul>
        <?php foreach ($cart as $item): ?>
          <li><?= htmlspecialchars($item['quantity']) ?> × <?= htmlspecialchars($item['name']) ?> — $<?= number_format($item['price'], 2) ?></li>
        <?php endforeach; ?>
      </ul>
      <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
    </div>

    <button type="submit">Place Order</button>
  </form>

<?php endif; ?>

<p><a href="cart.php">Back to Cart</a></p>

</body>
</html>
