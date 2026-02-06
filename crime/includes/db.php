<?php
$servername = "127.0.0.1";
$username = "root";       
$password = "";          
$database = "crime_tracking";
$port = 3306;              

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
