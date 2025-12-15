<?php
// db.php - Database connection file

$host = "localhost";
$user = "root";  // change if needed
$pass = "";      // change if needed
$db   = "blue_horizon";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>
