<?php
declare(strict_types=1);

require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/db.php';

$isLoggedIn = !empty($_SESSION['logged_in']);
$role = $_SESSION['role'] ?? '';

$stores = [];
$dbError = null;

try {
    $stmt = $pdo->query('SELECT * FROM `store`');
    $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database query failed in index.php: ' . $e->getMessage());
    $dbError = 'ไม่สามารถดึงข้อมูลร้านค้าได้: ' . $e->getMessage();
}
?>
<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>หน้าหลัก | LRU PrintFinder</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >
</head>

<body class="bg-light">

<?php require __DIR__ . '/navbar.php'; ?>

<main class="container py-5">

    <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1 text-primary">
                ค้นหาร้านปริ้นใกล้มหาวิทยาลัยราชภัฏเลย
            </h1>

            <p class="text-muted mb-0">
                ข้อมูลร้านค้า จุดบริการ และเวลาเปิด-ปิด
            </p>
        </div>
    </header>

    <?php if ($dbError): ?>
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($dbError, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <?php foreach ($stores as $store):

            $storeName =
                $store['Store_Name']
                ?? $store['store_name']
                ?? $store['name']
                ?? 'ไม่มีชื่อร้าน';

            $service =
                $store['Service']
                ?? $store['service']
                ?? $store['Description']
                ?? '-';

            $location =
                $store['LocationHint']
                ?? $store['location_hint']
                ?? $store['Address']
                ?? $store['address']
                ?? '-';

            $openingDays =
                $store['OpeningDays']
                ?? $store['opening_days']
                ?? '-';

            $openTime =
                $store['OpeningHours']
                ?? $store['opening_hours']
                ?? '';

            $closeTime =
                $store['ClosingHours']
                ?? $store['closing_hours']
                ?? '';

            $phone =
                $store['PhoneNumber']
                ?? $store['phone_number']
                ?? $store['Phone']
                ?? '';

            $contact =
                $store['MoreContact']
                ?? $store['more_contact']
                ?? '';
        ?>

            <div class="col-md-6 col-xl-4">

                <article class="card h-100 shadow-sm border-0 rounded-3">

                    <div class="card-body d-flex flex-column">

                        <h2 class="h5 fw-bold mb-3 text-dark">
                            <?= htmlspecialchars((string) $storeName, ENT_QUOTES, 'UTF-8') ?>
                        </h2>

                        <p class="mb-2 text-secondary">
                            <i class="bi bi-printer text-primary me-2"></i>
                            <strong class="text-dark">บริการ:</strong>
                            <?= htmlspecialchars((string) $service, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <p class="small mb-2 text-secondary">
                            <i class="bi bi-geo-alt text-danger me-2"></i>
                            <strong class="text-dark">สถานที่:</strong>
                            <?= htmlspecialchars((string) $location, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <p class="small mb-2 text-secondary">
                            <i class="bi bi-calendar3 text-success me-2"></i>
                            <strong class="text-dark">วันเปิด:</strong>
                            <?= htmlspecialchars((string) $openingDays, ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <?php if ($openTime || $closeTime): ?>
                            <p class="small mb-2 text-secondary">
                                <i class="bi bi-clock text-warning me-2"></i>
                                <strong class="text-dark">เวลา:</strong>
                                <?= htmlspecialchars((string) $openTime, ENT_QUOTES, 'UTF-8') ?>
                                -
                                <?= htmlspecialchars((string) $closeTime, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>

                        <p class="small mb-2 text-secondary">
                            <i class="bi bi-telephone text-info me-2"></i>
                            <strong class="text-dark">โทร:</strong>

                            <?php if (!empty($phone)): ?>

                                <a
                                    href="tel:<?= htmlspecialchars((string) $phone, ENT_QUOTES, 'UTF-8') ?>"
                                    class="text-decoration-none"
                                >
                                    <?= htmlspecialchars((string) $phone, ENT_QUOTES, 'UTF-8') ?>
                                </a>

                            <?php else: ?>

                                <span>-</span>

                            <?php endif; ?>
                        </p>

                        <?php if (!empty($contact)): ?>

                            <p class="small mb-3 text-secondary">
                                <i class="bi bi-chat-dots text-secondary me-2"></i>
                                <strong class="text-dark">ติดต่อเพิ่มเติม:</strong>
                                <?= htmlspecialchars((string) $contact, ENT_QUOTES, 'UTF-8') ?>
                            </p>

                        <?php endif; ?>

                        <div class="mt-auto pt-3">

                            <a
                                class="btn btn-sm btn-outline-primary w-100"
                                target="_blank"
                                rel="noopener noreferrer"
                                href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode(
                                    (string) (
                                        !empty($location) && $location !== '-'
                                            ? $location
                                            : $storeName
                                    )
                                ) ?>"
                            >
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                ดูตำแหน่งบนแผนที่
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        <?php endforeach; ?>

        <?php if (empty($stores) && !$dbError): ?>

            <div class="col-12">

                <div
                    class="alert alert-info border-0 shadow-sm text-center py-4"
                    role="alert"
                >
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    ยังไม่มีข้อมูลร้านค้าในระบบ
                </div>

            </div>

        <?php endif; ?>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>