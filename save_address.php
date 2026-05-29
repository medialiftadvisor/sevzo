<?php
header("Content-Type: application/json");
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['phone']) || empty($data['receiver_name']) || empty($data['pincode']) || empty($data['city']) || empty($data['house']) || empty($data['full_address'])) {
    echo json_encode(["status" => "error", "message" => "Required fields missing."]);
    exit;
}

$phone = trim($data['phone']);
$receiver_name = trim($data['receiver_name']);
$email = isset($data['email']) ? trim($data['email']) : null;
$pincode = trim($data['pincode']);
$city = trim($data['city']);
$house_no = trim($data['house']);
$full_address = trim($data['full_address']);
$landmark = isset($data['landmark']) ? trim($data['landmark']) : null;
$lat = isset($data['lat']) ? trim($data['lat']) : null;
$lng = isset($data['lng']) ? trim($data['lng']) : null;

try {
    // Check if address already exists for phone
    $stmt = $pdo->prepare("SELECT id FROM addresses WHERE phone = ?");
    $stmt->execute([$phone]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Update
        $stmt = $pdo->prepare("UPDATE addresses SET receiver_name = ?, email = ?, pincode = ?, city = ?, house_no = ?, full_address = ?, landmark = ?, lat = ?, lng = ? WHERE phone = ?");
        $stmt->execute([$receiver_name, $email, $pincode, $city, $house_no, $full_address, $landmark, $lat, $lng, $phone]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO addresses (phone, receiver_name, email, pincode, city, house_no, full_address, landmark, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$phone, $receiver_name, $email, $pincode, $city, $house_no, $full_address, $landmark, $lat, $lng]);
    }

    echo json_encode(["status" => "success", "message" => "Address saved successfully."]);
} catch (\Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
