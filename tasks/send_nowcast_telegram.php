<?php
require_once __DIR__ . '/../app/Config/database.php';
require_once __DIR__ . '/../app/Config/telegram.php';

$alerts = $db->query("
    SELECT * FROM nowcast_alerts
    WHERE sent_telegram = 0
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($alerts as $a) {
    $pesan = "⚠️ *PERINGATAN DINI BMKG*\n\n".
             $a['title']."\n\n".
             "🔗 ".$a['link'];

    telegram_send($pesan);

    $upd = $db->prepare("
        UPDATE nowcast_alerts
        SET sent_telegram = 1
        WHERE id = ?
    ");
    $upd->execute([$a['id']]);
}
