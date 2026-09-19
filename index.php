<?php
require_once __DIR__ . '/check_auth.php';
require_login();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าหลัก | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="container py-5">
        <h1 class="h3">หน้าหลักผู้ใช้งาน</h1>
        <p class="text-muted">คุณเข้าสู่ระบบในฐานะผู้ใช้งานทั่วไปแล้ว</p>
    </main>
</body>
</html>
