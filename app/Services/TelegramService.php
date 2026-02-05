<?php

namespace App\Services;

use App\Helpers\HttpClient;

class TelegramService
{
    protected $config;
    protected $httpClient;
    protected $apiUrl;
    
    public function __construct()
    {
        $this->config = require APP_PATH . '/Config/telegram.php';
        $this->httpClient = new HttpClient();
        $this->apiUrl = $this->config['api_url'] . $this->config['bot_token'];
    }
    
    public function sendMessage($text, $chatId = null)
    {
        $chatId = $chatId ?? $this->config['chat_id'];
        
        return $this->httpClient->post($this->apiUrl . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $this->config['parse_mode'],
        ]);
    }
    
    public function sendPhoto($photoUrl, $caption = '', $chatId = null)
    {
        $chatId = $chatId ?? $this->config['chat_id'];
        
        return $this->httpClient->post($this->apiUrl . '/sendPhoto', [
            'chat_id' => $chatId,
            'photo' => $photoUrl,
            'caption' => $caption,
            'parse_mode' => $this->config['parse_mode'],
        ]);
    }
    
    public function handleWebhook($update)
    {
        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'] ?? '';
            
            // Handle commands
            if (strpos($text, '/') === 0) {
                $this->handleCommand($text, $chatId);
            }
        }
    }
    
    protected function handleCommand($command, $chatId)
    {
        switch ($command) {
            case '/start':
                $this->sendMessage("Selamat datang di KTI Monitor Bot!", $chatId);
                break;
            case '/gempa':
                $this->sendMessage("Mengambil data gempa terbaru...", $chatId);
                break;
            case '/cuaca':
                $this->sendMessage("Mengambil data cuaca...", $chatId);
                break;
            default:
                $this->sendMessage("Perintah tidak dikenali.", $chatId);
        }
    }
}
