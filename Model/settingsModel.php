<?php
require_once('db.php');

function updateTimeFormat($username, $timeFormat) {
    $con = getConnection();
    $sql = "UPDATE system_settings SET time_format = ? WHERE username = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $timeFormat, $username);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    return $result;
}

function getTimeFormat($username) {
    $con = getConnection();
    $sql = "SELECT time_format FROM system_settings WHERE username = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    return $row ? $row['time_format'] : '24h';
}
?>