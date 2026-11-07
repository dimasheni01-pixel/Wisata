<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) session_start();

$host = 'localhost';
$db   = 'booking_wisata_baru';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal. Pastikan MySQL aktif dan database booking_wisata_baru sudah diimpor melalui phpMyAdmin. Detail: ' . htmlspecialchars($e->getMessage()));
}
