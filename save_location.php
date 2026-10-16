
<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data.']);
    exit;
}

$latitude = filter_var($data['latitude'] ?? null, FILTER_VALIDATE_FLOAT);
$longitude = filter_var($data['longitude'] ?? null, FILTER_VALIDATE_FLOAT);
$accuracy = filter_var($data['accuracy'] ?? null, FILTER_VALIDATE_FLOAT);

if (
    $latitude === false || $latitude === null ||
    $longitude === false || $longitude === null ||
    $accuracy === false || $accuracy === null ||
    $latitude < -90 || $latitude > 90 ||
    $longitude < -180 || $longitude > 180 ||
    $accuracy < 0
) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid coordinates or accuracy.']);
    exit;
}

try {
    $pdo = new PDO(
        'mysql:host=host.docker.internal;port=3306;dbname=location_tracker;charset=utf8mb4',
'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $stmt = $pdo->prepare(
        'INSERT INTO locations (latitude, longitude, accuracy)
         VALUES (:latitude, :longitude, :accuracy)'
    );

    $stmt->execute([
        ':latitude' => $latitude,
        ':longitude' => $longitude,
        ':accuracy' => $accuracy
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Location saved successfully.',
        'id' => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Unable to save location to the database.'
    ]);
}
