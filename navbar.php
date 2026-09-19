<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$isLoggedIn = !empty($_SESSION['logged_in']);
$name = htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $isLoggedIn ? 'index.php' : 'login.php' ?>">LRU PrintFinder</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="เปิดเมนู">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <?php if (!$isLoggedIn): ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">เข้าสู่ระบบ</a></li>
                    <li class="nav-item"><a class="btn btn-light btn-sm" href="register.php">สมัครสมาชิก</a></li>
                <?php else: ?>
                    <li class="nav-item"><span class="navbar-text text-white">ยินดีต้อนรับ, <?= $name ?></span></li>
                    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">จัดการระบบ</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="logout.php">ออกจากระบบ</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
