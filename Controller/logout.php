<?php
session_start();

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    $_SESSION = [];
    session_destroy();

    setcookie('status', '', time() - 3600, '/');

    header('Location: ../view/landingpage.html');
    exit;
}
?>

<script>
if (confirm("Are you sure you want to log out?")) {
    window.location.href = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?confirm=yes";
} else {
    window.history.back();
}
</script>
