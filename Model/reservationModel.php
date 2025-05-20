<?php
require_once __DIR__ . '/db.php';

class ReservationModel {
    private $con;

    public function __construct($connection) {
        $this->con = $connection;
    }

    public function getAllReservations(): array {
        $sql = "SELECT id, table_number, reservation_datetime, customer_name, customer_phone, party_size, special_requests
                FROM reservations
                ORDER BY reservation_datetime";
        $result = mysqli_query($this->con, $sql);
        $reservations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reservations[] = $row;
        }
        return $reservations;
    }

    public function getAvailableSlots(string $date): array {
        $slots = [];
        for ($table = 1; $table <= 10; $table++) {
            $stmt = mysqli_prepare($this->con,
                "SELECT COUNT(*) FROM reservations
                 WHERE table_number = ?
                   AND DATE(reservation_datetime) = ?"
            );
            mysqli_stmt_bind_param($stmt, 'is', $table, $date);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $count);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            if ($count === 0) {
                $slots[] = $table;
            }
        }
        return $slots;
    }

    public function createReservation(array $data): bool {
        $stmt = mysqli_prepare(
            $this->con,
            "INSERT INTO reservations
             (table_number, reservation_datetime, customer_name, customer_phone, party_size, special_requests)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'isssis',
            $data['table_number'],
            $data['reservation_datetime'],
            $data['customer_name'],
            $data['customer_phone'],
            $data['party_size'],
            $data['special_requests']
        );
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function deleteReservation(int $id): bool {
        $stmt = mysqli_prepare($this->con, "DELETE FROM reservations WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

}
?>
