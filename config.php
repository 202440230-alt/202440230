<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'savespace');
define('DB_PORT', 3307);
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
if (!$db) {
    die("Could not connect to the database: " . mysqli_connect_error());
}
mysqli_set_charset($db, "utf8mb4");
?>
