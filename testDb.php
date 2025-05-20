<?php
require 'db.php';

// If we get here, $con is valid
echo '<p style="color:green">✅ Connected successfully to MySQL!</p>';

// Do a simple query to verify table access:
$result = mysqli_query($con, "SHOW TABLES");
if (!$result) {
    die('Query error: ' . mysqli_error($con));
}
echo '<p>Tables in database:</p><ul>';
while ($row = mysqli_fetch_row($result)) {
    echo '<li>' . htmlspecialchars($row[0]) . '</li>';
}
echo '</ul>';
