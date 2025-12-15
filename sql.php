<?php
// sql.php - Database connection

$host = "localhost"; 
$user = "root";      // your MySQL username
$pass = "";          // your MySQL password
$dbname = "blue_horizon";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}
?>
