<?php
/**
 * Cron Job: Send Alert Notifications
 * Run: */5 * * * * php /path/to/cron/send_alert.php
 */

require_once dirname(__DIR__) . '/index.php';

use App\Controllers\CronController;

$cron = new CronController();
$result = $cron->sendAlerts();

echo json_encode($result);
