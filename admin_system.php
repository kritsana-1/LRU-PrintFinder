<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

$users = $pdo->query('SELECT User_ID, Username, Name, Role FROM `User` ORDER BY User_ID DESC')->fetchAll();
$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Management | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/navbar.php'; ?>
    <div class="admin-shell d-lg-flex">
        <?php require __DIR__ . '/admin_sidebar.php'; ?>
        <main class="admin-content p-3 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">System Management</h1>
                    <p class="text-muted mb-0">จัดการบัญชีผู้ใช้งานและสิทธิ์ในระบบ</p>
                </div>
                <a href="admin_user_form.php" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>เพิ่มผู้ใช้งาน</a>
            </div>
            <?php if ($flashSuccess): ?><div class="alert alert-success" role="alert"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
            <?php if ($flashError): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
            <section class="card admin-card">
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-5"><label class="visually-hidden" for="userSearch">ค้นหาผู้ใช้งาน</label><div class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search"></i></span><input class="form-control" type="search" id="userSearch" placeholder="Search account"></div></div>
                        <div class="col-md-auto"><button type="button" class="btn btn-outline-secondary" id="exportUsers"><i class="bi bi-download me-2"></i>Export</button></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="userTable">
                            <thead><tr><th>Account</th><th>User ID</th><th>Role</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><div class="d-flex align-items-center gap-2"><span class="avatar"><?= htmlspecialchars(strtoupper(substr($user['Username'], 0, 2)), ENT_QUOTES, 'UTF-8') ?></span><div><div class="fw-semibold"><?= htmlspecialchars($user['Username'], ENT_QUOTES, 'UTF-8') ?></div><small class="text-muted"><?= htmlspecialchars($user['Name'], ENT_QUOTES, 'UTF-8') ?></small></div></div></td>
                                        <td><?= htmlspecialchars($user['Username'], ENT_QUOTES, 'UTF-8') ?>@gmail.com</td>
                                        <td><span class="badge text-bg-light"><?= htmlspecialchars($user['Role'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td><span class="badge rounded-pill text-bg-success">Active</span></td>
                                        <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="admin_user_form.php?id=<?= (int) $user['User_ID'] ?>&view=1" title="ดูรายละเอียด"><i class="bi bi-eye"></i></a> <a class="btn btn-sm btn-outline-secondary" href="admin_user_form.php?id=<?= (int) $user['User_ID'] ?>" title="แก้ไข"><i class="bi bi-pencil"></i></a> <form class="d-inline" action="admin_user_delete.php" method="post" onsubmit="return confirm('ยืนยันการลบบัญชีนี้หรือไม่?');"><input type="hidden" name="user_id" value="<?= (int) $user['User_ID'] ?>"><button class="btn btn-sm btn-outline-danger" type="submit" title="ลบ"><i class="bi bi-trash"></i></button></form></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <nav class="d-flex justify-content-end mt-3" aria-label="Pagination"><ul class="pagination pagination-sm mb-0"><li class="page-item active"><a class="page-link" href="#">1</a></li></ul></nav>
                </div>
            </section>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            $('#userSearch').on('input', function () {
                const query = $(this).val().toLowerCase();
                $('#userTable tbody tr').each(function () {
                    $(this).toggle($(this).text().toLowerCase().includes(query));
                });
            });

            $('#exportUsers').on('click', function () {
                const rows = [['Username', 'Name', 'Role']];
                $('#userTable tbody tr:visible').each(function () {
                    const cells = $(this).find('td');
                    rows.push([$(cells[0]).find('.fw-semibold').text().trim(), $(cells[0]).find('small').text().trim(), $(cells[2]).text().trim()]);
                });
                const csv = rows.map(row => row.map(value => '"' + value.replaceAll('"', '""') + '"').join(',')).join('\n');
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                $('<a>').attr({ href: url, download: 'users.csv' })[0].click();
                URL.revokeObjectURL(url);
            });
        });
    </script>
</body>
</html>
