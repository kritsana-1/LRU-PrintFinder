<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูลให้ตรงกับเครื่องของคุณ
$host = '127.0.0.1';
$dbname = 'lru_printfinder';
$dbUser = 'root';
$dbPassword = '';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // ใช้ native prepared statements เพื่อให้ค่าผู้ใช้ไม่ถูกนำไปต่อเป็น SQL
    $pdo = new PDO($dsn, $dbUser, $dbPassword, $options);
} catch (PDOException $exception) {
    http_response_code(500);
    exit('ไม่สามารถเชื่อมต่อฐานข้อมูลได้ กรุณาตรวจสอบการตั้งค่าใน db.php');
}
