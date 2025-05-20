<?php
// Controller/staffActions.php – Handle availability, shifts, and time-off requests
session_start();
require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/staffModel.php';

// Only managers can perform these actions
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'manager') {
    header('Location: ../View/home.php');
    exit;
}

$model  = new StaffModel($con);
$action = $_POST['action'] ?? '';

switch ($action) {

    case 'setAvailability':
        $empId  = (int)($_POST['employee_id'] ?? 0);
        $date   = $_POST['date'] ?? '';
        $avail  = isset($_POST['is_available']) ? 1 : 0;
        if ($model->setAvailability($empId, $date, $avail)) {
            $_SESSION['staff_success'] = 'Availability updated.';
        } else {
            $_SESSION['staff_error']   = 'Failed to update availability.';
        }
        break;

    case 'createShift':
        $empId = (int)($_POST['employee_id'] ?? 0);
        $date  = $_POST['shift_date'] ?? '';
        $start = $_POST['start_time'] ?? '';
        $end   = $_POST['end_time'] ?? '';
        if ($model->createShift($empId, $date, $start, $end)) {
            $_SESSION['staff_success'] = 'Shift assigned.';
        } else {
            $_SESSION['staff_error']   = 'Failed to assign shift.';
        }
        break;

    case 'requestTimeOff':
        $empId     = (int)($_POST['employee_id'] ?? 0);
        $startDate = $_POST['start_date'] ?? '';
        $endDate   = $_POST['end_date'] ?? '';
        if ($model->requestTimeOff($empId, $startDate, $endDate)) {
            $_SESSION['staff_success'] = 'Time-off requested.';
        } else {
            $_SESSION['staff_error']   = 'Failed to request time-off.';
        }
        break;

    default:
        $_SESSION['staff_error'] = 'Unknown action.';
        break;
}

// Redirect back to the scheduling page
header('Location: ../View/staffScheduling.php');
exit;
