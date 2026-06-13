<?php

$hostname = 'Localhost';
$username = 'root';
$password = '';
$database_name = 'youthreverfest_db';

$db = mysqli_connect($hostname, $username, $password, $database_name);

if($db->connect_error) {
    echo "Koneksi database gagal";
    die("Connection failed: " . $db->connect_error);
} 

echo "Koneksi database berhasil";

?>