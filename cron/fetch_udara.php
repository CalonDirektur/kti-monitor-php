<?php
/**
 * Cron Job: Fetch Air Quality Data
 * Run: 0 * * * * php /path/to/cron/fetch_udara.php
 */

require_once dirname(__DIR__) . '/index.php';

use App\Controllers\CronController;

$cron = new CronController();
$result = $cron->fetchUdara();

echo json_encode($result);
