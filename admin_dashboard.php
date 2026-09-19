<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการระบบ | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="container py-5">
        <h1 class="h3">แดชบอร์ดผู้ดูแลระบบ</h1>
        <p class="text-muted">หน้านี้เปิดให้เฉพาะผู้ใช้ที่มี role เป็น admin</p>
    </main>
</body>
</html>
