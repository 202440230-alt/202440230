<?php
include("session.php");
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) ($_POST['id'] ?? 0);

    $stmt = mysqli_prepare($db, "SELECT unit_id FROM reservations WHERE id = ? AND user_id = ? AND status = 'pending'");
    mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($row) {
        $stmt = mysqli_prepare($db, "UPDATE reservations SET status = 'cancelled' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($db, "UPDATE units SET status = 'available' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $row['unit_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
header("Location: reservations.php");
?>
