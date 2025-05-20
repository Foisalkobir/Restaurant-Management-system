<?php
// View/reservations.php - Table reservations dashboard
session_start();
// Only logged-in users
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Corrected Model paths
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/reservationModel.php';

$model = new ReservationModel($con);

// Determine selected date (default: today)
$selectedDate = $_GET['date'] ?? date('Y-m-d');

// Fetch all reservations for that date
$reservations = array_filter(
    $model->getAllReservations(),
    fn($r) => strpos($r['reservation_datetime'], $selectedDate) === 0
);

// Fetch available table slots for that date
$availableTables = $model->getAvailableSlots($selectedDate);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservations - Restaurant Management</title>
  <link rel="stylesheet" href="../Asset/css/style.css">
  <style>
    .reservation-form, .reservation-list {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    table { width:100%; border-collapse: collapse; }
    table th, table td { border:1px solid #ddd; padding:8px; }
    table th { background:#f4f4f4; }
    .btn { padding:6px 12px; }
    .btn-secondary { background:#6c757d; color:#fff; border:none; cursor:pointer; }
  </style>
</head>
<body>
  <?php include 'nav.php'; ?>

  <?php if (!empty($_SESSION['res_success'])): ?>
    <p style="color:green; margin:10px 20px;"><?= htmlspecialchars($_SESSION['res_success']) ?></p>
    <?php unset($_SESSION['res_success']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['res_error'])): ?>
    <p style="color:red; margin:10px 20px;"><?= htmlspecialchars($_SESSION['res_error']) ?></p>
    <?php unset($_SESSION['res_error']); ?>
  <?php endif; ?>

  <main class="container">
    <h1>Table Reservations</h1>

    <div class="reservation-form">
      <h2>New Reservation</h2>
      <form method="POST" action="../Controller/reservationActions.php">
        <input type="hidden" name="action" value="createReservation">
        <input type="hidden" name="date" value="<?= htmlspecialchars($selectedDate) ?>">

        <div class="form-group">
          <label for="date">Date:</label>
          <input
            type="date"
            id="date"
            name="date"
            value="<?= htmlspecialchars($selectedDate) ?>"
            required
            onchange="this.form.submit()"
          >
        </div>

        <div class="form-group">
          <label for="table_number">Table Number:</label>
          <select id="table_number" name="table_number" required>
            <option value="">-- Select Table --</option>
            <?php foreach ($availableTables as $table): ?>
              <option value="<?= $table ?>"><?= $table ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="time">Time:</label>
          <input type="time" id="time" name="time" required>
        </div>

        <div class="form-group">
          <label for="party_size">Party Size:</label>
          <input type="number" id="party_size" name="party_size" min="1" required>
        </div>

        <div class="form-group">
          <label for="customer_name">Name:</label>
          <input type="text" id="customer_name" name="customer_name" required>
        </div>

        <div class="form-group">
          <label for="customer_phone">Phone:</label>
          <input type="tel" id="customer_phone" name="customer_phone" required>
        </div>

        <div class="form-group">
          <label for="special_requests">Special Requests:</label>
          <textarea id="special_requests" name="special_requests" rows="2"></textarea>
        </div>

        <button type="submit" class="btn">Reserve</button>
      </form>
    </div>

    <div class="reservation-list">
      <h2>Reservations on <?= htmlspecialchars($selectedDate) ?></h2>
      <table>
        <thead>
          <tr>
            <th>Table</th>
            <th>Datetime</th>
            <th>Name</th>
            <th>Party</th>
            <th>Phone</th>
            <th>Requests</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($reservations)): ?>
            <tr><td colspan="7">No reservations found.</td></tr>
          <?php else: ?>
            <?php foreach ($reservations as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['table_number']) ?></td>
                <td><?= htmlspecialchars($r['reservation_datetime']) ?></td>
                <td><?= htmlspecialchars($r['customer_name']) ?></td>
                <td><?= htmlspecialchars($r['party_size']) ?></td>
                <td><?= htmlspecialchars($r['customer_phone']) ?></td>
                <td><?= htmlspecialchars($r['special_requests']) ?></td>
                <td>
                  <form
                    method="POST"
                    action="../Controller/reservationActions.php"
                    onsubmit="return confirm('Cancel this reservation?');"
                  >
                    <input type="hidden" name="action" value="deleteReservation">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <input type="hidden" name="date" value="<?= htmlspecialchars($selectedDate) ?>">
                    <button type="submit" class="btn btn-secondary">Cancel</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
