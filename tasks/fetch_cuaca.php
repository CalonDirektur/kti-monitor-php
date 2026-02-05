<?php
// require_once __DIR__ . '/../app/Config/config.php';
require_once '../app/Config/database.php';
// $bmkg = require '../app/Config/bmkg.php';


/*
  Contoh kode wilayah BMKG (adm4):
  - Makassar      : 73.71.01.1001
  - Manokwari     : 91.01.01.1001
  - Ambon         : 81.71.01.1001
*/
$wilayah_list = [
    'Makassar'  => '73.71.01.1001',
    'Ambon'     => '81.71.01.1001',
    'Manokwari' => '91.01.01.1001'
];

foreach ($wilayah_list as $nama => $kode) {

    $url = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4={$kode}";
    $json = file_get_contents($url);

    if (!$json) {
        echo "Gagal ambil cuaca {$nama}\n";
        continue;
    }

    $data = json_decode($json, true);

    // Ambil prakiraan terdekat (slot pertama)
    $cuaca = $data['data'][0]['cuaca'][0];

    $stmt = $conn->prepare("
        INSERT INTO cuaca_realtime
        (wilayah, kondisi, suhu, kelembaban, kecepatan_angin, waktu)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssdiis",
        $nama,
        $cuaca['weather_desc'],
        $cuaca['t'],
        $cuaca['hu'],
        $cuaca['ws'],
        date('Y-m-d H:i:s')
    );

    $stmt->execute();

    echo "Cuaca {$nama} disimpan ({$cuaca['weather_desc']})\n";
}
