<?php
session_start();
require_once('../model/db.php');


if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

$con = getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid menu item ID.");
}

$categories = [];
$catRes = mysqli_query($con, "SELECT id, name FROM categories ORDER BY name ASC");
while ($cat = mysqli_fetch_assoc($catRes)) {
    $categories[] = $cat;
}

$stmt = mysqli_prepare($con, "SELECT id, category_id, name, description, price FROM menu_items WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$menuItem = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$menuItem) {
    die("Menu item not found.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    if ($name === '' || $category_id <= 0 || $price <= 0) {
        $error = "Please fill in all required fields with valid data.";
    } else {
        $updateStmt = mysqli_prepare($con, "UPDATE menu_items SET category_id = ?, name = ?, description = ?, price = ? WHERE id = ?");
        mysqli_stmt_bind_param($updateStmt, "issdi", $category_id, $name, $description, $price, $id);
        if (mysqli_stmt_execute($updateStmt)) {
            $success = "Menu item updated successfully.";
            // Refresh menu item details after update
            $menuItem['name'] = $name;
            $menuItem['category_id'] = $category_id;
            $menuItem['description'] = $description;
            $menuItem['price'] = $price;
        } else {
            $error = "Failed to update menu item.";
        }
        mysqli_stmt_close($updateStmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Menu Item - Admin</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<link rel="stylesheet" href="../asset/css/edit-menu-item.css" />
</head>
<body>

<main>
  <h1>Edit Menu Item</h1>

  <?php if ($error): ?>
    <p class="message error"><?= htmlspecialchars($error) ?></p>
  <?php elseif ($success): ?>
    <p class="message success"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="category_id">Category *</label>
    <select id="category_id" name="category_id" required>
      <option value="">-- Select Category --</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $menuItem['category_id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="name">Item Name *</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($menuItem['name']) ?>" required />

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="3"><?= htmlspecialchars($menuItem['description']) ?></textarea>

    <label for="price">Price ($) *</label>
    <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($menuItem['price']) ?>" required />

    <button type="submit">Update Item</button>
  </form>

  <a href="menu_management.php" class="back">← Back to Menu Management</a>
</main>

</body>
</html>
