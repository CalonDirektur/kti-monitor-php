<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Baca env
$envPath = __DIR__ . '/../../.env';
if (!file_exists($envPath)) {
    die('File .env tidak ditemukan di: ' . $envPath);
}

$env = parse_ini_file($envPath);
if ($env === false) {
    die('Gagal membaca file .env');
}

// Debug sementara (hapus nanti)
// var_dump($env);

// Koneksi DB
$conn = new mysqli(
    $env['DB_HOST'],
    $env['DB_USER'],
    $env['DB_PASS'],
    $env['DB_NAME']
);

// Cek error koneksi
if ($conn->connect_error) {
    die('Koneksi DB gagal: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
