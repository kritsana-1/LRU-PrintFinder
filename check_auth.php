<?php
// ไฟล์นี้ควรถูก include เป็นคำสั่งแรกของหน้าที่ต้องการการยืนยันตัวตน
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function require_login(): void
{
    if (empty($_SESSION['logged_in'])) {
        header('Location: login.php');
        exit;
    }
}

function check_admin(): void
{
    require_login();

    if (($_SESSION['role'] ?? null) !== 'admin') {
        http_response_code(403);
        exit('ไม่มีสิทธิ์เข้าถึงหน้านี้');
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}
