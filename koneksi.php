<?php
$conn = mysqli_connect("localhost", "root", "", "youthreverfest_db");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$conn->set_charset("utf8mb4");
?>
