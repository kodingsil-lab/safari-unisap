<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Admin SAFARI UNISAP') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family:'Manrope',sans-serif; background:#f1f5f9; color:#0f172a; }
        .sidebar { min-height:100vh; background:linear-gradient(180deg,#1e3a8a 0,#1d4ed8 100%); box-shadow:inset -1px 0 0 rgba(255,255,255,.08); }
        .sidebar a { color:#dbeafe; text-decoration:none; border-radius:.9rem; font-weight:500; transition:background-color .18s ease,color .18s ease,transform .18s ease,box-shadow .18s ease; }
        .sidebar a:hover { background:rgba(255,255,255,.08); color:#fff; transform:translateX(2px); }
        .sidebar a.active { background:rgba(255,255,255,.14); color:#fff; box-shadow:inset 0 0 0 1px rgba(255,255,255,.05); }
        .sidebar .nav-link-slim { padding:.72rem .9rem; }
        .sidebar .nav-link-slim i { font-size:.92rem; width:1rem; }
        .sidebar-brand {
            display:flex;
            align-items:center;
            gap:.85rem;
            margin-bottom:1.2rem;
            padding:.35rem .2rem;
            border-radius:1rem;
        }
        .sidebar-brand:hover {
            background:transparent;
            transform:none;
            box-shadow:none;
        }
        .sidebar-brand-logo {
            width:56px;
            height:56px;
            object-fit:contain;
            flex-shrink:0;
        }
        .sidebar-brand-title {
            font-size:1rem;
            line-height:1.05;
            letter-spacing:-.02em;
        }
        .sidebar-brand-subtitle {
            font-size:.88rem;
        }
        .panel { background:#fff; border-radius:1.25rem; border:1px solid #dbe4f0; box-shadow:0 10px 30px rgba(29,78,216,.08); }
        .topbar { background:linear-gradient(135deg,#ffffff,#eff6ff); border:1px solid #dbe4f0; border-radius:1.25rem; }
        .topbar .section-kicker { font-size:.95rem; }
        .topbar .section-title { font-size:2rem; line-height:1.1; letter-spacing:-.02em; }
        .topbar-user {
            background:#fff;
            border:1px solid #dbe4f0;
            border-radius:1.1rem;
            padding:.8rem 1rem;
            min-width:150px;
        }
        .topbar-user .label { font-size:.82rem; color:#64748b; }
        .topbar-user .name { font-size:1rem; font-weight:700; color:#0f172a; }
        .btn { font-weight:600; letter-spacing:-.01em; border-radius:.85rem; }
        .btn.rounded-pill { border-radius:999px !important; }
        .btn-sm,
        .btn-group-sm > .btn {
            padding:.34rem .78rem;
            font-size:.83rem;
            line-height:1.2;
        }
        .btn:not(.btn-sm):not(.btn-lg) {
            padding:.48rem .95rem;
            font-size:.92rem;
            line-height:1.25;
        }
        .btn-lg {
            padding:.66rem 1.05rem;
            font-size:.96rem;
        }
        .btn.w-100 { border-radius:1rem; }
        .btn-toolbar-slim .btn,
        .btn-toolbar-slim button {
            padding:.35rem .78rem;
            font-size:.84rem;
            line-height:1.2;
            white-space:nowrap;
            min-height:34px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }
        .btn-toolbar-slim form { margin:0; }
        .btn-toolbar-row { display:flex; flex-wrap:nowrap; gap:.5rem; align-items:center; }
        .btn-toolbar-row form { margin:0; }
        .badge {
            font-size:.74rem;
            font-weight:700;
            padding:.32rem .5rem;
            border-radius:.6rem;
            line-height:1.1;
        }
        .form-control,
        .form-select {
            min-height:42px;
            padding:.55rem .85rem;
            border-radius:.9rem;
            font-size:.94rem;
        }
        textarea.form-control { min-height:auto; }
        .form-control-sm,
        .form-select-sm {
            min-height:34px;
            padding:.35rem .65rem;
            border-radius:.75rem;
            font-size:.84rem;
        }
        .admin-page-head {
            margin-bottom:1.4rem !important;
        }
        .admin-page-head h3,
        .admin-page-head h4 {
            font-size:1.1rem;
            margin-bottom:0;
            line-height:1.2;
        }
        .admin-page-head .small {
            font-size:.86rem;
        }
        .admin-section-title {
            font-size:1.08rem;
            line-height:1.25;
            margin-bottom:.9rem;
        }
        .admin-form-actions {
            display:flex;
            flex-wrap:wrap;
            gap:.6rem;
            align-items:center;
            margin-top:.25rem;
        }
        .admin-filter-actions {
            display:flex;
            flex-wrap:wrap;
            gap:.6rem;
            align-items:flex-end;
        }
        .panel + .panel {
            margin-top:1.2rem;
        }
        .table {
            margin-bottom:0;
        }
        .table > :not(caption) > * > * {
            padding:.78rem .5rem;
        }
        .table thead th {
            font-size:.9rem;
            font-weight:700;
            color:#0f172a;
            white-space:nowrap;
        }
        .table tbody td {
            font-size:.94rem;
            vertical-align:middle;
        }
        .table-responsive {
            min-height:120px;
        }
        .table td .badge,
        .table th .badge { vertical-align:middle; }
        @media (max-width: 991.98px) {
            .btn-toolbar-row { flex-wrap:wrap; }
            .topbar .section-title { font-size:1.7rem; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-2 sidebar p-4">
                <a href="<?= site_url('/') ?>" class="sidebar-brand">
                    <img src="<?= app_logo_url() ?>" alt="Logo UNISAP" class="sidebar-brand-logo">
                    <div>
                        <h4 class="text-white fw-bold mb-0 sidebar-brand-title">SAFARI UNISAP</h4>
                        <div class="text-white-50 sidebar-brand-subtitle">Universitas San Pedro</div>
                    </div>
                </a>
                <div class="d-grid gap-2">
                    <a class="nav-link-slim <?= admin_nav_active(['admin', 'admin/dashboard']) ?>" href="<?= site_url('admin/dashboard') ?>"><i class="bi bi-speedometer2 me-2"></i>Beranda</a>
                    <a class="nav-link-slim <?= admin_nav_active('admin/pengajuan') ?>" href="<?= site_url('admin/pengajuan') ?>"><i class="bi bi-inboxes me-2"></i>Pengajuan</a>
                    <a class="nav-link-slim <?= admin_nav_active('admin/form-categories') ?>" href="<?= site_url('admin/form-categories') ?>"><i class="bi bi-diagram-3 me-2"></i>Kategori Form</a>
                    <a class="nav-link-slim <?= admin_nav_active('admin/forms') ?>" href="<?= site_url('admin/forms') ?>"><i class="bi bi-ui-checks-grid me-2"></i>Jenis Form</a>
                    <a class="nav-link-slim <?= admin_nav_active('admin/export') ?>" href="<?= site_url('admin/export') ?>"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Ekspor Data</a>
                    <a class="nav-link-slim <?= admin_nav_active('admin/laporan') ?>" href="<?= site_url('admin/laporan') ?>"><i class="bi bi-bar-chart-line me-2"></i>Laporan</a>
                    <a class="nav-link-slim <?= admin_nav_active('admin/pengaturan') ?>" href="<?= site_url('admin/pengaturan') ?>"><i class="bi bi-gear me-2"></i>Pengaturan</a>
                    <a class="nav-link-slim <?= admin_nav_active('admin/admin-users') ?>" href="<?= site_url('admin/admin-users') ?>"><i class="bi bi-people me-2"></i>Pengguna Admin</a>
                    <a class="nav-link-slim" href="<?= site_url('admin/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
                </div>
            </aside>
            <div class="col-lg-10 p-4 p-lg-5">
                <div class="topbar d-flex justify-content-between align-items-center mb-4 p-4">
                    <div>
                        <div class="text-secondary section-kicker">Panel Admin</div>
                        <h2 class="mb-0 section-title"><?= esc($pageTitle ?? 'Dashboard') ?></h2>
                    </div>
                    <div class="topbar-user">
                        <div class="label">Login sebagai</div>
                        <div class="name"><?= esc(session('admin_name')) ?></div>
                    </div>
                </div>
                <?= view('components/alerts') ?>
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
