<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['action'])) {
    echo json_encode(["status" => "error", "message" => "Invalid action."]);
    exit;
}

$action = $data['action'];

try {
    switch ($action) {
        case 'get_dashboard':
            // Total Revenue (from Delivered orders)
            $stmt = $pdo->query("SELECT SUM(total_amount) as revenue FROM orders WHERE order_status = 'Delivered'");
            $rev = $stmt->fetch();
            $revenue = $rev['revenue'] ? (float)$rev['revenue'] : 0.00;

            // Total Orders count
            $stmt = $pdo->query("SELECT COUNT(id) as cnt FROM orders");
            $ord = $stmt->fetch();
            $ordersCount = (int)$ord['cnt'];

            // Total Customers count
            $stmt = $pdo->query("SELECT COUNT(id) as cnt FROM users");
            $usr = $stmt->fetch();
            $usersCount = (int)$usr['cnt'];

            // Total Riders count
            $stmt = $pdo->query("SELECT COUNT(id) as cnt FROM riders");
            $rid = $stmt->fetch();
            $ridersCount = (int)$rid['cnt'];

            echo json_encode([
                "status" => "success",
                "stats" => [
                    "revenue" => $revenue,
                    "orders" => $ordersCount,
                    "users" => $usersCount,
                    "riders" => $ridersCount
                ]
            ]);
            break;

        case 'get_products':
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
            $products = $stmt->fetchAll();
            echo json_encode(["status" => "success", "products" => $products]);
            break;

        case 'get_orders':
            $stmt = $pdo->query("SELECT o.*, a.house_no, a.full_address, a.landmark, a.pincode, a.email 
                                 FROM orders o 
                                 LEFT JOIN addresses a ON o.customer_phone = a.phone 
                                 ORDER BY o.id DESC");
            $orders = $stmt->fetchAll();
            echo json_encode(["status" => "success", "orders" => $orders]);
            break;

        case 'get_users':
            $stmt = $pdo->query("SELECT id, name, phone, wallet, password FROM users ORDER BY id DESC");
            $users = $stmt->fetchAll();
            echo json_encode(["status" => "success", "users" => $users]);
            break;

        case 'save_user':
            $id = (int)$data['id'];
            $name = trim($data['name']);
            $phone = trim($data['phone']);
            $password = trim($data['password']);
            $wallet = (float)$data['wallet'];

            $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, password = ?, wallet = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $password, $wallet, $id]);
            echo json_encode(["status" => "success", "message" => "Customer details updated successfully."]);
            break;

        case 'get_riders':
            $stmt = $pdo->query("SELECT id, name, phone, is_online FROM riders ORDER BY id DESC");
            $riders = $stmt->fetchAll();
            echo json_encode(["status" => "success", "riders" => $riders]);
            break;

        case 'save_product':
            $id = (int)$data['id'];
            $name = trim($data['name']);
            $price = (float)$data['price'];
            $image_url = trim($data['image_url']);
            $images = isset($data['images']) ? trim($data['images']) : '';
            $category = trim($data['category']);
            $available_pincodes = trim($data['available_pincodes']);
            $brand = isset($data['brand']) ? trim($data['brand']) : 'SEVZO Fresh';
            $description = isset($data['description']) ? trim($data['description']) : '';
            $highlights = isset($data['highlights']) ? trim($data['highlights']) : '';

            if ($id > 0) {
                // Update
                $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image_url = ?, images = ?, category = ?, available_pincodes = ?, brand = ?, description = ?, highlights = ? WHERE id = ?");
                $stmt->execute([$name, $price, $image_url, $images, $category, $available_pincodes, $brand, $description, $highlights, $id]);
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO products (name, price, image_url, images, category, available_pincodes, brand, description, highlights) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $price, $image_url, $images, $category, $available_pincodes, $brand, $description, $highlights]);
            }
            echo json_encode(["status" => "success", "message" => "Product saved successfully."]);
            break;

        case 'update_wallet':
            $id = (int)$data['id'];
            $wallet = (float)$data['wallet'];
            $stmt = $pdo->prepare("UPDATE users SET wallet = ? WHERE id = ?");
            $stmt->execute([$wallet, $id]);
            echo json_encode(["status" => "success", "message" => "Wallet updated successfully."]);
            break;

        case 'update_order_status':
            $id = (int)$data['id'];
            $status = trim($data['order_status']);
            $partner_id = isset($data['delivery_partner_id']) ? $data['delivery_partner_id'] : null;

            if ($partner_id !== null) {
                $partner_val = ($partner_id === '' || $partner_id === 'null' || $partner_id === 0) ? null : (int)$partner_id;
                $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, delivery_partner_id = ? WHERE id = ?");
                $stmt->execute([$status, $partner_val, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
                $stmt->execute([$status, $id]);
            }
            echo json_encode(["status" => "success", "message" => "Order status updated successfully."]);
            break;

        case 'delete_item':
            $id = (int)$data['id'];
            $type = trim($data['type']);
            if ($type === 'product') {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$id]);
            }
            echo json_encode(["status" => "success", "message" => "Item deleted successfully."]);
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Invalid action specified."]);
            break;
    }
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
