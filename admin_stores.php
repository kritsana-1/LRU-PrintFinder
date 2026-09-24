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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/navbar.php'; ?>
    <div class="admin-shell d-lg-flex">
        <?php require __DIR__ . '/admin_sidebar.php'; ?>
        <main class="admin-content p-3 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Store Management</h1>
                    <p class="text-muted mb-0">Registered and verified printing store</p>
                </div>
                <a class="btn btn-primary" href="store_form.php"><i class="bi bi-plus-lg me-2"></i>Add New Store</a>
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

        <div class="card admin-card">
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-5"><label class="visually-hidden" for="storeSearch">ค้นหาร้านค้า</label><div class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search"></i></span><input type="search" class="form-control" id="storeSearch" placeholder="Search store"></div></div>
                    <div class="col-md-3"><select class="form-select" id="storeFilter" aria-label="กรองร้านค้า"><option value="">All locations</option><option value="มหาวิทยาลัยราชภัฏเลย">ใกล้มหาวิทยาลัยราชภัฏเลย</option></select></div>
                    <div class="col-md-auto"><button type="button" class="btn btn-primary" id="filterButton"><i class="bi bi-funnel me-2"></i>Filters</button></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="storeTable">
                        <thead>
                            <tr>
                                <th><input class="form-check-input" type="checkbox" id="selectAll" aria-label="เลือกทั้งหมด"></th>
                                <th>ลำดับ</th>
                                <th>รหัสร้าน</th>
                                <th>ชื่อร้าน</th>
                                <th>ที่อยู่</th>
                                <th>พิกัด (Lat, Long)</th>
                                <th>สถานะ</th>
                                <th class="text-nowrap">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$stores): ?>
                                <tr><td colspan="8" class="text-center text-muted py-4">ยังไม่มีข้อมูลร้านค้า</td></tr>
                            <?php endif; ?>
                            <?php foreach ($stores as $index => $store): ?>
                                <tr class="store-row">
                                    <td><input class="form-check-input store-check" type="checkbox" aria-label="เลือกร้านค้า"></td>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= (int) $store['Store_ID'] ?></td>
                                    <td><?= htmlspecialchars($store['Store_Name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= nl2br(htmlspecialchars($store['Address'], ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td class="text-nowrap"><?= htmlspecialchars($store['Latitude'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($store['Longitude'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><span class="badge rounded-pill text-bg-success">Active</span></td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-outline-primary" href="store_form.php?id=<?= (int) $store['Store_ID'] ?>">Edit</a>

                                        <button class="btn btn-sm btn-outline-danger js-delete-store" type="button" data-id="<?= (int) $store['Store_ID'] ?>" data-name="<?= htmlspecialchars($store['Store_Name'], ENT_QUOTES, 'UTF-8') ?>">Deactivate</button>

                                        <button class="btn btn-sm btn-outline-danger js-delete-store" type="button" data-id="<?= (int) $store['Store_ID'] ?>" data-name="<?= htmlspecialchars($store['Store_Name'], ENT_QUOTES, 'UTF-8') ?>">Delete</button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </main>
    </div>

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

            function filterStores() {
                const query = $('#storeSearch').val().toLowerCase();
                const location = $('#storeFilter').val().toLowerCase();
                $('.store-row').each(function () {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(query) && (!location || text.includes(location)));
                });
            }

            $('#storeSearch').on('input', filterStores);
            $('#filterButton').on('click', filterStores);
            $('#storeFilter').on('change', filterStores);
            $('#selectAll').on('change', function () {
                $('.store-check').prop('checked', this.checked);
            });
        });
    </script>
</body>
</html>
