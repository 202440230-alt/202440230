<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    die();
}
$login_session = $_SESSION['fullname'];
?>
