<?php
require_once __DIR__ . '/check_auth.php';
check_admin();
require_once __DIR__ . '/db.php';

$stores = $pdo->query('SELECT Store_ID, Store_Name, Address, Latitude, Longitude FROM `Store` ORDER BY Store_ID')->fetchAll();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Location Maps | LRU PrintFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
    <?php require __DIR__ . '/navbar.php'; ?>
    <div class="admin-shell d-lg-flex">
        <?php require __DIR__ . '/admin_sidebar.php'; ?>
        <main class="admin-content p-3 p-lg-4">
            <div class="mb-4">
                <h1 class="h3 fw-bold mb-1">Location Maps</h1>
                <p class="text-muted mb-0">Geolocation printers and verified printers</p>
            </div>
            <div class="row g-4">
                <div class="col-xl-8">
                    <section class="card admin-card h-100"><div class="card-body"><div class="map-surface"><div class="map-switcher btn-group btn-group-sm shadow-sm" role="group" aria-label="รูปแบบแผนที่"><button type="button" class="btn btn-primary">Map</button><button type="button" class="btn btn-light">Satellite</button></div><span class="map-road one"></span><span class="map-road two"></span><span class="map-road three"></span><span class="map-pin pin-a"><i class="bi bi-geo-alt-fill"></i></span><span class="map-pin blue pin-b"><i class="bi bi-geo-alt-fill"></i></span><span class="map-pin pin-c"><i class="bi bi-geo-alt-fill"></i></span><div class="map-info"><div class="fw-semibold">Printer 12</div><small class="text-muted">Geolocation printers</small><div class="small mt-2"><i class="bi bi-geo-alt text-primary me-1"></i>Verified location</div></div></div></div></section>
                </div>
                <div class="col-xl-4">
                    <section class="card admin-card h-100"><div class="card-body"><h2 class="h6 fw-bold mb-3">Printer status</h2><div class="small mb-3"><div class="mb-2"><span class="text-success me-2">●</span>Active printer</div><div class="mb-2"><span class="text-danger me-2">●</span>Offline printer</div><div class="mb-2"><span class="text-warning me-2">●</span>Pending verification</div><div><span class="text-secondary me-2">●</span>Unregistered</div></div><hr><label class="form-label small fw-semibold" for="mapArea">Area</label><select class="form-select mb-3" id="mapArea"><option>All locations</option><option>Near LRU</option></select><label class="form-label small fw-semibold" for="mapStatus">Status</label><select class="form-select" id="mapStatus"><option>All status</option><option>Active</option><option>Pending verification</option></select><div class="mt-4 p-3 rounded-3 bg-light"><div class="small text-muted">Registered locations</div><div class="h3 mb-0"><?= count($stores) ?></div></div></div></section>
                </div>
            </div>
            <section class="card admin-card mt-4"><div class="card-header py-3"><h2 class="h6 fw-bold mb-0">Verified printers</h2></div><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Store</th><th>Address</th><th>Coordinates</th><th>Status</th></tr></thead><tbody><?php foreach ($stores as $store): ?><tr><td class="fw-semibold"><?= htmlspecialchars($store['Store_Name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($store['Address'], ENT_QUOTES, 'UTF-8') ?></td><td class="text-nowrap"><?= htmlspecialchars($store['Latitude'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($store['Longitude'], ENT_QUOTES, 'UTF-8') ?></td><td><span class="badge rounded-pill text-bg-success">Verified</span></td></tr><?php endforeach; ?></tbody></table></div></section>
        </main>
    </div>
</body>
</html>
