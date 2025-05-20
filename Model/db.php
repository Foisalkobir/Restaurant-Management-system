<?php
// Connect to MySQL
$con = mysqli_connect('127.0.0.1', 'root', '', 'webtechproject');

// Check connection
if (!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

?>
