<?php
require_once __DIR__ . '/../app/Config/database.php';

$type = $_GET['type'] ?? 'ALL';

// Base query
$sql = "
    SELECT id, alert_type, title, link, pub_date
    FROM nowcast_alerts
    WHERE 1=1
";

// Filter jenis alert
if ($type !== 'ALL') {
    $sql .= " AND alert_type = '" . $conn->real_escape_string($type) . "'";
}

// ⚠️ JANGAN FILTER 7 HARI DULU (BIAR KELIHATAN)
$sql .= " ORDER BY pub_date DESC LIMIT 50";

$result = $conn->query($sql);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'data' => $data
]);
