<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../app/Config/database.php';

// GEMPA
$gempa = $conn->query("
    SELECT magnitude 
    FROM gempa_events 
    ORDER BY waktu DESC 
    LIMIT 1
")->fetch_assoc();

$gempa_status = 'AMAN';
if ($gempa && $gempa['magnitude'] >= 6) {
    $gempa_status = 'WASPADA';
}

// HUJAN (sementara dummy, nanti isi dari cuaca_realtime)
$hujan_status = 'AMAN';

// UDARA (sementara dummy)
$udara_status = 'AMAN';

// BENDUNGAN (sementara dummy)
$bendungan_status = 'AMAN';

echo json_encode([
    'status' => 'ok',
    'ringkasan' => [
        'gempa'     => $gempa_status,
        'hujan'     => $hujan_status,
        'udara'     => $udara_status,
        'bendungan' => $bendungan_status
    ],
    'updated_at' => date('Y-m-d H:i:s')
]);
