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
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE phone = ?");
    $stmt->execute([$phone]);
    $address = $stmt->fetch();

    if ($address) {
        echo json_encode(["status" => "success", "address" => $address]);
    } else {
        echo json_encode(["status" => "error", "message" => "Address not found."]);
    }
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
