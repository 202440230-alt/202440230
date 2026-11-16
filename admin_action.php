<?php
include("session.php");
include("config.php");

if ($_SESSION['role'] != 'admin' || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$action = $_POST['action'] ?? '';
$id = (int) ($_POST['id'] ?? 0);

if ($action == 'unit') {
    $status = $_POST['status'] ?? '';
    if (in_array($status, ['available', 'limited', 'reserved', 'occupied'])) {
        $stmt = mysqli_prepare($db, "UPDATE units SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
} else {
    $stmt = mysqli_prepare($db, "SELECT unit_id, status FROM reservations WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($row) {
        $new_status = '';
        $unit_status = '';
        if ($action == 'confirm' && $row['status'] == 'pending') {
            $new_status = 'confirmed';
            $unit_status = 'occupied';
        } elseif ($action == 'cancel' && $row['status'] == 'pending') {
            $new_status = 'cancelled';
            $unit_status = 'available';
        } elseif ($action == 'complete' && $row['status'] == 'confirmed') {
            $new_status = 'completed';
            $unit_status = 'available';
        }
        if ($new_status != '') {
            $stmt = mysqli_prepare($db, "UPDATE reservations SET status = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $new_status, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($db, "UPDATE units SET status = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $unit_status, $row['unit_id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
header("Location: admin.php");
?>
