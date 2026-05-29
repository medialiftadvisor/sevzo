<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['phone'])) {
    echo json_encode(["status" => "error", "message" => "Phone is required."]);
    exit;
}

$phone = trim($data['phone']);

try {
    $stmt = $pdo->prepare("SELECT wallet FROM users WHERE phone = ?");
    $stmt->execute([$phone]);
    $user = $stmt->fetch();

    if ($user) {
        echo json_encode(["status" => "success", "wallet" => (float)$user['wallet']]);
    } else {
        echo json_encode(["status" => "error", "message" => "User not found."]);
    }
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
