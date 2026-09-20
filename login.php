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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-blue: #1769e0;
            --heading-blue: #123b78;
            --text-dark: #344054;
            --input-border: #d9e2f0;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            background: #f7f9fc;
            color: var(--text-dark);
        }

        .login-page {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            isolation: isolate;
        }

        .shape {
            position: absolute;
            z-index: -1;
            pointer-events: none;
            background: #b9d9ff;
            opacity: 0.28;
        }

        .shape-one {
            top: 8%;
            left: 13%;
            width: 150px;
            height: 150px;
            border-radius: 32px;
            transform: rotate(28deg);
        }

        .shape-two {
            right: 10%;
            bottom: 12%;
            width: 190px;
            height: 190px;
            border-radius: 50%;
            background: #c7e5ff;
        }

        .shape-three {
            top: 22%;
            right: 18%;
            width: 90px;
            height: 90px;
            transform: rotate(45deg);
            background: #d1e8ff;
        }

        .login-card {
            width: min(100%, 410px);
            border: 1px solid rgba(217, 226, 240, 0.8);
            border-radius: 1rem;
            box-shadow: 0 20px 55px rgba(35, 75, 125, 0.12);
        }

        .login-title {
            color: var(--heading-blue);
            letter-spacing: 0.01em;
        }

        .form-label {
            color: var(--text-dark);
            font-weight: 600;
        }

        .input-group-text,
        .form-control {
            border-color: var(--input-border);
        }

        .input-group-text {
            width: 46px;
            justify-content: center;
            color: #6b89b5;
            background: #f8fbff;
        }

        .form-control {
            min-height: 46px;
        }

        .form-control:focus {
            border-color: #76aaf0;
            box-shadow: 0 0 0 0.2rem rgba(23, 105, 224, 0.12);
        }

        .btn-primary {
            --bs-btn-bg: var(--primary-blue);
            --bs-btn-border-color: var(--primary-blue);
            --bs-btn-hover-bg: #0f57c2;
            --bs-btn-hover-border-color: #0f57c2;
            min-height: 48px;
            font-weight: 600;
        }

        .register-link {
            color: var(--primary-blue);
            font-weight: 600;
            text-decoration: none;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main class="login-page d-flex align-items-center justify-content-center px-3 py-4">
        <span class="shape shape-one" aria-hidden="true"></span>
        <span class="shape shape-two" aria-hidden="true"></span>
        <span class="shape shape-three" aria-hidden="true"></span>

        <div class="card login-card bg-white">
            <div class="card-body p-4 p-md-5">
                <h1 class="login-title h3 fw-bold text-center mb-4">เข้าสู่ระบบ</h1>
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
                        <div class="input-group">
                            <span class="input-group-text" aria-hidden="true"><i class="bi bi-key"></i></span>
                            <input type="text" class="form-control" id="username" name="username" placeholder="example_user" required autocomplete="username">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">รหัสผ่าน</label>
                        <div class="input-group">
                            <span class="input-group-text" aria-hidden="true"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2">เข้าสู่ระบบ <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                </form>
                <p class="text-center mt-4 mb-0">ยังไม่มีบัญชี? <a class="register-link" href="register.php">สมัครสมาชิก</a></p>
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
