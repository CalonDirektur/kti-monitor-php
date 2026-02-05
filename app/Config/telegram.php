<?php
function telegram_send($text) {
    $token  = getenv('TELEGRAM_TOKEN');
    $chatId = getenv('TELEGRAM_CHAT_ID');

    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    file_get_contents($url . '?' . http_build_query([
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'Markdown'
    ]));
}
