<?php

namespace App\Controllers;

use App\Models\GempaModel;
use App\Models\CuacaModel;
use App\Models\UdaraModel;
use App\Models\BendunganModel;
use App\Services\TelegramService;

class ApiController
{
    protected $gempaModel;
    protected $cuacaModel;
    protected $udaraModel;
    protected $bendunganModel;
    
    public function __construct()
    {
        $this->gempaModel = new GempaModel();
        $this->cuacaModel = new CuacaModel();
        $this->udaraModel = new UdaraModel();
        $this->bendunganModel = new BendunganModel();
    }
    
    /**
     * Get earthquake data
     */
    public function gempa()
    {
        $this->jsonResponse([
            'success' => true,
            'data' => $this->gempaModel->getAll(),
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Get weather data
     */
    public function cuaca()
    {
        $wilayah = $_GET['wilayah'] ?? null;
        
        $data = $wilayah 
            ? $this->cuacaModel->getByWilayah($wilayah)
            : $this->cuacaModel->getAll();
        
        $this->jsonResponse([
            'success' => true,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Get air quality data
     */
    public function udara()
    {
        $this->jsonResponse([
            'success' => true,
            'data' => $this->udaraModel->getAll(),
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Get dam data
     */
    public function bendungan()
    {
        $this->jsonResponse([
            'success' => true,
            'data' => $this->bendunganModel->getAll(),
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }
    
    /**
     * Handle Telegram webhook
     */
    public function webhook()
    {
        $input = file_get_contents('php://input');
        $update = json_decode($input, true);
        
        if ($update) {
            $telegramService = new TelegramService();
            $telegramService->handleWebhook($update);
        }
        
        $this->jsonResponse(['ok' => true]);
    }
    
    /**
     * Send JSON response
     */
    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
