<?php
header("Content-Type: application/json");
require_once "db.php";

$category = isset($_GET['category']) ? trim($_GET['category']) : 'All';
$pincode = isset($_GET['pincode']) ? trim($_GET['pincode']) : '';

try {
    $query = "SELECT * FROM products WHERE 1=1";
    $params = [];

    // Filter by Category
    if ($category !== 'All' && !empty($category)) {
        $query .= " AND category = ?";
        $params[] = $category;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    // Filter by Pincode in memory to support CSV pincode format
    $filteredProducts = [];
    foreach ($products as $p) {
        $avPins = strtolower(trim($p['available_pincodes']));
        if ($avPins === 'all' || empty($pincode)) {
            $filteredProducts[] = $p;
        } else {
            // Check if user pincode is in the list
            $pinsArray = array_map('trim', explode(',', $avPins));
            if (in_array(strtolower($pincode), $pinsArray)) {
                $filteredProducts[] = $p;
            }
        }
    }

    echo json_encode($filteredProducts);
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
