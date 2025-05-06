<?php
// filepath: c:\xampp\htdocs\webtech\Project\php\logout.php
session_start();
session_destroy();
header("Location: ../View/login.html");
exit();
?>