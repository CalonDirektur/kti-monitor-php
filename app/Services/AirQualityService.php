<?php

namespace App\Services;

use App\Helpers\HttpClient;

class AirQualityService
{
    protected $httpClient;
    protected $apiUrl;
    protected $apiKey;
    
    public function __construct()
    {
        $this->httpClient = new HttpClient();
        $this->apiUrl = getenv('AIR_QUALITY_API_URL') ?: '';
        $this->apiKey = getenv('AIR_QUALITY_API_KEY') ?: '';
    }
    
    public function getData($location = null)
    {
        if (empty($this->apiUrl)) {
            return $this->getMockData();
        }
        
        $params = ['key' => $this->apiKey];
        if ($location) {
            $params['location'] = $location;
        }
        
        return $this->httpClient->get($this->apiUrl, $params);
    }
    
    public function getByCoordinates($lat, $lng)
    {
        $params = [
            'key' => $this->apiKey,
            'lat' => $lat,
            'lng' => $lng,
        ];
        
        return $this->httpClient->get($this->apiUrl, $params);
    }
    
    protected function getMockData()
    {
        return [
            ['lokasi' => 'Jakarta', 'aqi' => 85, 'pm25' => 35, 'pm10' => 50],
            ['lokasi' => 'Surabaya', 'aqi' => 65, 'pm25' => 25, 'pm10' => 40],
            ['lokasi' => 'Bandung', 'aqi' => 55, 'pm25' => 20, 'pm10' => 35],
        ];
    }
}
