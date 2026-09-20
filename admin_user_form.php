<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$isEdit = $userId !== false && $userId !== null && $userId > 0;
$isView = isset($_GET['view']);
$user = ['Username' => '', 'Name' => '', 'Role' => 'user'];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT User_ID, Username, Name, Role FROM `User` WHERE User_ID = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if (!$user) {
        $_SESSION['flash_error'] = 'ไม่พบบัญชีผู้ใช้งาน';
        header('Location: admin_system.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $isView ? 'รายละเอียดผู้ใช้' : ($isEdit ? 'แก้ไขผู้ใช้' : 'เพิ่มผู้ใช้') ?> | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/navbar.php'; ?>
    <main class="container py-4"><div class="card admin-card mx-auto" style="max-width: 600px"><div class="card-body">
        <h1 class="h3 mb-4"><?= $isView ? 'รายละเอียดผู้ใช้' : ($isEdit ? 'แก้ไขผู้ใช้' : 'เพิ่มผู้ใช้') ?></h1>
        <form action="admin_user_save.php" method="post">
            <?php if ($isEdit): ?><input type="hidden" name="user_id" value="<?= (int) $userId ?>"><?php endif; ?>
            <div class="mb-3"><label class="form-label" for="username">ชื่อผู้ใช้</label><input class="form-control" id="username" name="username" maxlength="50" value="<?= htmlspecialchars($user['Username'], ENT_QUOTES, 'UTF-8') ?>" required <?= $isView ? 'readonly' : '' ?>></div>
            <div class="mb-3"><label class="form-label" for="name">ชื่อ</label><input class="form-control" id="name" name="name" maxlength="100" value="<?= htmlspecialchars($user['Name'], ENT_QUOTES, 'UTF-8') ?>" required <?= $isView ? 'readonly' : '' ?>></div>
            <?php if (!$isView): ?><div class="mb-3"><label class="form-label" for="password">รหัสผ่าน <?= $isEdit ? '(เว้นว่างถ้าไม่เปลี่ยน)' : '' ?></label><input class="form-control" type="password" id="password" name="password" minlength="8" <?= $isEdit ? '' : 'required' ?>></div><?php endif; ?>
            <div class="mb-4"><label class="form-label" for="role">Role</label><select class="form-select" id="role" name="role" <?= $isView ? 'disabled' : '' ?>><option value="user" <?= $user['Role'] === 'user' ? 'selected' : '' ?>>user</option><option value="admin" <?= $user['Role'] === 'admin' ? 'selected' : '' ?>>admin</option></select></div>
            <a href="admin_system.php" class="btn btn-light">กลับ</a><?php if (!$isView): ?><button class="btn btn-primary" type="submit">บันทึก</button><?php endif; ?>
        </form>
    </div></div></main>
</body>
</html>