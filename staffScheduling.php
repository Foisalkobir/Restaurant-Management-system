<?php
// staffScheduling.php – Weekly roster, availability, and time-off board (upgraded)
session_start();
require_once 'db.php';
require_once 'staffModel.php';

// Only managers/admins
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'manager') {
    header('Location: home.php');
    exit;
}

// Preserve “today” for form defaults
$currentDate = new DateTime();

// Compute start/end of the week we're viewing
$viewDate   = clone $currentDate;
if (isset($_GET['week_start'])) {
    // Accept YYYY-MM-DD as override
    $viewDate = DateTime::createFromFormat('Y-m-d', $_GET['week_start']) ?: $viewDate;
}
$dayOfWeek  = (int)$viewDate->format('N'); // 1 (Mon)–7 (Sun)
$monday     = clone $viewDate;
$monday->modify('-' . ($dayOfWeek - 1) . ' days');
$weekStart  = $monday->format('Y-m-d');

$weekDates  = [];
for ($i = 0; $i < 7; $i++) {
    $d                = (clone $monday)->modify("+{$i} days");
    $weekDates[$i]    = $d->format('Y-m-d');
}
$weekEnd = end($weekDates);

// Load data
$model        = new StaffModel($con);
$employees    = $model->getAllEmployees();
$shifts       = $model->getShiftsForWeek($weekStart, $weekEnd);
$availability = $model->getAvailabilityForWeek($weekDates);
$timeOff      = $model->getTimeOffRequestsForWeek($weekDates);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Staff Scheduling – Restaurant Management</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .roster-table { width: 100%; border-collapse: collapse; margin-bottom:2rem; }
    .roster-table th, .roster-table td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    .available   { background: #e0ffe0; }
    .unavailable { background: #ffe0e0; }
    .timeoff     { background: #fff0b3; }
    .nav-weeks   { margin-bottom:1rem; }
    .nav-weeks a { margin-right:1rem; text-decoration:none; }
  </style>
</head>
<body>
  <?php include 'nav.php'; ?>

  <main class="container">
    <h1>Staff Scheduling</h1>

    <!-- Feedback messages -->
    <?php
      if (!empty($_SESSION['staff_success'])) {
          echo '<p class="success">'.htmlspecialchars($_SESSION['staff_success']).'</p>';
          unset($_SESSION['staff_success']);
      }
      if (!empty($_SESSION['staff_error'])) {
          echo '<p class="error">'.htmlspecialchars($_SESSION['staff_error']).'</p>';
          unset($_SESSION['staff_error']);
      }
    ?>

    <!-- Week navigation -->
    <div class="nav-weeks">
      <?php
        $prev = (clone $monday)->modify('-7 days')->format('Y-m-d');
        $next = (clone $monday)->modify('+7 days')->format('Y-m-d');
      ?>
      <a href="?week_start=<?= $prev ?>">&larr; Previous Week</a>
      <strong><?= (new DateTime($weekStart))->format('M j, Y') ?> – <?= (new DateTime($weekEnd))->format('M j, Y') ?></strong>
      <a href="?week_start=<?= $next ?>">Next Week &rarr;</a>
    </div>

    <!-- Weekly Roster Grid -->
    <section>
      <h2>Weekly Roster</h2>
      <table class="roster-table">
        <thead>
          <tr>
            <th>Employee</th>
            <?php foreach ($weekDates as $date): ?>
              <th><?= (new DateTime($date))->format('D<br>m/d') ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($employees as $emp): ?>
            <tr>
              <td><?= htmlspecialchars($emp['name']) ?></td>
              <?php foreach ($weekDates as $date):
                  $key   = $emp['id'].'_'.$date;
                  $shift = $shifts[$key]    ?? null;
                  $avail = $availability[$key] ?? 1;
                  $off   = $timeOff[$key]   ?? false;
                  $cellClass = $off ? 'timeoff' : ($shift ? '' : ($avail ? 'available' : 'unavailable'));
              ?>
                <td class="<?= $cellClass ?>">
                  <?php if ($off): ?>
                    Time-Off
                  <?php elseif ($shift): ?>
                    <?= htmlspecialchars($shift['start_time']) ?>–<?= htmlspecialchars($shift['end_time']) ?>
                  <?php else: ?>
                    <?= $avail ? 'Avail' : 'Busy' ?>
                  <?php endif; ?>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Availability Tracker -->
    <section>
      <h2>Set Availability</h2>
      <form method="POST" action="staffActions.php" class="form-inline">
        <input type="hidden" name="action" value="setAvailability">
        <select name="employee_id" required>
          <?php foreach ($employees as $emp): ?>
            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="date" name="date" value="<?= $currentDate->format('Y-m-d') ?>" required>
        <label><input type="checkbox" name="is_available" value="1" checked> Available</label>
        <button type="submit" class="btn">Save</button>
      </form>
    </section>

    <!-- Shift Assignment -->
    <section>
      <h2>Assign Shift</h2>
      <form method="POST" action="staffActions.php" class="form-inline">
        <input type="hidden" name="action" value="createShift">
        <select name="employee_id" required>
          <?php foreach ($employees as $emp): ?>
            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="date" name="shift_date" value="<?= $currentDate->format('Y-m-d') ?>" required>
        <input type="time" name="start_time" required>
        <input type="time" name="end_time" required>
        <button type="submit" class="btn">Assign</button>
      </form>
    </section>

    <!-- Time-Off Requests -->
    <section>
      <h2>Request Time-Off</h2>
      <form method="POST" action="staffActions.php" class="form-inline">
        <input type="hidden" name="action" value="requestTimeOff">
        <select name="employee_id" required>
          <?php foreach ($employees as $emp): ?>
            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="date" name="start_date" required>
        <input type="date" name="end_date" required>
        <button type="submit" class="btn">Request</button>
      </form>
    </section>
  </main>
</body>
</html>
