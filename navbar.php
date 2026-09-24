<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$isLoggedIn = !empty($_SESSION['logged_in']);
$name = htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8');

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark admin-navbar py-2">

    <div class="container-fluid px-4">

        <a
            class="navbar-brand fw-bold"
            href="<?= $isLoggedIn ? 'index.php' : 'login.php' ?>"
        >
            <i class="bi bi-printer me-1"></i>
            LRU PrintFinder
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="เปิดเมนู"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mt-2 mt-lg-0">

                <?php if (!$isLoggedIn): ?>

                    <li class="nav-item">
                        <a
                            class="btn btn-primary px-3"
                            href="login.php"
                        >
                            เข้าสู่ระบบ
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="btn btn-outline-light px-3"
                            href="register.php"
                        >
                            สมัครสมาชิก
                        </a>
                    </li>

                <?php else: ?>

                    <li class="nav-item me-lg-2">
                        <span class="navbar-text text-white">
                            ยินดีต้อนรับ, <?= $name ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>"
                            href="index.php"
                        >
                            <i class="bi bi-house-door"></i>
                            หน้าหลัก
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="btn btn-primary btn-sm <?= $currentPage === 'profile.php' ? 'active' : '' ?>"
                            href="profile.php"
                            aria-current="<?= $currentPage === 'profile.php' ? 'page' : 'false' ?>"
                        >
                            <i class="bi bi-person-circle"></i>
                            โปรไฟล์ของฉัน
                        </a>
                    </li>

                    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>

                        <li class="nav-item">
                            <a
                                class="nav-link <?= $currentPage === 'admin_dashboard.php' ? 'active' : '' ?>"
                                href="admin_dashboard.php"
                            >
                                แดชบอร์ด
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link <?= $currentPage === 'admin_system.php' ? 'active' : '' ?>"
                                href="admin_system.php"
                            >
                                <i class="bi bi-people"></i>
                                จัดการผู้ใช้
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link <?= $currentPage === 'admin_stores.php' ? 'active' : '' ?>"
                                href="admin_stores.php"
                            >
                                <i class="bi bi-shop"></i>
                                จัดการร้านค้า
                            </a>
                        </li>

                        <!--
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="admin_locations.php"
                            >
                                ตำแหน่งที่ตั้ง
                            </a>
                        </li>
                        -->

                    <?php endif; ?>

                    <li class="nav-item">
                        <a
                            class="btn btn-danger btn-sm logout-link"
                            href="logout.php"
                        >
                            ออกจากระบบ
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>