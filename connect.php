<?php
// Database configuration
$servername = "localhost"; // or your database server
$db_user = "root"; // your database username
$db_passw = ""; // your database password
$dbname = "isp";

// Create connection
$conn = new mysqli($servername, $db_user, $db_passw, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else 
?>
