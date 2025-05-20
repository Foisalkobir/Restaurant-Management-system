<?php
// Model/staffModel.php – Data access for staff scheduling
require_once __DIR__ . '/db.php';

class StaffModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    /**
     * Fetch all employees
     * @return array [['id'=>int,'name'=>string],…]
     */
    public function getAllEmployees(): array {
        $sql = "SELECT id, name FROM employees ORDER BY name";
        $res = mysqli_query($this->con, $sql);
        $out = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = $row;
        }
        return $out;
    }

    /**
     * Fetch shifts between two dates (inclusive), keyed by "employeeId_date"
     * @param string $start 'YYYY-MM-DD'
     * @param string $end   'YYYY-MM-DD'
     * @return array ['{empId}_{date}'=> ['start_time'=>'HH:MM','end_time'=>'HH:MM'], …]
     */
    public function getShiftsForWeek(string $start, string $end): array {
        $sql = "SELECT employee_id, shift_date, start_time, end_time
                  FROM shifts
                 WHERE shift_date BETWEEN ? AND ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $start, $end);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $empId, $date, $startT, $endT);

        $map = [];
        while (mysqli_stmt_fetch($stmt)) {
            $map[$empId . '_' . $date] = [
                'start_time' => $startT,
                'end_time'   => $endT
            ];
        }
        mysqli_stmt_close($stmt);
        return $map;
    }

    /**
     * Fetch availability flags for a set of dates, keyed by "employeeId_date"
     * @param array $dates list of 'YYYY-MM-DD'
     * @return array ['{empId}_{date}'=> 0|1, …]
     */
    public function getAvailabilityForWeek(array $dates): array {
        if (empty($dates)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($dates), '?'));
        $sql = "SELECT employee_id, date, is_available
                  FROM availability
                 WHERE date IN ($placeholders)";
        $stmt = mysqli_prepare($this->con, $sql);
        $types = str_repeat('s', count($dates));
        mysqli_stmt_bind_param($stmt, $types, ...$dates);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $empId, $date, $avail);

        $map = [];
        while (mysqli_stmt_fetch($stmt)) {
            $map[$empId . '_' . $date] = (int)$avail;
        }
        mysqli_stmt_close($stmt);
        return $map;
    }

    /**
     * Fetch approved time-off within a date range, keyed by each day => true
     * @param array $dates list of 'YYYY-MM-DD' to check
     * @return array ['{empId}_{date}'=> true, …]
     */
    public function getTimeOffRequestsForWeek(array $dates): array {
        if (empty($dates)) {
            return [];
        }
        $weekStart = min($dates);
        $weekEnd   = max($dates);
        $sql = "SELECT employee_id, start_date, end_date
                  FROM time_off_requests
                 WHERE status = 'Approved'
                   AND NOT (end_date < ? OR start_date > ?)";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $weekStart, $weekEnd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $empId, $start, $end);

        $map = [];
        while (mysqli_stmt_fetch($stmt)) {
            $cur = new DateTime($start);
            $last = new DateTime($end);
            while ($cur <= $last) {
                $d = $cur->format('Y-m-d');
                if (in_array($d, $dates, true)) {
                    $map[$empId . '_' . $d] = true;
                }
                $cur->modify('+1 day');
            }
        }
        mysqli_stmt_close($stmt);
        return $map;
    }

    /**
     * Set availability for one employee on one date
     */
    public function setAvailability(int $empId, string $date, int $isAvailable): bool {
        $sql = "INSERT INTO availability (employee_id, date, is_available)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE is_available = VALUES(is_available)";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, 'isi', $empId, $date, $isAvailable);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    /**
     * Schedule a shift for an employee
     */
    public function createShift(int $empId, string $date, string $start, string $end): bool {
        $sql = "INSERT INTO shifts (employee_id, shift_date, start_time, end_time)
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, 'isss', $empId, $date, $start, $end);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }

    /**
     * Record a time-off request
     */
    public function requestTimeOff(int $empId, string $startDate, string $endDate): bool {
        $sql = "INSERT INTO time_off_requests (employee_id, start_date, end_date)
                VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, 'iss', $empId, $startDate, $endDate);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
}
?>
