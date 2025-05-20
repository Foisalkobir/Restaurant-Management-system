<?php
session_start();
// Only logged-in users
if (!isset($_SESSION['user_id'])) {
    header('Location: ../View/login.php');
    exit;
}

require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/reservationModel.php';
$model = new ReservationModel($con);

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'createReservation':
        // Gather inputs
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $datetime = trim("$date $time");
        $data = [
            'table_number'        => intval($_POST['table_number'] ?? 0),
            'reservation_datetime'=> $datetime,
            'customer_name'       => trim($_POST['customer_name'] ?? ''),
            'customer_phone'      => trim($_POST['customer_phone'] ?? ''),
            'party_size'          => intval($_POST['party_size'] ?? 1),
            'special_requests'    => trim($_POST['special_requests'] ?? ''),
        ];
        // Basic server-side validation
        if ($data['table_number'] <= 0 || empty($date) || empty($time)) {
            $_SESSION['res_error'] = 'Table, date, and time are required.';
        } else {
            if ($model->createReservation($data)) {
                $_SESSION['res_success'] = 'Reservation created!';
            } else {
                $_SESSION['res_error'] = 'Failed to create reservation.';
            }
        }
        break;

    case 'deleteReservation':
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0 && $model->deleteReservation($id)) {
            $_SESSION['res_success'] = 'Reservation cancelled.';
        } else {
            $_SESSION['res_error'] = 'Failed to cancel reservation.';
        }
        break;

    default:
        $_SESSION['res_error'] = 'Unknown action.';
        break;
}

// Redirect back to the reservations view, preserving date filter
$dateParam = urlencode($_POST['date'] ?? date('Y-m-d'));
header("Location: ../View/reservations.php?date={$dateParam}");
exit;
