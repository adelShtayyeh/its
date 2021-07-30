<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "useragents";
// Create connection
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    $msg1 = "Connection error";
    echo "<h1>$msg1</h1>";
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
$msg = "Connected successfully";
//echo "<h1>$msg</h1>";