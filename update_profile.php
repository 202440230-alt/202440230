<?php
include("session.php");
include("config.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: profile.php");
    exit();
}

$fullname = trim($_POST['pname'] ?? '');
$email = trim($_POST['pemail'] ?? '');
$contact = trim($_POST['pcontact'] ?? '');

$errors = [];
if ($fullname == '' || $email == '' || $contact == '') {
    $errors[] = "Please fill in all fields.";
} else {
    if (!preg_match("/^[A-Za-z .'-]+$/", $fullname)) {
        $errors[] = "Name can only have letters, spaces, periods, apostrophes and hyphens.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (!preg_match("/^09[0-9]{9}$/", $contact)) {
        $errors[] = "Contact number must be 11 digits starting with 09.";
    }
}

if (count($errors) == 0) {
    $stmt = mysqli_prepare($db, "SELECT id FROM users WHERE email = ? AND id != ?");
    mysqli_stmt_bind_param($stmt, "si", $email, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $taken = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if ($taken) {
        $errors[] = "That email is already used by another account.";
    }
}

if (count($errors) > 0) {
    $title = "Update Failed";
    $messages = $errors;
    $is_error = true;
    $back_url = "profile.php";
    $back_text = "Go Back";
    include("message.php");
    exit();
}

$stmt = mysqli_prepare($db, "UPDATE users SET fullname = ?, email = ?, contact = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "sssi", $fullname, $email, $contact, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$_SESSION['fullname'] = $fullname;
$_SESSION['email'] = $email;
$_SESSION['contact'] = $contact;

$title = "Details Updated";
$messages = ["Your details were saved."];
$is_error = false;
$back_url = "profile.php";
$back_text = "Back to Profile";
include("message.php");
?>
