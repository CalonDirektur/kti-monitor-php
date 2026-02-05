<?php
require_once __DIR__ . '/../app/Config/database.php';

$data = $db->query("
    SELECT title, link, pub_date
    FROM nowcast_alerts
    ORDER BY detected_at DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'status' => 'ok',
    'data' => $data
]);
