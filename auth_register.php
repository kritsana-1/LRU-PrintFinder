<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$name = trim($_POST['name'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$_SESSION['old_register_username'] = $username;
$_SESSION['old_register_name'] = $name;

if ($username === '' || $name === '' || $password === '' || $confirmPassword === '') {
    $_SESSION['flash_error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
} elseif (strlen($username) > 50 || strlen($name) > 100) {
    $_SESSION['flash_error'] = 'ชื่อผู้ใช้หรือชื่อแสดงผลยาวเกินกำหนด';
} elseif (strlen($password) < 8) {
    $_SESSION['flash_error'] = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
} elseif ($password !== $confirmPassword) {
    $_SESSION['flash_error'] = 'ยืนยันรหัสผ่านไม่ตรงกัน';
} else {
    $checkStmt = $pdo->prepare('SELECT User_ID FROM `User` WHERE Username = ?');
    $checkStmt->execute([$username]);

    if ($checkStmt->fetch()) {
        $_SESSION['flash_error'] = 'ชื่อผู้ใช้นี้มีอยู่แล้ว';
    } else {
        try {
            $insertStmt = $pdo->prepare('INSERT INTO `User` (Username, Password, Name, Role) VALUES (?, ?, ?, ?)');
            $insertStmt->execute([$username, password_hash($password, PASSWORD_BCRYPT), $name, 'user']);
            unset($_SESSION['old_register_username'], $_SESSION['old_register_name']);
            $_SESSION['flash_success'] = 'สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ';
            header('Location: login.php');
            exit;
        } catch (PDOException $exception) {
            $_SESSION['flash_error'] = 'ไม่สามารถสมัครสมาชิกได้ กรุณาลองใหม่อีกครั้ง';
        }
    }
}

header('Location: register.php');
exit;