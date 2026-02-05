<?php
// require_once __DIR__ . '/../app/Config/config.php';
require_once '../app/Config/database.php';
// $bmkg = require '../app/Config/bmkg.php';



$url = 'https://iklim.bmkg.go.id/id/kualitas-udara-indonesia/';
$html = file_get_contents($url);

if (!$html) {
    die('Gagal mengambil data kualitas udara BMKG');
}

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

/*
  Ambil baris tabel kualitas udara
  (struktur BMKG bisa berubah, tapi ini pendekatan paling aman)
*/
$rows = $xpath->query("//table//tr");

foreach ($rows as $row) {
    $cols = $row->getElementsByTagName('td');
    if ($cols->length < 3) continue;

    $lokasi   = trim($cols->item(0)->nodeValue);
    $pm25     = trim($cols->item(1)->nodeValue);
    $kategori = trim($cols->item(2)->nodeValue);

    if (!$lokasi || !$kategori) continue;

    $stmt = $conn->prepare("
        INSERT INTO kualitas_udara
        (lokasi, pm25, kategori, waktu)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sdss",
        $lokasi,
        floatval($pm25),
        $kategori,
        date('Y-m-d H:i:s')
    );

    $stmt->execute();

    echo "Udara {$lokasi} ({$kategori}) disimpan\n";
}
