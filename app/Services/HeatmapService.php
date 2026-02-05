<?php

namespace App\Services;

class HeatmapService
{
    protected $config;
    
    public function __construct()
    {
        $this->config = require APP_PATH . '/Config/map.php';
    }
    
    public function generateGempaHeatmap($data)
    {
        $points = [];
        foreach ($data as $gempa) {
            if (isset($gempa['coordinates'])) {
                $coords = explode(',', $gempa['coordinates']);
                $points[] = [
                    'lat' => (float) $coords[0],
                    'lng' => (float) $coords[1],
                    'intensity' => $this->calculateIntensity($gempa['magnitude']),
                ];
            }
        }
        return $points;
    }
    
    public function generateAqiHeatmap($data)
    {
        $points = [];
        foreach ($data as $item) {
            if (isset($item['latitude']) && isset($item['longitude'])) {
                $points[] = [
                    'lat' => (float) $item['latitude'],
                    'lng' => (float) $item['longitude'],
                    'intensity' => $item['aqi'] / 500,
                ];
            }
        }
        return $points;
    }
    
    protected function calculateIntensity($magnitude)
    {
        return min(1, $magnitude / 10);
    }
    
    public function getHeatmapConfig()
    {
        return $this->config['heatmap'];
    }
}
