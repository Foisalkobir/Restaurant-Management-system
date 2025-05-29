<?php
if (!function_exists('getConnection')) {
    function getConnection() {
        $con = mysqli_connect('127.0.0.1', 'root', '', 'testing_project');
        if (!$con) {
            die("Database connection failed: " . mysqli_connect_error());
        }
        return $con;
    }
}
?>
