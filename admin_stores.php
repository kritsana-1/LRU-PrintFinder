<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

$stmt = $pdo->query('SELECT Store_ID, Store_Name, Description, Address, Latitude, Longitude FROM `Store` ORDER BY Store_ID DESC');
$stores = $stmt->fetchAll();
$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการข้อมูลร้านค้า | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="h3 mb-1">จัดการข้อมูลร้านค้าและสถานที่ตั้ง</h1>
                <p class="text-muted mb-0">Process 2.1 และ 2.3</p>
            </div>
            <a class="btn btn-primary" href="store_form.php">เพิ่มร้านค้าใหม่</a>
        </div>

        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="ปิด"></button>
            </div>
        <?php endif; ?>
        <?php if ($flashError): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="ปิด"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>รหัสร้าน</th>
                                <th>ชื่อร้าน</th>
                                <th>ที่อยู่</th>
                                <th>พิกัด (Lat, Long)</th>
                                <th class="text-nowrap">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$stores): ?>
                                <tr><td colspan="6" class="text-center text-muted py-4">ยังไม่มีข้อมูลร้านค้า</td></tr>
                            <?php endif; ?>
                            <?php foreach ($stores as $index => $store): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= (int) $store['Store_ID'] ?></td>
                                    <td><?= htmlspecialchars($store['Store_Name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= nl2br(htmlspecialchars($store['Address'], ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td class="text-nowrap"><?= htmlspecialchars($store['Latitude'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($store['Longitude'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-outline-primary" href="store_form.php?id=<?= (int) $store['Store_ID'] ?>">แก้ไข</a>
                                        <button class="btn btn-sm btn-outline-danger js-delete-store" type="button" data-id="<?= (int) $store['Store_ID'] ?>" data-name="<?= htmlspecialchars($store['Store_Name'], ENT_QUOTES, 'UTF-8') ?>">ลบ</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="deleteModalLabel">ยืนยันการลบร้านค้า</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">ต้องการลบร้านค้า <strong id="deleteStoreName"></strong> ใช่หรือไม่?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <form action="store_delete.php" method="post">
                        <input type="hidden" name="store_id" id="deleteStoreId">
                        <button type="submit" class="btn btn-danger">ยืนยันการลบ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function () {
            const deleteModal = new bootstrap.Modal('#deleteModal');
            $('.js-delete-store').on('click', function () {
                $('#deleteStoreId').val($(this).data('id'));
                $('#deleteStoreName').text($(this).data('name'));
                deleteModal.show();
            });
        });
    </script>
</body>
</html>
