<?php
header("Content-Type: application/json");
require_once "db.php";

// Add email column to riders table if it doesn't exist
try {
    $pdo->exec("ALTER TABLE `riders` ADD COLUMN `email` VARCHAR(100) DEFAULT NULL AFTER `phone`;");
} catch (\Exception $e) {
    // Column already exists or table doesn't exist yet
}

$data = json_decode(file_get_contents("php://input"), true);

// Support beacon POST where input comes from raw POST string
if (!$data && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
}

if (!$data || empty($data['action'])) {
    echo json_encode(["status" => "error", "message" => "Invalid action."]);
    exit;
}

$action = $data['action'];

try {
    switch ($action) {
        case 'login':
            $phone = trim($data['phone']);
            $password = $data['password'];

            $stmt = $pdo->prepare("SELECT * FROM riders WHERE phone = ?");
            $stmt->execute([$phone]);
            $rider = $stmt->fetch();

            if ($rider && $password === $rider['password']) {
                echo json_encode([
                    "status" => "success",
                    "user" => [
                        "id" => $rider['id'],
                        "name" => $rider['name'],
                        "phone" => $rider['phone'],
                        "email" => isset($rider['email']) ? $rider['email'] : '',
                        "is_online" => (int)$rider['is_online'],
                        "profile_pic" => $rider['profile_pic']
                    ]
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid mobile number or password!"]);
            }
            break;

        case 'toggle_status':
            $id = (int)$data['id'];
            $status = (int)$data['status'];
            $lat = isset($data['lat']) ? trim($data['lat']) : null;
            $lng = isset($data['lng']) ? trim($data['lng']) : null;

            if ($status == 1 && $lat && $lng) {
                $stmt = $pdo->prepare("UPDATE riders SET is_online = ?, lat = ?, lng = ? WHERE id = ?");
                $stmt->execute([$status, $lat, $lng, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE riders SET is_online = ? WHERE id = ?");
                $stmt->execute([$status, $id]);
            }
            echo json_encode(["status" => "success", "message" => "Duty status updated."]);
            break;

        case 'get_orders':
            $partner_id = (int)$data['partner_id'];
            $lat = isset($data['lat']) ? (float)$data['lat'] : null;
            $lng = isset($data['lng']) ? (float)$data['lng'] : null;

            // 1. Fetch new nearby orders (Pending/Confirmed with no partner assigned)
            $stmt = $pdo->query("SELECT o.*, a.house_no, a.full_address, a.landmark, a.pincode, a.city, a.receiver_name, o.customer_phone 
                                 FROM orders o 
                                 LEFT JOIN addresses a ON o.customer_phone = a.phone 
                                 WHERE o.delivery_partner_id IS NULL AND o.order_status IN ('Pending', 'Confirmed') 
                                 ORDER BY o.id DESC");
            $newOrders = $stmt->fetchAll();

            // Calculate distance in memory if rider coordinates are available
            if ($lat !== null && $lng !== null) {
                foreach ($newOrders as &$no) {
                    if (!empty($no['lat']) && !empty($no['lng'])) {
                        $noLat = (float)$no['lat'];
                        $noLng = (float)$no['lng'];
                        // Calculate simple Euclidean distance or Haversine if needed, simple distance is fine here
                        $no['distance'] = sqrt(pow($noLat - $lat, 2) + pow($noLng - $lng, 2));
                    } else {
                        $no['distance'] = 999;
                    }
                }
                // Sort by distance
                usort($newOrders, function($a, $b) {
                    return $a['distance'] <=> $b['distance'];
                });
            }

            // 2. Fetch active deliveries assigned to this partner (Out for Delivery)
            $stmt = $pdo->prepare("SELECT o.*, a.house_no, a.full_address, a.landmark, a.pincode, a.city, a.receiver_name, o.customer_phone 
                                   FROM orders o 
                                   LEFT JOIN addresses a ON o.customer_phone = a.phone 
                                   WHERE o.delivery_partner_id = ? AND o.order_status = 'Out for Delivery'
                                   ORDER BY o.id DESC");
            $stmt->execute([$partner_id]);
            $activeOrders = $stmt->fetchAll();

            // Fetch order items for each active order
            foreach ($activeOrders as &$ao) {
                $stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $stmtItems->execute([$ao['id']]);
                $ao['items'] = $stmtItems->fetchAll();
            }

            echo json_encode([
                "status" => "success",
                "new_orders" => $newOrders,
                "active_orders" => $activeOrders
            ]);
            break;

        case 'accept_order':
            $order_id = (int)$data['order_id'];
            $partner_id = (int)$data['partner_id'];
            
            // Check if order is still available
            $stmt = $pdo->prepare("SELECT delivery_partner_id FROM orders WHERE id = ?");
            $stmt->execute([$order_id]);
            $ord = $stmt->fetch();

            if ($ord && $ord['delivery_partner_id'] === null) {
                $stmt = $pdo->prepare("UPDATE orders SET delivery_partner_id = ?, order_status = 'Out for Delivery' WHERE id = ?");
                $stmt->execute([$partner_id, $order_id]);
                echo json_encode(["status" => "success", "message" => "Order accepted."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Order has already been accepted by another rider!"]);
            }
            break;

        case 'complete_order':
            $order_id = (int)$data['order_id'];
            $pin = trim($data['pin']);
            $delivery_proof = isset($data['delivery_proof']) ? $data['delivery_proof'] : null;

            $stmt = $pdo->prepare("SELECT delivery_pin FROM orders WHERE id = ?");
            $stmt->execute([$order_id]);
            $ord = $stmt->fetch();

            if ($ord && $ord['delivery_pin'] === $pin) {
                $stmt = $pdo->prepare("UPDATE orders SET order_status = 'Delivered', delivery_proof = ? WHERE id = ?");
                $stmt->execute([$delivery_proof, $order_id]);
                echo json_encode(["status" => "success", "message" => "Delivery marked as complete."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Incorrect security PIN. Please verify with the customer."]);
            }
            break;

        case 'get_history':
            $partner_id = (int)$data['partner_id'];

            $stmt = $pdo->prepare("SELECT * FROM orders WHERE delivery_partner_id = ? AND order_status = 'Delivered' ORDER BY id DESC");
            $stmt->execute([$partner_id]);
            $history = $stmt->fetchAll();

            $total_deliveries = count($history);
            $total_revenue = $total_deliveries * 25.00; // Rider gets Rs. 25 per delivery

            echo json_encode([
                "status" => "success",
                "total_deliveries" => $total_deliveries,
                "total_revenue" => $total_revenue,
                "history" => $history
            ]);
            break;

        case 'update_profile':
            $partner_id = (int)$data['partner_id'];
            $phone = trim($data['phone']);
            $email = trim($data['email']);

            $stmt = $pdo->prepare("UPDATE riders SET phone = ?, email = ? WHERE id = ?");
            $stmt->execute([$phone, $email, $partner_id]);

            // Fetch updated profile
            $stmt = $pdo->prepare("SELECT * FROM riders WHERE id = ?");
            $stmt->execute([$partner_id]);
            $rider = $stmt->fetch();

            echo json_encode([
                "status" => "success",
                "user" => [
                    "id" => $rider['id'],
                    "name" => $rider['name'],
                    "phone" => $rider['phone'],
                    "email" => $rider['email'],
                    "is_online" => (int)$rider['is_online'],
                    "profile_pic" => $rider['profile_pic']
                ]
            ]);
            break;

        case 'upload_selfie':
            $partner_id = (int)$data['partner_id'];
            $image = $data['image']; // Base64 string

            $stmt = $pdo->prepare("UPDATE riders SET profile_pic = ? WHERE id = ?");
            $stmt->execute([$image, $partner_id]);

            // Fetch updated profile
            $stmt = $pdo->prepare("SELECT * FROM riders WHERE id = ?");
            $stmt->execute([$partner_id]);
            $rider = $stmt->fetch();

            echo json_encode([
                "status" => "success",
                "user" => [
                    "id" => $rider['id'],
                    "name" => $rider['name'],
                    "phone" => $rider['phone'],
                    "email" => $rider['email'],
                    "is_online" => (int)$rider['is_online'],
                    "profile_pic" => $rider['profile_pic']
                ]
            ]);
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Invalid action."]);
            break;
    }
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
