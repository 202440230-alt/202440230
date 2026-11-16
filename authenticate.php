<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    $title = "Log In Failed";
    $messages = ["Please enter your email and password."];
    $is_error = true;
    $back_url = "login.php";
    $back_text = "Go Back";
    include("message.php");
    exit();
}

$stmt = mysqli_prepare($db, "SELECT id, fullname, email, contact, password, role, created_at FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['contact'] = $user['contact'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['created_at'] = $user['created_at'];
    if (isset($_POST['remember'])) {
        setcookie('remember_email', $user['email'], time() + 60 * 60 * 24 * 30, "/");
    } else {
        setcookie('remember_email', '', time() - 3600, "/");
    }
    if ($user['role'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: home.php");
    }
    exit();
}

$title = "Log In Failed";
$messages = ["Your email or password is invalid."];
$is_error = true;
$back_url = "login.php";
$back_text = "Try Again";
include("message.php");
?>
