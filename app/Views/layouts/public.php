<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'SAFARI UNISAP') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --brand:#1d4ed8; --brand-dark:#1e3a8a; --brand-soft:#dbeafe; --ink:#0f172a; --muted:#64748b; --surface:#ffffff; --bg:#f8fafc; --line:#dbe4f0; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:linear-gradient(180deg,#eff6ff 0,#f8fafc 30%,#ffffff 100%); color:var(--ink); }
        .navbar-glass,.card-soft{ background:rgba(255,255,255,.92); backdrop-filter:blur(12px); border:1px solid var(--line); box-shadow:0 12px 35px rgba(30,58,138,.08); }
        .hero { padding:2.4rem 0 3rem; }
        .hero-orb { position:absolute; width:18rem; height:18rem; background:linear-gradient(135deg,#bfdbfe,#93c5fd); filter:blur(18px); opacity:.45; border-radius:999px; z-index:-1; }
        .hero-orb.one { top:3rem; left:-4rem; } .hero-orb.two { bottom:-4rem; right:0; }
        .icon-pill { width:3rem; height:3rem; display:inline-flex; align-items:center; justify-content:center; border-radius:1rem; background:var(--brand-soft); color:var(--brand-dark); }
        .public-section-title { font-size:1.18rem; line-height:1.25; }
        .public-brand-logo { width:58px; height:58px; object-fit:contain; }
        .public-brand-text strong { font-size:1.35rem; line-height:1; letter-spacing:-.02em; }
        .public-brand-text small { font-size:.86rem; line-height:1.35; }
        .footer-soft { background:#1e3a8a; color:#dbeafe; }
        .btn-dark { background:var(--brand-dark); border-color:var(--brand-dark); }
        .btn-outline-dark { color:var(--brand-dark); border-color:#93c5fd; }
        .btn-outline-dark:hover { background:var(--brand-dark); border-color:var(--brand-dark); }
        .btn { font-weight:600; }
        .public-nav-link {
            color:var(--ink);
            border-radius:999px;
            padding:.55rem .95rem;
            transition:all .18s ease;
        }
        .public-nav-link:hover {
            background:#eff6ff;
            color:var(--brand-dark);
        }
        .public-nav-link.active {
            background:#dbeafe;
            color:var(--brand-dark) !important;
            font-weight:700;
        }
        .badge { font-size:.76rem; padding:.35rem .55rem; border-radius:.7rem; }
        .form-control,
        .form-select { min-height:48px; border-radius:1rem; }
        textarea.form-control { min-height:auto; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <div class="navbar-glass rounded-pill px-3 px-lg-4 py-2 w-100">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <a class="navbar-brand mb-0 fw-bold text-dark d-flex align-items-center gap-3" href="<?= site_url('/') ?>">
                        <img src="<?= app_logo_url() ?>" alt="Logo UNISAP" class="public-brand-logo">
                        <span class="public-brand-text">
                            <strong class="d-block">SAFARI UNISAP</strong>
                            <small class="text-secondary fw-medium">Sistem Administrasi Formulir Universitas San Pedro</small>
                        </span>
                    </a>
                <div class="d-flex gap-2 flex-wrap align-items-center">
                        <a class="btn btn-link text-decoration-none public-nav-link <?= public_nav_active('') ?>" href="<?= site_url('/') ?>">Beranda</a>
                        <a class="btn btn-link text-decoration-none public-nav-link <?= public_nav_active('formulir') ?>" href="<?= site_url('formulir') ?>">Formulir</a>
                        <a class="btn btn-dark rounded-pill px-4" href="<?= site_url('admin/login') ?>">Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div class="container py-2">
            <?= view('components/alerts') ?>
        </div>
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="footer-soft mt-5 py-5">
        <div class="container d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <h5 class="mb-1"><?= esc(system_setting('site_name', 'SAFARI UNISAP')) ?></h5>
                <p class="mb-0"><?= esc(system_setting('site_tagline', 'Sistem Administrasi Formulir Universitas San Pedro')) ?>.</p>
                <div class="mt-3 small">
                    Developed By
                    <a href="https://wa.me/628113821126" target="_blank" rel="noopener noreferrer" class="text-white text-decoration-none fw-semibold">KSJ</a>
                    <span style="color:#ef4444;">&hearts;</span>
                </div>
            </div>
            <div class="text-lg-end">
                <div>Email layanan: <?= esc(system_setting('support_email', 'layanan@unisap.ac.id')) ?></div>
                <div>Jam layanan: <?= esc(system_setting('office_hours', 'Senin - Jumat, 08.00 - 16.00 WITA')) ?></div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
