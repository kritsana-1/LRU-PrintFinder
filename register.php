<?php
session_start();
require_once __DIR__ . '/db.php';

if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = null;
$oldUsername = '';
$oldName = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldUsername = trim($_POST['username'] ?? '');
    $oldName = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($oldUsername === '' || $oldName === '' || $password === '') {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } elseif (strlen($oldUsername) > 50 || strlen($oldName) > 100) {
        $error = 'ชื่อผู้ใช้หรือชื่อแสดงผลยาวเกินกำหนด';
    } elseif (strlen($password) < 8) {
        $error = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
    } elseif ($password !== $confirmPassword) {
        $error = 'ยืนยันรหัสผ่านไม่ตรงกัน';
    } else {
        $checkStmt = $pdo->prepare('SELECT User_ID FROM `User` WHERE Username = ?');
        $checkStmt->execute([$oldUsername]);

        if ($checkStmt->fetch()) {
            $error = 'ชื่อผู้ใช้นี้มีอยู่แล้ว';
        } else {
            $insertStmt = $pdo->prepare(
                'INSERT INTO `User` (Username, Password, Name, Role) VALUES (?, ?, ?, ?)'
            );
            $insertStmt->execute([
                $oldUsername,
                password_hash($password, PASSWORD_BCRYPT),
                $oldName,
                'user',
            ]);

            $_SESSION['flash_success'] = 'สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ';
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สมัครสมาชิก | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="card shadow-sm border-0" style="max-width: 500px; width: 100%;">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-4">สมัครสมาชิก</h1>
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <form action="register.php" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">ชื่อ</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="100" value="<?= htmlspecialchars($oldName, ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="username" name="username" maxlength="50" value="<?= htmlspecialchars($oldUsername, ENT_QUOTES, 'UTF-8') ?>" required autocomplete="username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="8" required autocomplete="new-password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8" required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
                </form>
                <p class="text-center mt-4 mb-0"><a href="login.php">กลับไปหน้าเข้าสู่ระบบ</a></p>
            </div>
        </div>
    </main>
</body>
</html>
