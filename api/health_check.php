<?php
header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'message' => 'API internal KTI Monitor aktif',
    'time' => date('Y-m-d H:i:s')
]);
