<?php
require_once __DIR__ . '/../app/Config/database.php';

$RSS_URL = "https://www.bmkg.go.id/alerts/nowcast/id";

$WILAYAH_KTI = [
    "SULAWESI", "MALUKU", "PAPUA",
    "GORONTALO", "AMBON", "TERNATE",
    "JAYAPURA", "SORONG", "MANOKWARI", "MERAUKE"
];

$xml = @simplexml_load_file($RSS_URL);
if (!$xml) {
    echo "Gagal ambil RSS\n";
    exit;
}

foreach ($xml->channel->item as $item) {
    $title = (string)$item->title;
    $link  = (string)$item->link;
    $pub   = date('Y-m-d H:i:s', strtotime((string)$item->pubDate));

    // Filter wilayah KTI
    $is_kti = false;
    foreach ($WILAYAH_KTI as $w) {
        if (stripos($title, $w) !== false) {
            $is_kti = true;
            break;
        }
    }
    if (!$is_kti) continue;

    // Deteksi jenis alert
    $alert_type = 'LAINNYA';
    if (stripos($title, 'HUJAN') !== false)  $alert_type = 'HUJAN';
    if (stripos($title, 'ANGIN') !== false)  $alert_type = 'ANGIN';
    if (stripos($title, 'PETIR') !== false)  $alert_type = 'PETIR';

    // Cek duplikasi
    $stmt = $conn->prepare(
        "SELECT id FROM nowcast_alerts WHERE link = ?"
    );
    $stmt->bind_param("s", $link);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        continue;
    }
    $stmt->close();

    // Insert data
    $stmt = $conn->prepare(
        "INSERT INTO nowcast_alerts 
        (alert_type, title, wilayah, link, pub_date)
        VALUES (?, ?, ?, ?, ?)"
    );
    $wilayah = "KTI";
    $stmt->bind_param(
        "sssss",
        $alert_type,
        $title,
        $wilayah,
        $link,
        $pub
    );
    $stmt->execute();
    $stmt->close();

    echo "✔ ALERT MASUK: $title\n";
}
