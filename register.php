<?php
session_start();

if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_error']);
$oldUsername = $_SESSION['old_register_username'] ?? '';
$oldName = $_SESSION['old_register_name'] ?? '';
unset($_SESSION['old_register_username'], $_SESSION['old_register_name']);
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
                <?php if ($flashError): ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <div id="registerError" class="alert alert-danger d-none" role="alert"></div>
                <form id="registerForm" action="auth_register.php" method="post" novalidate>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            $('#registerForm').on('submit', function (event) {
                const username = $.trim($('#username').val());
                const name = $.trim($('#name').val());
                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();
                let message = '';
                if (!username || !name || !password || !confirmPassword) {
                    message = 'กรุณากรอกข้อมูลให้ครบถ้วน';
                } else if (password.length < 8) {
                    message = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
                } else if (password !== confirmPassword) {
                    message = 'ยืนยันรหัสผ่านไม่ตรงกัน';
                }
                if (message) {
                    event.preventDefault();
                    $('#registerError').text(message).removeClass('d-none');
                }
            });
        });
    </script>
</body>
</html>
