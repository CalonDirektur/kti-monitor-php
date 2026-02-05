<?php

namespace App\Controllers;

use App\Services\BmkgService;
use App\Services\AirQualityService;
use App\Services\TelegramService;
use App\Models\GempaModel;
use App\Models\CuacaModel;
use App\Models\UdaraModel;

class CronController
{
    protected $bmkgService;
    protected $airQualityService;
    protected $telegramService;
    
    public function __construct()
    {
        $this->bmkgService = new BmkgService();
        $this->airQualityService = new AirQualityService();
        $this->telegramService = new TelegramService();
    }
    
    /**
     * Fetch latest earthquake data
     */
    public function fetchGempa()
    {
        try {
            $data = $this->bmkgService->getAutoGempa();
            
            $gempaModel = new GempaModel();
            $isNew = $gempaModel->saveIfNew($data);
            
            if ($isNew) {
                $this->log('New earthquake data saved');
                return ['success' => true, 'message' => 'New earthquake data saved', 'data' => $data];
            }
            
            return ['success' => true, 'message' => 'No new earthquake data'];
        } catch (\Exception $e) {
            $this->log('Error fetching gempa: ' . $e->getMessage(), 'error');
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Fetch weather forecast data
     */
    public function fetchCuaca($wilayah = null)
    {
        try {
            $data = $this->bmkgService->getCuaca($wilayah);
            
            $cuacaModel = new CuacaModel();
            $cuacaModel->save($data);
            
            $this->log('Weather data updated for: ' . ($wilayah ?? 'all regions'));
            return ['success' => true, 'message' => 'Weather data updated'];
        } catch (\Exception $e) {
            $this->log('Error fetching cuaca: ' . $e->getMessage(), 'error');
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Fetch air quality data
     */
    public function fetchUdara()
    {
        try {
            $data = $this->airQualityService->getData();
            
            $udaraModel = new UdaraModel();
            $udaraModel->save($data);
            
            $this->log('Air quality data updated');
            return ['success' => true, 'message' => 'Air quality data updated'];
        } catch (\Exception $e) {
            $this->log('Error fetching udara: ' . $e->getMessage(), 'error');
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Send alert notifications
     */
    public function sendAlerts()
    {
        $config = require APP_PATH . '/Config/telegram.php';
        $alerts = [];
        
        // Check for earthquake alerts
        if ($config['alert_settings']['gempa']['enabled']) {
            $gempaModel = new GempaModel();
            $recentGempa = $gempaModel->getRecent(10); // Last 10 minutes
            
            foreach ($recentGempa as $gempa) {
                if ($gempa['magnitude'] >= $config['alert_settings']['gempa']['min_magnitude']) {
                    $alerts[] = $this->formatGempaAlert($gempa);
                }
            }
        }
        
        // Send alerts via Telegram
        foreach ($alerts as $alert) {
            $this->telegramService->sendMessage($alert);
        }
        
        $this->log('Sent ' . count($alerts) . ' alerts');
        return ['success' => true, 'alerts_sent' => count($alerts)];
    }
    
    /**
     * Format earthquake alert message
     */
    protected function formatGempaAlert($gempa)
    {
        return "🚨 <b>PERINGATAN GEMPA BUMI</b>\n\n"
            . "📍 Lokasi: {$gempa['wilayah']}\n"
            . "📏 Magnitudo: {$gempa['magnitude']} SR\n"
            . "📐 Kedalaman: {$gempa['kedalaman']}\n"
            . "🕐 Waktu: {$gempa['tanggal']} {$gempa['jam']}\n"
            . "🌊 Potensi: {$gempa['potensi']}\n\n"
            . "#Gempa #BMKG #KTIMonitor";
    }
    
    /**
     * Log message to file
     */
    protected function log($message, $level = 'info')
    {
        $logFile = STORAGE_PATH . '/logs/cron.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$level}] {$message}\n";
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
