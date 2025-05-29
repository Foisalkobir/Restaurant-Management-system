<?php
require_once('db.php'); // your DB connection file with getConnection()

function getSiteInfo() {
    $con = getConnection();
    $sql = "SELECT * FROM site_info ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($con, $sql);
    $info = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $info;
}

function updateSiteInfo($about_us, $services, $why_choose_us, $email, $phone, $address) {
    $con = getConnection();
    $sql = "UPDATE site_info SET about_us=?, services=?, why_choose_us=?, contact_email=?, contact_phone=?, contact_address=? ORDER BY id DESC LIMIT 1";
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, "ssssss", $about_us, $services, $why_choose_us, $email, $phone, $address);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    return $success;
}
?>
