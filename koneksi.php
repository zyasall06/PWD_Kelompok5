<?php
$conn = mysqli_connect("localhost", "root", "root", "youthreverfest_db");
if (!$conn) 
    { 
    die("Koneksi gagal: " . mysqli_connect_error()); 
    }
?>
