<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// DB
require_once __DIR__ . '/../app/Config/database.php';

// Ambil 10 gempa terbaru
$q = $conn->query("
    SELECT 
        wilayah,
        magnitude,
        lintang,
        bujur,
        kedalaman,
        potensi,
        waktu
    FROM gempa_events
    ORDER BY waktu DESC
    LIMIT 10
");

$data = [];

while ($r = $q->fetch_assoc()) {

    // Konversi koordinat BMKG ke decimal
    $lat = str_replace(['LS','LU'], '', $r['lintang']);
    if (strpos($r['lintang'], 'LS') !== false) $lat = -$lat;

    $lon = str_replace(['BT','BB'], '', $r['bujur']);
    if (strpos($r['bujur'], 'BB') !== false) $lon = -$lon;

    $data[] = [
        'wilayah'   => $r['wilayah'],
        'magnitude' => (float)$r['magnitude'],
        'lat'       => (float)$lat,
        'lon'       => (float)$lon,
        'kedalaman' => $r['kedalaman'],
        'potensi'   => $r['potensi'],
        'waktu'     => $r['waktu']
    ];
}

echo json_encode([
    'status' => 'ok',
    'total'  => count($data),
    'data'   => $data
]);
