<?php

namespace App\Controllers;

use App\Models\GempaModel;
use App\Models\CuacaModel;
use App\Models\UdaraModel;
use App\Models\BendunganModel;

class DashboardController
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
     * Display main dashboard
     */
    public function index()
    {
        $data = [
            'title' => 'Dashboard - KTI Monitor',
            'gempa' => $this->gempaModel->getLatest(5),
            'cuaca' => $this->cuacaModel->getToday(),
            'udara' => $this->udaraModel->getLatest(),
            'bendungan' => $this->bendunganModel->getAll(),
        ];
        
        $this->render('dashboard', $data);
    }
    
    /**
     * Display map view
     */
    public function map()
    {
        $mapConfig = require APP_PATH . '/Config/map.php';
        
        $data = [
            'title' => 'Peta Monitoring - KTI Monitor',
            'mapConfig' => $mapConfig,
            'gempa' => $this->gempaModel->getAll(),
            'cuaca' => $this->cuacaModel->getAllLocations(),
        ];
        
        $this->render('map', $data);
    }
    
    /**
     * Render view with layout
     */
    protected function render($view, $data = [])
    {
        extract($data);
        
        ob_start();
        include RESOURCE_PATH . "/views/{$view}.php";
        $content = ob_get_clean();
        
        include RESOURCE_PATH . '/views/layout/main.php';
    }
}
