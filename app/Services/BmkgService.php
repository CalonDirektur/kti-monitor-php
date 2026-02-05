<?php

namespace App\Services;

use App\Helpers\HttpClient;

class BmkgService
{
    protected $config;
    protected $httpClient;
    
    public function __construct()
    {
        $this->config = require APP_PATH . '/Config/bmkg.php';
        $this->httpClient = new HttpClient();
    }
    
    public function getAutoGempa()
    {
        $url = $this->config['base_url'] . $this->config['endpoints']['gempa']['autogempa'];
        $response = $this->httpClient->get($url);
        
        if ($response && isset($response['Infogempa']['gempa'])) {
            return $this->parseGempaData($response['Infogempa']['gempa']);
        }
        return null;
    }
    
    public function getGempaTerkini()
    {
        $url = $this->config['base_url'] . $this->config['endpoints']['gempa']['gempaterkini'];
        $response = $this->httpClient->get($url);
        
        if ($response && isset($response['Infogempa']['gempa'])) {
            return array_map([$this, 'parseGempaData'], $response['Infogempa']['gempa']);
        }
        return [];
    }
    
    public function getCuaca($wilayah = null)
    {
        $wilayahList = $wilayah 
            ? [$wilayah => $this->config['wilayah'][$wilayah] ?? null]
            : $this->config['wilayah'];
        
        $result = [];
        foreach ($wilayahList as $key => $file) {
            if (!$file) continue;
            $url = $this->config['base_url'] . $this->config['endpoints']['cuaca']['forecast'] . '/' . $file;
            $xmlContent = $this->httpClient->get($url, [], false);
            if ($xmlContent) {
                $parsed = $this->parseWeatherXml($xmlContent, $key);
                $result = array_merge($result, $parsed);
            }
        }
        return $result;
    }
    
    protected function parseGempaData($data)
    {
        return [
            'tanggal' => $data['Tanggal'] ?? null,
            'jam' => $data['Jam'] ?? null,
            'coordinates' => $data['Coordinates'] ?? null,
            'magnitude' => (float) ($data['Magnitude'] ?? 0),
            'kedalaman' => $data['Kedalaman'] ?? null,
            'wilayah' => $data['Wilayah'] ?? null,
            'potensi' => $data['Potensi'] ?? null,
        ];
    }
    
    protected function parseWeatherXml($xmlContent, $wilayahKey)
    {
        // XML parsing implementation
        return [];
    }
}
