
<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "POST requests only"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (
    !is_array($data) ||
    !isset($data["latitude"], $data["longitude"]) ||
    !is_numeric($data["latitude"]) ||
    !is_numeric($data["longitude"])
) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid coordinates"]);
    exit;
}

$latitude = (float) $data["latitude"];
$longitude = (float) $data["longitude"];
$accuracy = isset($data["accuracy"]) && is_numeric($data["accuracy"])
    ? (float) $data["accuracy"]
    : null;

if (
    !is_finite($latitude) ||
    !is_finite($longitude) ||
    $latitude < -90 || $latitude > 90 ||
    $longitude < -180 || $longitude > 180
) {
    http_response_code(400);
    echo json_encode(["error" => "Coordinates out of range"]);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=location_tracker;charset=utf8mb4",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare(
        "INSERT INTO locations
         (latitude, longitude, accuracy_meters)
         VALUES (?, ?, ?)"
    );

    $stmt->execute([$latitude, $longitude, $accuracy]);

    echo json_encode(["success" => true]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode(["error" => "Database operation failed"]);
}
?>
