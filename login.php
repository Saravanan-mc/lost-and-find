<?php
header("Content-Type: application/json");
require_once "sql.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["email"], $data["password"])) {
    echo json_encode(["success" => false, "message" => "Missing credentials"]);
    exit;
}

$email = strtolower(trim($data["email"]));
$password = $data["password"];

$stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user["password"])) {
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user["id"],
            "email" => $user["email"],
            "role" => $user["role"]
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid email or password"]);
}
?>
