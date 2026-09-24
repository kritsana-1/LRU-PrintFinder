<?php

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    $_SESSION['flash_error'] = 'คำขอไม่ถูกต้องหรือหมดอายุ กรุณาลองใหม่';
    header('Location: profile.php');
    exit;
}

$userId = $_SESSION['user_id'];

$name = trim($_POST['name'] ?? '');

$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';


// ตรวจสอบข้อมูลพื้นฐาน
if ($name === '' || mb_strlen($name) > 100) {

    $_SESSION['flash_error'] =
        'กรุณากรอกชื่อ-นามสกุลให้ถูกต้อง';

    header('Location: profile.php');
    exit;
}


// เริ่ม UPDATE ข้อมูล
if ($newPassword !== '' || $currentPassword !== '' || $confirmPassword !== '') {

    $stmt = $pdo->prepare('SELECT Password FROM `User` WHERE User_ID = ?');
    $stmt->execute([$userId]);
    $storedPassword = $stmt->fetchColumn();

    if (!$storedPassword || !password_verify($currentPassword, $storedPassword)) {
        $_SESSION['flash_error'] = 'รหัสผ่านปัจจุบันไม่ถูกต้อง';
        header('Location: profile.php');
        exit;
    }

    if (strlen($newPassword) < 8 || $newPassword !== $confirmPassword) {

        $_SESSION['flash_error'] =
            'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน';

        header('Location: profile.php');
        exit;
    }


    // เข้ารหัส Password
    $hashedPassword = password_hash(
        $newPassword,
        PASSWORD_BCRYPT
    );


    $stmt = $pdo->prepare(
        'UPDATE `User`
         SET Name = ?,
             Password = ?
         WHERE User_ID = ?'
    );

    $stmt->execute([
        $name,
        $hashedPassword,
        $userId
    ]);

} else {

    // ไม่ได้เปลี่ยน Password
    $stmt = $pdo->prepare(
        'UPDATE `User`
         SET Name = ?
         WHERE User_ID = ?'
    );

    $stmt->execute([
        $name,
        $userId
    ]);
}


// อัปเดต Session
$_SESSION['name'] = $name;


// แจ้งผลสำเร็จ
$_SESSION['flash_success'] =
    'แก้ไขข้อมูลส่วนตัวเรียบร้อยแล้ว';

header('Location: profile.php');
exit;