<?php
/**
 * Cron Job: Fetch Gempa Data
 * Run: */5 * * * * php /path/to/cron/fetch_gempa.php
 */

require_once dirname(__DIR__) . '/index.php';

use App\Controllers\CronController;

$cron = new CronController();
$result = $cron->fetchGempa();

echo json_encode($result);
