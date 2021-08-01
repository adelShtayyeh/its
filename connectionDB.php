<?php

$servername = "ec2-54-145-185-178.compute-1.amazonaws.com";
$username = "obqixuukmjcfig";
$password = "e20c255d4f077909b5e6afa7b0c157ee7f01c6f20be70f9e1b2f73745542a514";
$db = "dig4l9mv75c3q";
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
