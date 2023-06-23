<?php
$host = "localhost"; // replace with your server name
$username = "username"; // replace with your database username
$password = "password"; // replace with your database password
$database = "kodego/kodego_db"; // replace with your database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>