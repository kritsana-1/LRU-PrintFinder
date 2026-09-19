<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_stores.php');
    exit;
}

$storeId = filter_var($_POST['store_id'] ?? null, FILTER_VALIDATE_INT);
if ($storeId === false || $storeId < 1) {
    $_SESSION['flash_error'] = 'รหัสร้านค้าไม่ถูกต้อง';
    header('Location: admin_stores.php');
    exit;
}

try {
    $stmt = $pdo->prepare('DELETE FROM `Store` WHERE Store_ID = ?');
    $stmt->execute([$storeId]);
    $_SESSION['flash_success'] = $stmt->rowCount() > 0 ? 'ลบข้อมูลร้านค้าสำเร็จ' : 'ไม่พบข้อมูลร้านค้าที่ต้องการลบ';
} catch (PDOException $exception) {
    // รองรับกรณีมีตารางอื่นอ้างอิงร้านค้าอยู่ด้วย Foreign Key
    $_SESSION['flash_error'] = 'ไม่สามารถลบร้านค้านี้ได้ เนื่องจากมีข้อมูลอื่นเชื่อมโยงอยู่';
}

header('Location: admin_stores.php');
exit;
