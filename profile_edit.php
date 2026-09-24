<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

require_login();

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'SELECT User_ID, Username, Name
     FROM `User`
     WHERE User_ID = ?'
);

$stmt->execute([$userId]);

$user = $stmt->fetch();

if (!$user) {
    exit('ไม่พบข้อมูลผู้ใช้');
}
?>

<!doctype html>
<html lang="th">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>แก้ไขโปรไฟล์ | LRU PrintFinder</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="admin.css">

</head>

<body class="admin-body">

<?php require __DIR__ . '/navbar.php'; ?>

<main class="container py-5">

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">

        <div class="col-md-7 col-lg-6">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <h1 class="h4 mb-4">
                        แก้ไขข้อมูลส่วนตัว
                    </h1>

                    <form
                        action="profile_update.php"
                        method="POST"
                        id="profileForm"
                        novalidate
                    >
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

                        <div class="mb-3">

                            <label class="form-label">
                                ชื่อผู้ใช้
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($user['Username'], ENT_QUOTES, 'UTF-8') ?>"
                                readonly
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                ชื่อ - นามสกุล
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?= htmlspecialchars($user['Name'], ENT_QUOTES, 'UTF-8') ?>"
                                maxlength="100"
                                required
                            >

                        </div>


                        <hr>


                        <h2 class="h6 mb-3">
                            เปลี่ยนรหัสผ่าน
                        </h2>

                        <p class="text-muted small">
                            หากไม่ต้องการเปลี่ยนรหัสผ่าน ให้เว้นช่องนี้ว่าง
                        </p>


                        <div class="mb-3">

                            <label class="form-label">
                                รหัสผ่านปัจจุบัน
                            </label>

                            <input
                                type="password"
                                name="current_password"
                                class="form-control"
                                autocomplete="current-password"
                            >

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                รหัสผ่านใหม่
                            </label>

                            <input
                                type="password"
                                name="new_password"
                                class="form-control"
                                minlength="8"
                                autocomplete="new-password"
                            >

                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="confirm_password">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="8" autocomplete="new-password">
                        </div>

                        <div id="formError" class="alert alert-danger d-none" role="alert"></div>


                        <div class="d-flex gap-2">

                            <a
                                href="profile.php"
                                class="btn btn-secondary"
                            >
                                ยกเลิก
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                บันทึกข้อมูล
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $('#profileForm').on('submit', function (event) {
        const form = this;
        const name = $.trim(form.name.value);
        const currentPassword = form.current_password.value;
        const newPassword = form.new_password.value;
        const confirmPassword = form.confirm_password.value;
        let error = '';

        if (!name) {
            error = 'กรุณากรอกชื่อ-นามสกุล';
        } else if (newPassword || confirmPassword || currentPassword) {
            if (!currentPassword) error = 'กรุณากรอกรหัสผ่านปัจจุบัน';
            else if (newPassword.length < 8) error = 'รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัวอักษร';
            else if (newPassword !== confirmPassword) error = 'รหัสผ่านใหม่และการยืนยันไม่ตรงกัน';
        }

        if (error) {
            event.preventDefault();
            $('#formError').text(error).removeClass('d-none');
            return;
        }

        $(form).find('button[type="submit"]').prop('disabled', true);
    });
</script>

</body>
</html>