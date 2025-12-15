<?php
header("Content-Type: application/json");
require_once "sql.php";

$stmt = $pdo->query("SELECT COUNT(*) AS user_count FROM users");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(["user_count" => $row["user_count"] ?? 0]);
?>
