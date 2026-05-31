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
        case 'submit_review':
            $product_id = (int)$data['product_id'];
            $customer_name = trim($data['customer_name']);
            $rating = (int)$data['rating'];
            $review_text = trim($data['review_text']);
            
            if ($product_id <= 0 || empty($customer_name) || $rating < 1 || $rating > 5 || empty($review_text)) {
                echo json_encode(["status" => "error", "message" => "All fields are required. Rating must be between 1 and 5 stars."]);
                exit;
            }
            
            $stmt = $pdo->prepare("INSERT INTO product_reviews (product_id, customer_name, rating, review_text, status) VALUES (?, ?, ?, ?, 'Approved')");
            $stmt->execute([$product_id, $customer_name, $rating, $review_text]);
            
            echo json_encode(["status" => "success", "message" => "Review submitted successfully!"]);
            break;
            
        default:
            echo json_encode(["status" => "error", "message" => "Action not supported."]);
            break;
    }
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
