<?php
session_start();

// --- Database Connection ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "blue_horizon";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "msg" => "DB Connection failed"]));
}

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    if ($_POST["action"] === "register") {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $confirm = $_POST["confirm"];
        $role = $_POST["role"];

        if ($password !== $confirm) {
            echo json_encode(["success" => false, "msg" => "Passwords do not match"]);
            exit;
        }

        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo json_encode(["success" => false, "msg" => "Email already exists"]);
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (email,password,role) VALUES (?,?,?)");
        $stmt->bind_param("sss", $email, $hash, $role);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "msg" => "Registration successful"]);
        } else {
            echo json_encode(["success" => false, "msg" => "Registration failed"]);
        }
        exit;
    }

    if ($_POST["action"] === "login") {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT id,password,role FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            echo json_encode(["success" => false, "msg" => "Invalid credentials"]);
            exit;
        }
        $stmt->bind_result($id, $hash, $role);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["role"] = $role;
            echo json_encode(["success" => true, "msg" => "Welcome back!", "role" => $role]);
        } else {
            echo json_encode(["success" => false, "msg" => "Invalid credentials"]);
        }
        exit;
    }
}
