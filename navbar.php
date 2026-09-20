<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$isLoggedIn = !empty($_SESSION['logged_in']);
$name = htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark admin-navbar py-2">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="<?= $isLoggedIn ? 'index.php' : 'login.php' ?>">
            LRU PrintFinder
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="เปิดเมนู">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mt-2 mt-lg-0">
                <?php if (!$isLoggedIn): ?>
                    <!-- ชุดปุ่มมุมขวาบนเมื่อยังไม่ได้เข้าสู่ระบบ -->
                    <li class="nav-item">
                        <a class="btn btn-primary px-3" href="login.php">
                            เข้าสู่ระบบ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light px-3" href="register.php">
                            สมัครสมาชิก
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-lg-2">
                        <span class="navbar-text text-white">ยินดีต้อนรับ, <?= $name ?></span>
                    </li>
                    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">แดชบอร์ด</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_system.php">จัดการผู้ใช้</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_stores.php">จัดการร้านค้า</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_locations.php">ตำแหน่งที่ตั้ง</a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="btn btn-danger btn-sm logout-link" href="logout.php">ออกจากระบบ</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>