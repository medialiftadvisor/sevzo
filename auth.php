<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['phone']) || empty($data['password'])) {
    echo json_encode(["status" => "error", "message" => "Phone and password are required."]);
    exit;
}

$phone = trim($data['phone']);
$password = $data['password'];
$name = isset($data['name']) ? trim($data['name']) : null;

try {
    if ($name) {
        // Sign Up Mode
        // Check if user already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        if ($stmt->fetch()) {
            echo json_encode(["status" => "error", "message" => "Mobile number is already registered! Please Login."]);
            exit;
        }

        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (name, phone, password, wallet) VALUES (?, ?, ?, 0.00)");
        $stmt->execute([$name, $phone, $password]);

        // Get created user
        $userId = $pdo->lastInsertId();
        $user = ["id" => $userId, "name" => $name, "phone" => $phone, "wallet" => 0.00];
        
        echo json_encode(["status" => "success", "user" => $user]);
    } else {
        // Login Mode
        // Fetch user by phone
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) {
            echo json_encode([
                "status" => "success",
                "user" => [
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "phone" => $user['phone'],
                    "wallet" => (float)$user['wallet']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid mobile number or password!"]);
        }
    }
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
}
?>
