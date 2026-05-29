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
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_phone = ? ORDER BY id DESC");
    $stmt->execute([$phone]);
    $orders = $stmt->fetchAll();

    $formattedOrders = [];
    foreach ($orders as $o) {
        $o['time_formatted'] = date("d M Y, h:i A", strtotime($o['created_at']));
        $formattedOrders[] = $o;
    }

    echo json_encode(["status" => "success", "orders" => $formattedOrders]);
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
