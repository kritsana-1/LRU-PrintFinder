<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$isLoggedIn = !empty($_SESSION['logged_in']);
$role = $_SESSION['role'] ?? '';
$stores = $pdo->query('SELECT Store_ID, Store_Name, Description, Address, Latitude, Longitude FROM `Store` ORDER BY Store_Name')->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าหลัก | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">ค้นหาร้านปริ้นใกล้มหาวิทยาลัยราชภัฏเลย</h1>
                <p class="text-muted mb-0">ข้อมูลร้านค้าจากฐานข้อมูลของระบบ</p>
            </div>
            <?php if (!$isLoggedIn): ?>
                <!-- <div class="d-flex gap-2">
                    <a class="btn btn-primary" href="login.php">เข้าสู่ระบบ</a>
                    <a class="btn btn-outline-primary" href="register.php">สมัครสมาชิก</a>
                </div> -->
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <?php foreach ($stores as $store): ?>
                <div class="col-md-6 col-xl-4">
                    <article class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h5"><?= htmlspecialchars($store['Store_Name'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <p class="text-muted"><?= htmlspecialchars($store['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="small mb-2"><i class="bi bi-geo-alt me-1"></i><?= nl2br(htmlspecialchars($store['Address'], ENT_QUOTES, 'UTF-8')) ?></p>
                            <a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode($store['Latitude'] . ',' . $store['Longitude']) ?>">ดูตำแหน่งบนแผนที่</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
            <?php if (!$stores): ?>
                <div class="col-12"><div class="alert alert-info">ยังไม่มีข้อมูลร้านค้าในระบบ</div></div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
