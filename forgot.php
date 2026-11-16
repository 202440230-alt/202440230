<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("config.php");

    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $errors = [];
    if ($email == '' || $contact == '' || $password == '' || $confirm == '') {
        $errors[] = "Please fill in all fields.";
    } else {
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }
        if ($password != $confirm) {
            $errors[] = "The two passwords do not match.";
        }
    }

    if (count($errors) == 0) {
        $stmt = mysqli_prepare($db, "SELECT id FROM users WHERE email = ? AND contact = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email, $contact);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if (!$user) {
            $errors[] = "We could not find an account with that email and contact number.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($db, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $hash, $user['id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if (count($errors) > 0) {
        $title = "Reset Failed";
        $messages = $errors;
        $is_error = true;
        $back_url = "forgot.php";
        $back_text = "Try Again";
    } else {
        $title = "Password Changed";
        $messages = ["Your password was changed. You can log in with it now."];
        $is_error = false;
        $back_url = "login.php";
        $back_text = "Go to Log In";
    }
    include("message.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SaveSpace - Forgot Password</title>
<link rel="icon" type="image/svg+xml" href="images/savespace-logo.svg">
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
<div class="header-inner">
<div class="logo-block">
<img class="logo-img" src="images/savespace-logo.svg" alt="SaveSpace logo">
<div>
<h1><a href="index.php"><span class="save">Save</span><span class="space">Space</span></a></h1>
<p class="tagline">Your Self-Storage Facility</p>
</div>
</div>
<button class="hamburger" onclick="document.getElementById('main-nav').classList.toggle('nav-open')">&#9776;</button>
<nav id="main-nav">
<a href="index.php">Home</a>
<a href="login.php">Log In</a>
<a href="register.html">Sign Up</a>
</nav>
</div>
</header>
<main>
<section class="content-block info-box form-card">
<h2>Forgot Password</h2>
<p>Enter your email and the contact number you registered with, then choose a new password.</p>
<form action="forgot.php" method="post">
<label for="email">Email</label>
<input type="email" id="email" name="email" placeholder="you@example.com" required>
<label for="contact">Contact Number</label>
<input type="tel" id="contact" name="contact" placeholder="09123456789" pattern="09[0-9]{9}" title="11 digits starting with 09" required>
<label for="password">New Password</label>
<input type="password" id="password" name="password" minlength="8" required>
<label for="confirm_password">Confirm New Password</label>
<input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
<button type="submit" class="btn">Reset Password</button>
</form>
<div class="link-row">
<a href="login.php">Back to Log In</a>
</div>
</section>
</main>
<footer>
<div class="footer-inner">
<div class="footer-brand"><img class="footer-logo" src="images/savespace-logo.svg" alt="SaveSpace logo"><span>SaveSpace</span></div>
<div class="footer-links">
<a href="mailto:savespace@gmail.com" title="Email SaveSpace"><img class="footer-icon" src="images/savespace-icon-email.svg" alt="Email SaveSpace"></a>
<a href="tel:09950939228" title="Call SaveSpace"><img class="footer-icon" src="images/savespace-icon-phone.svg" alt="Call SaveSpace"></a>
</div>
<div class="footer-legal">
<a href="terms.html">Terms</a>
<a href="privacy.html">Privacy</a>
</div>
</div>
<p class="footer-copyright">&copy; 2026 SaveSpace. All rights reserved.</p>
</footer>
</body>
</html>
