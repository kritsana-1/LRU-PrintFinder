<?php
session_start();

if (!empty($_SESSION['logged_in'])) {
    header('Location: ' . (($_SESSION['role'] ?? '') === 'admin' ? 'admin_dashboard.php' : 'index.php'));
    exit;
}

$flashError = $_SESSION['flash_error'] ?? null;
$flashSuccess = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error']);
unset($_SESSION['flash_success']);
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="card shadow-sm border-0" style="max-width: 430px; width: 100%;">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-4">เข้าสู่ระบบ</h1>
                <?php if ($flashError): ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if ($flashSuccess): ?>
                    <div class="alert alert-success" role="alert"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <div id="clientError" class="alert alert-warning d-none" role="alert"></div>
                <form id="loginForm" action="auth_login.php" method="post" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="username" name="username" required autocomplete="username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
                </form>
                <p class="text-center mt-4 mb-0">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            $('#loginForm').on('submit', function (event) {
                const username = $.trim($('#username').val());
                const password = $('#password').val();
                const $error = $('#clientError');

                if (!username || !password) {
                    event.preventDefault();
                    $error.text('กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน').removeClass('d-none');
                } else {
                    $error.addClass('d-none').text('');
                }
            });
        });
    </script>
</body>
</html>
