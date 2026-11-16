<?php
include("session.php");
include("config.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: home.php");
    exit();
}

$size = $_POST['size'] ?? '';
$movein = $_POST['movein'] ?? '';
$months = (int) ($_POST['months'] ?? 0);
$notes = trim($_POST['notes'] ?? '');

$errors = [];
if (!in_array($size, ["Small Locker", "Medium Room", "Large Room"])) {
    $errors[] = "Please choose a storage size.";
}
if ($movein == '' || strtotime($movein) === false) {
    $errors[] = "Please choose a move-in date.";
} elseif ($movein < date("Y-m-d")) {
    $errors[] = "The move-in date cannot be in the past.";
}
if (!in_array($months, [1, 3, 6, 12])) {
    $errors[] = "Please choose a rental length.";
}
if (strlen($notes) > 300) {
    $errors[] = "Notes must be 300 characters or less.";
}

if (count($errors) == 0) {
    $stmt = mysqli_prepare($db, "SELECT id, code FROM units WHERE size = ? AND status = 'available' ORDER BY code LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $size);
    mysqli_stmt_execute($stmt);
    $unit = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if (!$unit) {
        $errors[] = "Sorry, there is no available " . $size . " right now. Please try another size.";
    }
}

if (count($errors) > 0) {
    $title = "Reservation Failed";
    $messages = $errors;
    $is_error = true;
    $back_url = "home.php#reserve";
    $back_text = "Go Back";
    include("message.php");
    exit();
}

$stmt = mysqli_prepare($db, "INSERT INTO reservations (user_id, unit_id, movein, months, notes) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "iisis", $_SESSION['user_id'], $unit['id'], $movein, $months, $notes);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($db, "UPDATE units SET status = 'reserved' WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $unit['id']);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$title = "Request Sent";
$messages = ["Your request for unit " . $unit['code'] . " (" . $size . ") was sent.", "The owner will confirm it soon. You can check it in My Reservations."];
$is_error = false;
$back_url = "reservations.php";
$back_text = "View My Reservations";
include("message.php");
?>
