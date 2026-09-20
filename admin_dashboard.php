<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

$storeCount = (int) $pdo->query('SELECT COUNT(*) FROM `Store`')->fetchColumn();
$userCount = (int) $pdo->query('SELECT COUNT(*) FROM `User`')->fetchColumn();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการระบบ | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/navbar.php'; ?>
    <div class="admin-shell d-lg-flex">
        <?php require __DIR__ . '/admin_sidebar.php'; ?>
        <main class="admin-content p-3 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">แดชบอร์ดผู้ดูแลระบบ</h1>
                    <p class="text-muted mb-0">หน้านี้เปิดให้เฉพาะผู้ใช้ที่มี role เป็น admin</p>
                </div>
                <a class="btn btn-primary" href="admin_stores.php"><i class="bi bi-shop me-2"></i>จัดการข้อมูลร้านค้าและสถานที่ตั้ง</a>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <section class="card admin-card h-100"><div class="card-body d-flex align-items-center gap-3"><i class="bi bi-shop display-5 text-primary"></i><div><div class="text-muted">ร้านค้าทั้งหมด</div><div class="display-5 fw-bold"><?= $storeCount ?></div></div></div></section>
                </div>
                <div class="col-md-6">
                    <section class="card admin-card h-100"><div class="card-body d-flex align-items-center gap-3"><i class="bi bi-people display-5 text-success"></i><div><div class="text-muted">ผู้ใช้ทั้งหมด</div><div class="display-5 fw-bold"><?= $userCount ?></div></div></div></section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
