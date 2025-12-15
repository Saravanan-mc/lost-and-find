<?php
header("Content-Type: application/json");
require_once "sql.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["email"], $data["password"], $data["role"])) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

$email = strtolower(trim($data["email"]));
$password = $data["password"];
$role = $data["role"];

if (!in_array($role, ["student", "admin", "premium"])) {
    echo json_encode(["success" => false, "message" => "Invalid role"]);
    exit;
}

// check duplicate
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(["success" => false, "message" => "Email already registered"]);
    exit;
}

// hash password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
if ($stmt->execute([$email, $hashedPassword, $role])) {
    echo json_encode(["success" => true, "message" => "Registration successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed"]);
}
?>
