<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

$storeId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$isEdit = $storeId !== false && $storeId !== null && $storeId > 0;
$store = [
    'Store_Name' => '',
    'Description' => '',
    'Address' => '',
    'Latitude' => '',
    'Longitude' => '',
];
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_error']);

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT Store_ID, Store_Name, Description, Address, Latitude, Longitude FROM `Store` WHERE Store_ID = ?');
    $stmt->execute([$storeId]);
    $store = $stmt->fetch();
    if (!$store) {
        $_SESSION['flash_error'] = 'ไม่พบข้อมูลร้านค้าที่ต้องการแก้ไข';
        header('Location: admin_stores.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $isEdit ? 'แก้ไขร้านค้า' : 'เพิ่มร้านค้าใหม่' ?> | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h3 mb-4"><?= $isEdit ? 'แก้ไขข้อมูลร้านค้า' : 'เพิ่มร้านค้าใหม่' ?></h1>
                        <?php if ($flashError): ?>
                            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <div id="formError" class="alert alert-danger d-none" role="alert"></div>
                        <form id="storeForm" action="<?= $isEdit ? 'store_update.php' : 'store_create.php' ?>" method="post" novalidate>
                            <?php if ($isEdit): ?>
                                <input type="hidden" name="store_id" value="<?= (int) $storeId ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label for="store_name" class="form-label">ชื่อร้านค้า</label>
                                <input type="text" class="form-control" id="store_name" name="store_name" maxlength="150" value="<?= htmlspecialchars($store['Store_Name'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">คำอธิบาย/จุดเด่น</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($store['Description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">ที่อยู่และข้อมูลการเดินทาง</label>
                                <textarea class="form-control" id="address" name="address" rows="4" required><?= htmlspecialchars($store['Address'], ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="latitude" class="form-label">ละติจูด</label>
                                    <input type="number" step="any" min="-90" max="90" class="form-control" id="latitude" name="latitude" value="<?= htmlspecialchars($store['Latitude'], ENT_QUOTES, 'UTF-8') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="longitude" class="form-label">ลองจิจูด</label>
                                    <input type="number" step="any" min="-180" max="180" class="form-control" id="longitude" name="longitude" value="<?= htmlspecialchars($store['Longitude'], ENT_QUOTES, 'UTF-8') ?>" required>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <button type="button" id="getLocation" class="btn btn-outline-secondary">ดึงพิกัดปัจจุบัน</button>
                                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'บันทึกการแก้ไข' : 'บันทึกร้านค้า' ?></button>
                                <a href="admin_stores.php" class="btn btn-light">ยกเลิก</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            $('#getLocation').on('click', function () {
                const $button = $(this);
                if (!navigator.geolocation) {
                    $('#formError').text('เบราว์เซอร์ไม่รองรับการระบุตำแหน่ง').removeClass('d-none');
                    return;
                }
                $button.prop('disabled', true).text('กำลังดึงพิกัด...');
                navigator.geolocation.getCurrentPosition(function (position) {
                    $('#latitude').val(position.coords.latitude.toFixed(8));
                    $('#longitude').val(position.coords.longitude.toFixed(8));
                    $('#formError').addClass('d-none').text('');
                    $button.prop('disabled', false).text('ดึงพิกัดปัจจุบัน');
                }, function () {
                    $('#formError').text('ไม่สามารถดึงพิกัดได้ กรุณาอนุญาตการเข้าถึงตำแหน่งหรือกรอกพิกัดเอง').removeClass('d-none');
                    $button.prop('disabled', false).text('ดึงพิกัดปัจจุบัน');
                });
            });

            $('#storeForm').on('submit', function (event) {
                const latitude = Number($('#latitude').val());
                const longitude = Number($('#longitude').val());
                let message = '';
                if (!$.trim($('#store_name').val()) || !$.trim($('#address').val()) || $('#latitude').val() === '' || $('#longitude').val() === '') {
                    message = 'กรุณากรอกชื่อร้าน ที่อยู่ และพิกัดให้ครบถ้วน';
                } else if (!Number.isFinite(latitude) || latitude < -90 || latitude > 90) {
                    message = 'Latitude ต้องอยู่ระหว่าง -90 ถึง 90';
                } else if (!Number.isFinite(longitude) || longitude < -180 || longitude > 180) {
                    message = 'Longitude ต้องอยู่ระหว่าง -180 ถึง 180';
                }
                if (message) {
                    event.preventDefault();
                    $('#formError').text(message).removeClass('d-none');
                }
            });
        });
    </script>
</body>
</html>
