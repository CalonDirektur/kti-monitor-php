<?php
/**
 * Cron Job: Fetch Cuaca Data
 * Run: 0 */6 * * * php /path/to/cron/fetch_cuaca.php
 */

require_once dirname(__DIR__) . '/index.php';

use App\Controllers\CronController;

$cron = new CronController();
$wilayah = $argv[1] ?? null;
$result = $cron->fetchCuaca($wilayah);

echo json_encode($result);
