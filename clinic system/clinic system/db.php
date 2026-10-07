<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic";

$conn = mysqli_connect(
    $servername,
    $username,
    $password,
    $dbname
);

if (!$conn) {
    die("فشل الاتصال: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
