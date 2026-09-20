<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

$userId = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $userId === false || $userId < 1 || (int) $userId === (int) ($_SESSION['user_id'] ?? 0)) {
    $_SESSION['flash_error'] = 'ไม่สามารถลบบัญชีนี้ได้';
} else {
    $stmt = $pdo->prepare('DELETE FROM `User` WHERE User_ID = ?');
    $stmt->execute([$userId]);
    $_SESSION['flash_success'] = $stmt->rowCount() ? 'ลบบัญชีผู้ใช้สำเร็จ' : 'ไม่พบบัญชีผู้ใช้';
}
header('Location: admin_system.php'); exit;