<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['flash_error'] = 'กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน';
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT User_ID, Username, Password, Name, Role FROM `User` WHERE Username = ?'
);
$stmt->execute([$username]);
$row = $stmt->fetch();

if (!$row || !password_verify($password, $row['Password'])) {
    $_SESSION['flash_error'] = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
    header('Location: login.php');
    exit;
}

// ป้องกัน session fixation ก่อนเก็บข้อมูลผู้ใช้
session_regenerate_id(true);
$_SESSION['user_id'] = $row['User_ID'];
$_SESSION['username'] = $row['Username'];
$_SESSION['name'] = $row['Name'];
$_SESSION['role'] = $row['Role'];
$_SESSION['logged_in'] = true;

if ($row['Role'] === 'admin') {
    header('Location: admin_dashboard.php');
} else {
    header('Location: index.php');
}
exit;
