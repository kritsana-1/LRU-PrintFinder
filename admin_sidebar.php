<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar p-3">
    <nav aria-label="เมนูผู้ดูแลระบบ">
        <p class="small text-uppercase text-muted fw-semibold px-2 mb-3">เมนูหลัก</p>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'admin_dashboard.php' ? 'active' : '' ?>" href="admin_dashboard.php">
                    <i class="bi bi-grid-1x2-fill"></i>แดชบอร์ด
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'admin_system.php' ? 'active' : '' ?>" href="admin_system.php">
                    <i class="bi bi-gear-fill"></i>จัดการระบบ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'admin_stores.php' ? 'active' : '' ?>" href="admin_stores.php">
                    <i class="bi bi-shop"></i>จัดการร้านค้า
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentPage === 'admin_locations.php' ? 'active' : '' ?>" href="admin_locations.php">
                    <i class="bi bi-geo-alt-fill"></i>ตำแหน่งที่ตั้ง
                </a>
            </li>
        </ul>
    </nav>
</aside>
