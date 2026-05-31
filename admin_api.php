<?php
header("Content-Type: application/json");
require_once "db.php";

// File upload endpoint for product images
if (isset($_GET['upload']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $target_dir = __DIR__ . "/uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $filename = "img_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo json_encode(["status" => "success", "url" => "uploads/" . $filename]);
            exit;
        }
    }
    echo json_encode(["status" => "error", "message" => "Failed to save uploaded image. Allowed formats: JPG, JPEG, PNG, GIF, WEBP."]);
    exit;
}

// Bulk CSV upload endpoint
if (isset($_GET['bulk_upload']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, "r");
    if ($handle !== FALSE) {
        $header = fgetcsv($handle, 1000, ",");
        if ($header !== FALSE) {
            $header = array_map('strtolower', array_map('trim', $header));
            
            $pdo->beginTransaction();
            $count = 0;
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($row) < count($header)) continue;
                
                $item = array_combine($header, array_slice($row, 0, count($header)));
                if ($item === FALSE || empty($item['name'])) continue;
                
                $name = trim($item['name']);
                $price = isset($item['price']) ? (float)$item['price'] : 0.0;
                $image_url = isset($item['image_url']) ? trim($item['image_url']) : '';
                $images = isset($item['images']) ? trim($item['images']) : '';
                $category = isset($item['category']) ? trim($item['category']) : 'Grocery & Kitchen';
                $available_pincodes = isset($item['available_pincodes']) ? trim($item['available_pincodes']) : 'ALL';
                $brand = isset($item['brand']) ? trim($item['brand']) : 'SEVZO Fresh';
                $description = isset($item['description']) ? trim($item['description']) : '';
                $highlights = isset($item['highlights']) ? trim($item['highlights']) : '';
                $stock = isset($item['stock']) ? (int)$item['stock'] : 10;
                $variations = isset($item['variations']) ? trim($item['variations']) : null;
                
                // Check if product exists by name
                $stmt = $pdo->prepare("SELECT id FROM products WHERE name = ?");
                $stmt->execute([$name]);
                $existing = $stmt->fetch();
                
                if ($existing) {
                    $stmt = $pdo->prepare("UPDATE products SET price = ?, image_url = ?, images = ?, category = ?, available_pincodes = ?, brand = ?, description = ?, highlights = ?, stock = ?, variations = ? WHERE id = ?");
                    $stmt->execute([$price, $image_url, $images, $category, $available_pincodes, $brand, $description, $highlights, $stock, $variations, $existing['id']]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO products (name, price, image_url, images, category, available_pincodes, brand, description, highlights, stock, variations) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $price, $image_url, $images, $category, $available_pincodes, $brand, $description, $highlights, $stock, $variations]);
                }
                $count++;
            }
            $pdo->commit();
            fclose($handle);
            echo json_encode(["status" => "success", "message" => "Successfully imported/updated $count products!"]);
            exit;
        }
    }
    echo json_encode(["status" => "error", "message" => "Failed to read CSV file."]);
    exit;
}

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
            $stock = isset($data['stock']) ? (int)$data['stock'] : 10;
            $variations = isset($data['variations']) ? trim($data['variations']) : null;

            if ($id > 0) {
                // Update
                $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image_url = ?, images = ?, category = ?, available_pincodes = ?, brand = ?, description = ?, highlights = ?, stock = ?, variations = ? WHERE id = ?");
                $stmt->execute([$name, $price, $image_url, $images, $category, $available_pincodes, $brand, $description, $highlights, $stock, $variations, $id]);
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO products (name, price, image_url, images, category, available_pincodes, brand, description, highlights, stock, variations) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $price, $image_url, $images, $category, $available_pincodes, $brand, $description, $highlights, $stock, $variations]);
            }
            echo json_encode(["status" => "success", "message" => "Product saved successfully."]);
            break;

        case 'get_reviews':
            $stmt = $pdo->query("SELECT r.*, p.name as product_name FROM product_reviews r JOIN products p ON r.product_id = p.id ORDER BY r.id DESC");
            $reviews = $stmt->fetchAll();
            echo json_encode(["status" => "success", "reviews" => $reviews]);
            break;

        case 'save_review':
            $id = (int)$data['id'];
            $product_id = (int)$data['product_id'];
            $customer_name = trim($data['customer_name']);
            $rating = (int)$data['rating'];
            $review_text = trim($data['review_text']);
            $status = trim($data['status']);
            
            if ($id > 0) {
                $stmt = $pdo->prepare("UPDATE product_reviews SET product_id = ?, customer_name = ?, rating = ?, review_text = ?, status = ? WHERE id = ?");
                $stmt->execute([$product_id, $customer_name, $rating, $review_text, $status, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO product_reviews (product_id, customer_name, rating, review_text, status) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$product_id, $customer_name, $rating, $review_text, $status]);
            }
            echo json_encode(["status" => "success", "message" => "Review saved successfully."]);
            break;

        case 'approve_review':
            $id = (int)$data['id'];
            $stmt = $pdo->prepare("UPDATE product_reviews SET status = 'Approved' WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(["status" => "success", "message" => "Review approved successfully."]);
            break;

        case 'delete_review':
            $id = (int)$data['id'];
            $stmt = $pdo->prepare("DELETE FROM product_reviews WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(["status" => "success", "message" => "Review deleted successfully."]);
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
