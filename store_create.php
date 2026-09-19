<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_stores.php');
    exit;
}

$storeName = trim($_POST['store_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$address = trim($_POST['address'] ?? '');
$latitude = filter_var($_POST['latitude'] ?? null, FILTER_VALIDATE_FLOAT);
$longitude = filter_var($_POST['longitude'] ?? null, FILTER_VALIDATE_FLOAT);

if ($storeName === '' || $address === '' || $latitude === false || $longitude === false || $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
    $_SESSION['flash_error'] = 'ข้อมูลไม่ครบถ้วนหรือพิกัดไม่ถูกต้อง';
    header('Location: store_form.php');
    exit;
}

$stmt = $pdo->prepare('INSERT INTO `Store` (Store_Name, Description, Address, Latitude, Longitude) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$storeName, $description !== '' ? $description : null, $address, $latitude, $longitude]);

$_SESSION['flash_success'] = 'เพิ่มข้อมูลร้านค้าสำเร็จ';
header('Location: admin_stores.php');
exit;
