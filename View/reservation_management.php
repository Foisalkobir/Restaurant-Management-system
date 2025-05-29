<?php
session_start();
require_once('../model/db.php');

if (!isset($_SESSION['status']) || $_SESSION['user']['account_type'] !== 'admin') {
    header('Location: ../view/login.html');
    exit();
}

$con = getConnection();
if (!$con) {
    die("DB connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $reservationId = (int)$_POST['reservation_id'];
        $newStatus = $_POST['new_status'];
        $stmt = $con->prepare("UPDATE reservations SET status = ? WHERE reservation_id = ?");
        $stmt->bind_param("si", $newStatus, $reservationId);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_reservation'])) {
        $reservationId = (int)$_POST['reservation_id'];
        $stmt = $con->prepare("DELETE FROM reservations WHERE reservation_id = ?");
        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['add_reservation'])) {
        $user_id = (int)$_POST['user_id'];
        $date = $_POST['reservation_date'];
        $time = $_POST['reservation_time'];
        $guests = (int)$_POST['guests'];
        $special_requests = trim($_POST['special_requests']);
        $status = 'Pending';

        $stmt = $con->prepare("INSERT INTO reservations (user_id, reservation_date, reservation_time, guests, special_requests, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ississ", $user_id, $date, $time, $guests, $special_requests, $status);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT r.reservation_id, r.reservation_date, r.reservation_time, r.guests, r.special_requests, r.status, u.username, u.email 
        FROM reservations r 
        JOIN users u ON r.user_id = u.id
        ORDER BY r.reservation_date DESC, r.reservation_time DESC";
$result = $con->query($sql);
$reservations = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
} else {
    die("Query error: " . $con->error);
}

$userRes = $con->query("SELECT id, username FROM users ORDER BY username ASC");
$users = [];
if ($userRes) {
    while ($u = $userRes->fetch_assoc()) {
        $users[] = $u;
    }
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Reservation Management - Admin | RestaurantPro</title>
<link rel="stylesheet" href="../asset/css/reservation_management.css" />
</head>
<body>

<nav>
  <a href="home.php">Dashboard</a>
  <a href="../controller/logout.php">Logout</a>
</nav>

<div class="container">
  <h1>Reservation Management</h1>

  <button class="add-reservation-btn" id="openModalBtn">+ Add Reservation</button>

  <?php if (empty($reservations)): ?>
    <p style="text-align:center; font-size:1.2rem; color:#ddd;">No reservations found.</p>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Email</th>
        <th>Date</th>
        <th>Time</th>
        <th>Guests</th>
        <th>Special Requests</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($reservations as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['reservation_id']) ?></td>
        <td><?= htmlspecialchars($r['username']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><?= htmlspecialchars($r['reservation_date']) ?></td>
        <td><?= htmlspecialchars(substr($r['reservation_time'], 0, 5)) ?></td>
        <td><?= htmlspecialchars($r['guests']) ?></td>
        <td><?= nl2br(htmlspecialchars($r['special_requests'])) ?></td>
        <td>
          <form method="POST" style="display:inline-block;">
            <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
            <select name="new_status" class="status-select" onchange="this.form.submit()">
              <option value="Pending" <?= $r['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="Confirmed" <?= $r['status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
              <option value="Cancelled" <?= $r['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
            <input type="hidden" name="update_status" value="1" />
          </form>
        </td>
        <td>
          <form method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this reservation?');">
            <input type="hidden" name="reservation_id" value="<?= $r['reservation_id'] ?>">
            <button type="submit" name="delete_reservation" class="delete-btn">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<div class="modal" id="addReservationModal">
  <div class="modal-content">
    <span class="close-btn" id="closeModalBtn">&times;</span>
    <div class="modal-header">Add New Reservation</div>
    <form method="POST" class="add-reservation-form">
      <input type="hidden" name="add_reservation" value="1" />
      <label for="user_id">User</label>
      <select id="user_id" name="user_id" required>
        <option value="">-- Select User --</option>
        <?php foreach ($users as $user): ?>
          <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="reservation_date">Date</label>
      <input type="date" id="reservation_date" name="reservation_date" required />

      <label for="reservation_time">Time</label>
      <input type="time" id="reservation_time" name="reservation_time" required />

      <label for="guests">Number of Guests</label>
      <input type="number" id="guests" name="guests" min="1" max="50" value="1" required />

      <label for="special_requests">Special Requests</label>
      <textarea id="special_requests" name="special_requests" rows="3" placeholder="Any special requests?"></textarea>

      <button type="submit">Add Reservation</button>
    </form>
  </div>
</div>

<script>
const openModalBtn = document.getElementById('openModalBtn');
const modal = document.getElementById('addReservationModal');
const closeModalBtn = document.getElementById('closeModalBtn');

openModalBtn.addEventListener('click', () => {
  modal.style.display = 'block';
});
closeModalBtn.addEventListener('click', () => {
  modal.style.display = 'none';
});
window.addEventListener('click', (e) => {
  if (e.target === modal) {
    modal.style.display = 'none';
  }
});
</script>

</body>
</html>
