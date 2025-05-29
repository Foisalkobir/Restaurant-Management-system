<?php
session_start();
require_once('../model/menuModel.php');

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'user') {
    header('Location: login.html');
    exit();
}

$menuItems = getMenuItems();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $itemId = (int)$_POST['item_id'];
    $item = null;
    foreach ($menuItems as $menuItem) {
        if ($menuItem['id'] === $itemId) {
            $item = $menuItem;
            break;
        }
    }

    if ($item) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $cart = &$_SESSION['cart'];

        $found = false;
        foreach ($cart as &$cartItem) {
            if ($cartItem['id'] === $itemId) {
                $cartItem['quantity']++;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => 1,
            ];
        }
        header('Location: cart.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Menu - RestaurantPro</title>
<link rel="stylesheet" href="../asset/css/menu.css">
</head>
<body>

<header>Menu</header>

<nav>
  <a href="home.php">Dashboard</a>
  <a href="cart.php">Cart (<?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0 ?>)</a>
  <a href="profile.php?id=<?= htmlspecialchars($_SESSION['user']['id']) ?>">Profile</a>
  <a href="../controller/logout.php">Logout</a>
</nav>

<main>
  <?php if (empty($menuItems)): ?>
    <p>No menu items available right now.</p>
  <?php else: ?>
    <?php foreach ($menuItems as $item): ?>
      <div class="menu-item">
        <div>
          <h3><?= htmlspecialchars($item['name']) ?></h3>
          <p><?= htmlspecialchars($item['description']) ?></p>
          <p><strong>Price: $<?= number_format($item['price'], 2) ?></strong></p>
          <small>Category: <?= htmlspecialchars($item['category']) ?></small>
        </div>
        <form method="POST" style="margin:0;">
          <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
          <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</main>

</body>
</html>
