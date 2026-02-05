<?php

namespace App\Helpers;

class HttpClient
{
    protected $timeout = 30;
    
    public function get($url, $params = [], $json = true)
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \Exception("HTTP Error: {$error}");
        }
        
        return $json ? json_decode($response, true) : $response;
    }
    
    public function post($url, $data = [], $json = true)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json ? json_encode($data) : http_build_query($data),
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $json 
                ? ['Content-Type: application/json'] 
                : ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    public function setTimeout($seconds)
    {
        $this->timeout = $seconds;
        return $this;
    }
}
