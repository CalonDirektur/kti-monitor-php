<?php

$url = 'https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-Papua.json';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (BMKG Client)\r\n",
        'timeout' => 10
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

$json = file_get_contents($url, false, $context);

if ($json === false) {
    echo "GAGAL AMBIL DATA\n";
} else {
    echo "BERHASIL\n\n";
    echo substr($json, 0, 500); // tampilkan sebagian
}
