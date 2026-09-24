<?php
include 'check_auth.php';
require_once __DIR__ . '/db.php';

require_login();

$userId = (int) $_SESSION['user_id'];
$stmt = $pdo->prepare(
    'SELECT User_ID, Username, Name, Role
     FROM `User`
     WHERE User_ID = ?'
);
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>

<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>โปรไฟล์ของฉัน | LRU PrintFinder</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >
    <link rel="stylesheet" href="admin.css">
</head>

<body class="admin-body profile-page">

<?php require __DIR__ . '/navbar.php'; ?>

<main class="container py-5">

    <?php if ($flashSuccess): ?><div class="alert alert-success alert-dismissible fade show" role="alert"><?= $escape($flashSuccess) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="ปิด"></button></div><?php endif; ?>
    <?php if ($flashError): ?><div class="alert alert-danger alert-dismissible fade show" role="alert"><?= $escape($flashError) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="ปิด"></button></div><?php endif; ?>

    <div class="row justify-content-center">

        <div class="col-md-7 col-lg-6">

            <div class="card admin-card border-0 profile-card">

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <div class="profile-avatar mx-auto mb-3" aria-hidden="true">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <h1 class="h4 mt-3 mb-2">
                            โปรไฟล์ของฉัน
                        </h1>
                        <p class="text-muted mb-2">จัดการข้อมูลส่วนตัวของคุณ</p>
                        <div class="profile-divider mx-auto"></div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label" for="username">
                            <i class="bi bi-person me-1 text-primary"></i>
                            ชื่อผู้ใช้
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="username"
                            value="<?= $escape($user['Username']) ?>"
                            readonly
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label" for="profileName">
                            <i class="bi bi-person-vcard me-1 text-primary"></i>
                            ชื่อ - นามสกุล
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="profileName"
                            value="<?= $escape($user['Name']) ?>"
                            readonly
                        >

                    </div>


                    <div class="mb-4">

                        <label class="form-label" for="role">
                            <i class="bi bi-shield-check me-1 text-primary"></i>
                            สิทธิ์ผู้ใช้
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="role"
                            value="<?= $escape($user['Role']) ?>"
                            readonly
                        >

                    </div>


                    <div class="d-grid">

                        <button
                            type="button"
                            class="btn btn-primary w-100 py-2"
                            data-bs-toggle="modal"
                            data-bs-target="#editProfileModal"
                        >
                            <i class="bi bi-pencil-square"></i>
                            แก้ไขข้อมูลส่วนตัว
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="profile_update.php" method="post" id="profileForm" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="editProfileModalLabel">แก้ไขข้อมูลส่วนตัว</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $escape(csrf_token()) ?>">

                    <div id="formError" class="alert alert-danger d-none" role="alert"></div>

                    <div class="mb-3">
                        <label class="form-label" for="editName">ชื่อ - นามสกุล</label>
                        <input type="text" class="form-control" id="editName" name="name" value="<?= $escape($user['Name']) ?>" maxlength="100" required>
                    </div>

                    <hr>
                    <p class="small text-muted mb-3">หากไม่ต้องการเปลี่ยนรหัสผ่าน ให้เว้นช่องรหัสผ่านไว้ หากเปลี่ยนรหัสผ่านต้องยืนยันรหัสผ่านปัจจุบัน</p>

                    <div class="mb-3">
                        <label class="form-label" for="currentPassword">รหัสผ่านปัจจุบัน</label>
                        <input type="password" class="form-control" id="currentPassword" name="current_password" autocomplete="current-password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="newPassword">รหัสผ่านใหม่</label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" minlength="8" autocomplete="new-password">
                    </div>

                    <div>
                        <label class="form-label" for="confirmPassword">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" minlength="8" autocomplete="new-password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary" id="saveProfileButton">
                        <i class="bi bi-check2-circle me-1"></i>บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .profile-page { background: #f4f7fb; min-height: 100vh; }
    .profile-card { max-width: 560px; margin-inline: auto; }
    .profile-avatar { width: 96px; height: 96px; display: grid; place-items: center; border: 8px solid #dcecff; border-radius: 50%; background: #edf6ff; color: #0866d8; font-size: 3.25rem; }
    .profile-divider { width: 64px; height: 4px; border-radius: 4px; background: #0d6efd; }
    .profile-card .form-control[readonly] { background-color: #f8fafc; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('profileForm').addEventListener('submit', function (event) {
        const form = event.currentTarget;
        const name = form.name.value.trim();
        const currentPassword = form.current_password.value;
        const newPassword = form.new_password.value;
        const confirmPassword = form.confirm_password.value;
        const error = document.getElementById('formError');
        let message = '';

        if (!name) {
            message = 'กรุณากรอกชื่อ-นามสกุล';
        } else if (currentPassword || newPassword || confirmPassword) {
            if (!currentPassword) message = 'กรุณากรอกรหัสผ่านปัจจุบัน';
            else if (newPassword.length < 8) message = 'รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัวอักษร';
            else if (newPassword !== confirmPassword) message = 'รหัสผ่านใหม่และการยืนยันไม่ตรงกัน';
        }

        if (message) {
            event.preventDefault();
            error.textContent = message;
            error.classList.remove('d-none');
            return;
        }

        error.classList.add('d-none');
        document.getElementById('saveProfileButton').disabled = true;
    });
</script>

</body>
</html>