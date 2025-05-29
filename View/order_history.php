<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../model/orderModel.php');  // Your DB access functions

if (!isset($_SESSION['status']) || !isset($_SESSION['user']['id'])) {
    header('Location: login.html');
    exit();
}

$userId = (int)$_SESSION['user']['id'];

$orders = getUserOrders($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Your Order History - RestaurantPro</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #fafafa;
    margin: 0;
    padding: 2rem;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
  }
  h1 {
    text-align: center;
    margin-bottom: 2rem;
    color: #667eea;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  th, td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: center;
  }
  th {
    background-color: #667eea;
    color: white;
  }
  tr:nth-child(even) {
    background-color: #f4f7fb;
  }
  .no-orders {
    text-align: center;
    padding: 2rem;
    font-size: 1.2rem;
    color: #555;
  }
  a {
    display: inline-block;
    margin-top: 20px;
    color: #667eea;
    text-decoration: none;
  }
  a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>

<h1>Your Order History</h1>

<?php if (empty($orders)): ?>
    <p class="no-orders">You have no previous orders.</p>
<?php else: ?>
<table>
  <thead>
    <tr>
      <th>Order ID</th>
      <th>Date</th>
      <th>Status</th>
      <th>Total Amount</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($orders as $order): ?>
    <tr>
      <td><?= htmlspecialchars($order['order_id']) ?></td>
      <td><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($order['order_date']))) ?></td>
      <td><?= htmlspecialchars(ucfirst($order['status'])) ?></td>
      <td>$<?= number_format($order['total_amount'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<a href="home.php">Back to Dashboard</a>

</body>
</html>
