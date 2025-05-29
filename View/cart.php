<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'user') {
    header('Location: login.html');
    exit();
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Your Cart - RestaurantPro</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #fafafa;
    margin: 0;
    padding: 2rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
}
th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
.total {
    font-weight: bold;
    font-size: 1.2rem;
}
button {
    padding: 10px 20px;
    background: #ff6b6b;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
button:hover {
    background: #e55;
}
</style>
</head>
<body>

<h1>Your Cart</h1>

<?php if (empty($cart)): ?>
    <p>Your cart is empty. <a href="menu.php">Go to Menu</a></p>
<?php else: ?>

<table>
  <thead>
    <tr><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>
  </thead>
  <tbody>
    <?php foreach ($cart as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td>$<?= number_format($item['price'], 2) ?></td>
      <td><?= (int)$item['quantity'] ?></td>
      <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p class="total">Total: $<?= number_format($total, 2) ?></p>

<form method="POST" action="checkout.php">
  <button type="submit">Proceed to Checkout</button>
</form>

<p><a href="menu.php">Back to Menu</a></p>

<?php endif; ?>

</body>
</html>
