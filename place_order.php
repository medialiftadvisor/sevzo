<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['phone']) || empty($data['total_amount']) || empty($data['items'])) {
    echo json_encode(["status" => "error", "message" => "Invalid order data."]);
    exit;
}

$phone = trim($data['phone']);
$name = trim($data['name']);
$total_amount = (float)$data['total_amount'];
$payment_method = trim($data['payment_method']);
$lat = isset($data['lat']) ? trim($data['lat']) : '';
$lng = isset($data['lng']) ? trim($data['lng']) : '';
$items = $data['items'];

try {
    $pdo->beginTransaction();

    // 1. Process payment if payment method is Wallet
    if ($payment_method === 'Wallet') {
        $stmt = $pdo->prepare("SELECT wallet FROM users WHERE phone = ? FOR UPDATE");
        $stmt->execute([$phone]);
        $user = $stmt->fetch();

        if (!$user || (float)$user['wallet'] < $total_amount) {
            $pdo->rollBack();
            echo json_encode(["status" => "error", "message" => "Insufficient wallet balance."]);
            exit;
        }

        // Decrement wallet balance
        $stmt = $pdo->prepare("UPDATE users SET wallet = wallet - ? WHERE phone = ?");
        $stmt->execute([$total_amount, $phone]);
    }

    // 2. Generate random 4-digit PIN
    $delivery_pin = (string)rand(1000, 9999);

    // 3. Insert order
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_phone, total_amount, payment_method, order_status, delivery_pin, lat, lng) VALUES (?, ?, ?, ?, 'Pending', ?, ?, ?)");
    $stmt->execute([$name, $phone, $total_amount, $payment_method, $delivery_pin, $lat, $lng]);
    $order_id = $pdo->lastInsertId();

    // 4. Insert order items
    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($items as $item) {
        $item_name = trim($item['n']);
        $item_qty = (int)$item['q'];
        $item_price = (float)$item['p'];
        $stmtItem->execute([$order_id, $item_name, $item_qty, $item_price]);
    }

    $pdo->commit();
    echo json_encode(["status" => "success", "order_id" => $order_id]);
} catch (\Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["status" => "error", "message" => "Order placement failed: " . $e->getMessage()]);
}
?>
