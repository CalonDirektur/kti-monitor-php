<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Koneksi DB
require_once __DIR__ . '/../app/Config/database.php';

// Config BMKG
$bmkg = require __DIR__ . '/../app/Config/bmkg.php';

// Ambil data gempa dari BMKG
$json = file_get_contents($bmkg['GEMPA_AUTOGEMPA']);
if ($json === false) {
    die('Gagal mengambil data gempa dari BMKG');
}

$data = json_decode($json, true);
if (!isset($data['Infogempa']['gempa'])) {
    die('Format data gempa BMKG tidak sesuai');
}

$g = $data['Infogempa']['gempa'];

// Parsing waktu
$waktu = date(
    'Y-m-d H:i:s',
    strtotime($g['Tanggal'] . ' ' . $g['Jam'])
);

$magnitude = floatval($g['Magnitude']);

// 🔒 CEK DUPLIKAT (waktu + magnitude + wilayah)
$cek = $conn->prepare("
    SELECT id FROM gempa_events
    WHERE waktu = ? AND magnitude = ? AND wilayah = ?
");
$cek->bind_param("sds", $waktu, $magnitude, $g['Wilayah']);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
    echo "Gempa sudah ada di database\n";
    exit;
}

// INSERT DATA
$stmt = $conn->prepare("
    INSERT INTO gempa_events
    (waktu, magnitude, lintang, bujur, kedalaman, wilayah, potensi)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sdsssss",
    $waktu,
    $magnitude,
    $g['Lintang'],
    $g['Bujur'],
    $g['Kedalaman'],
    $g['Wilayah'],
    $g['Potensi']
);

$stmt->execute();

echo "✅ Gempa berhasil disimpan\n";
echo "Wilayah   : {$g['Wilayah']}\n";
echo "Magnitudo : {$g['Magnitude']} SR\n";
echo "Waktu     : {$waktu}\n";
