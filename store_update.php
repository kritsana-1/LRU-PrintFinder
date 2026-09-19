<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_stores.php');
    exit;
}

$storeId = filter_var($_POST['store_id'] ?? null, FILTER_VALIDATE_INT);
$storeName = trim($_POST['store_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$address = trim($_POST['address'] ?? '');
$latitude = filter_var($_POST['latitude'] ?? null, FILTER_VALIDATE_FLOAT);
$longitude = filter_var($_POST['longitude'] ?? null, FILTER_VALIDATE_FLOAT);

if ($storeId === false || $storeId < 1 || $storeName === '' || $address === '' || $latitude === false || $longitude === false || $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
    $_SESSION['flash_error'] = 'ข้อมูลไม่ครบถ้วนหรือพิกัดไม่ถูกต้อง';
    header('Location: admin_stores.php');
    exit;
}

$stmt = $pdo->prepare('UPDATE `Store` SET Store_Name = ?, Description = ?, Address = ?, Latitude = ?, Longitude = ? WHERE Store_ID = ?');
$stmt->execute([$storeName, $description !== '' ? $description : null, $address, $latitude, $longitude, $storeId]);

$_SESSION['flash_success'] = $stmt->rowCount() > 0 ? 'แก้ไขข้อมูลร้านค้าสำเร็จ' : 'ไม่พบข้อมูลร้านค้าหรือไม่มีข้อมูลเปลี่ยนแปลง';
header('Location: admin_stores.php');
exit;
