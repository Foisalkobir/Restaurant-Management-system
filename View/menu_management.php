<?php
session_start();
require_once('../model/db.php'); 

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: login.html');
    exit();
}

$con = getConnection();

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = mysqli_prepare($con, "DELETE FROM menu_items WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: menu_management.php");
    exit();
}

$sql = "SELECT mi.id, mi.name, mi.price, mi.description, c.name AS category 
        FROM menu_items mi
        LEFT JOIN categories c ON mi.category_id = c.id
        ORDER BY mi.id DESC";
$result = mysqli_query($con, $sql);

$menuItems = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $menuItems[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Menu Management - Admin</title>
<link rel="stylesheet" href="../asset/css/landingpage.css" />
<style>
  main {
    max-width: 900px;
    margin: 100px auto 50px;
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }
  h1 {
    margin-bottom: 1.5rem;
    color: #ff6b6b;
  }
  #searchInput {
    width: 100%;
    padding: 10px 15px;
    font-size: 1rem;
    margin-bottom: 20px;
    border: 2px solid #764ba2;
    border-radius: 8px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid #ddd;
    padding: 12px 15px;
    text-align: left;
  }
  th {
    background: #667eea;
    color: white;
  }
  tr:nth-child(even) {
    background: #f9f9f9;
  }
  a.button {
    display: inline-block;
    padding: 8px 15px;
    margin-right: 10px;
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
    color: white;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s ease;
  }
  a.button:hover {
    background: linear-gradient(45deg, #4ecdc4, #ff6b6b);
  }
  .actions a {
    margin-right: 10px;
    color: #ff6b6b;
    font-weight: bold;
    cursor: pointer;
  }
</style>
<script>
function filterMenu() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const table = document.getElementById('menuTable');
    const trs = table.getElementsByTagName('tr');

    for (let i = 1; i < trs.length; i++) { // Skip header row
        const tr = trs[i];
        const name = tr.cells[1].textContent.toLowerCase();
        const category = tr.cells[2].textContent.toLowerCase();
        const description = tr.cells[4].textContent.toLowerCase();

        if (name.includes(input) || category.includes(input) || description.includes(input)) {
            tr.style.display = '';
        } else {
            tr.style.display = 'none';
        }
    }
}
</script>
</head>
<body>

<main>
  <h1>Menu Management</h1>

  <input type="text" id="searchInput" onkeyup="filterMenu()" placeholder="Search menu items by name, category, or description...">

  <a href="add_menu_item.php" class="button">Add New Item</a>

  <table id="menuTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Price ($)</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($menuItems) === 0): ?>
      <tr><td colspan="6" style="text-align:center;">No menu items found.</td></tr>
      <?php else: ?>
        <?php foreach ($menuItems as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['id']) ?></td>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td><?= htmlspecialchars($item['category']) ?></td>
          <td><?= number_format($item['price'], 2) ?></td>
          <td><?= htmlspecialchars($item['description']) ?></td>
          <td class="actions">
            <a href="edit_menu_item.php?id=<?= $item['id'] ?>">Edit</a>
            <a href="menu_management.php?delete_id=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="home.php" class="button" style="margin-top: 20px; display:inline-block;">Back to Dashboard</a>
</main>

</body>
</html>
