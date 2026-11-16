<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.html");
    exit();
}

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$errors = [];

if ($fullname == '' || $email == '' || $contact == '' || $password == '' || $confirm_password == '') {
    $errors[] = "Please fill in every field.";
} else {
    if (!preg_match("/^[A-Za-z .'-]+$/", $fullname) || strlen($fullname) > 100) {
        $errors[] = "Full name can only have letters, spaces, dots, apostrophes and hyphens.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $errors[] = "Please enter a valid email address.";
    }
    if (!preg_match("/^09[0-9]{9}$/", $contact)) {
        $errors[] = "Contact number must be 11 digits and start with 09.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if ($password != $confirm_password) {
        $errors[] = "Password and Confirm Password do not match.";
    }
}

if (count($errors) == 0) {
    $stmt = mysqli_prepare($db, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "That email is already registered. Please log in instead.";
    }
    mysqli_stmt_close($stmt);
}

if (count($errors) > 0) {
    $title = "Sign Up Failed";
    $messages = $errors;
    $is_error = true;
    $back_url = "register.html";
    $back_text = "Go Back";
    include("message.php");
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($db, "INSERT INTO users (fullname, email, contact, password) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $fullname, $email, $contact, $hashed);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$title = "Account Created";
$messages = ["Your account is ready. You can log in now."];
$is_error = false;
$back_url = "login.php";
$back_text = "Go to Log In";
include("message.php");
?>
