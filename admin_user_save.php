<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin_system.php'); exit; }
$userId = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);
$username = trim($_POST['username'] ?? '');
$name = trim($_POST['name'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'user';

if ($username === '' || $name === '' || !in_array($role, ['admin', 'user'], true) || (!$userId && strlen($password) < 8)) {
    $_SESSION['flash_error'] = 'ข้อมูลผู้ใช้งานไม่ครบถ้วนหรือไม่ถูกต้อง';
    header('Location: admin_system.php'); exit;
}
try {
    if ($userId && $userId > 0) {
        $sql = 'UPDATE `User` SET Username = ?, Name = ?, Role = ?';
        $params = [$username, $name, $role];
        if ($password !== '') { $sql .= ', Password = ?'; $params[] = password_hash($password, PASSWORD_BCRYPT); }
        $sql .= ' WHERE User_ID = ?'; $params[] = $userId;
        $pdo->prepare($sql)->execute($params);
    } else {
        $pdo->prepare('INSERT INTO `User` (Username, Password, Name, Role) VALUES (?, ?, ?, ?)')->execute([$username, password_hash($password, PASSWORD_BCRYPT), $name, $role]);
    }
    $_SESSION['flash_success'] = 'บันทึกข้อมูลผู้ใช้สำเร็จ';
} catch (PDOException $exception) { $_SESSION['flash_error'] = $exception->getCode() === '23000' ? 'ชื่อผู้ใช้นี้มีอยู่แล้ว' : 'ไม่สามารถบันทึกข้อมูลผู้ใช้ได้'; }
header('Location: admin_system.php'); exit;