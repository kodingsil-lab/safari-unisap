<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin SAFARI UNISAP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --brand:#1e3a8a; --brand-soft:#dbeafe; --ink:#0f172a; --muted:#64748b; --line:#dbe4f0; }
        body { font-family:'Manrope',sans-serif; min-height:100vh; background:radial-gradient(circle at top left,#dbeafe 0,#eff6ff 24%,#f8fafc 55%,#ffffff 100%); display:grid; place-items:center; position:relative; overflow:hidden; color:var(--ink); }
        .login-wrap { width:min(100%, 1020px); position:relative; z-index:2; }
        .login-shell { display:grid; grid-template-columns:1.1fr .9fr; gap:1.5rem; align-items:stretch; }
        .login-panel,
        .login-card { background:rgba(255,255,255,.92); backdrop-filter:blur(14px); border:1px solid var(--line); border-radius:1.75rem; box-shadow:0 30px 65px rgba(30,58,138,.14); }
        .login-panel { padding:2.2rem; position:relative; overflow:hidden; }
        .login-card { width:100%; padding:2rem 2.1rem; position:relative; }
        .panel-chip { display:inline-flex; align-items:center; gap:.55rem; padding:.55rem .9rem; border-radius:999px; background:#ffffff; border:1px solid #d7e6fb; font-size:.84rem; font-weight:700; color:var(--brand); }
        .brand-mark { width:82px; height:82px; display:flex; align-items:center; justify-content:center; border-radius:1.6rem; background:linear-gradient(180deg,#eff6ff,#dbeafe); box-shadow:inset 0 0 0 1px #d7e6fb; margin-bottom:1.25rem; }
        .brand-mark img { width:56px; height:56px; object-fit:contain; }
        .panel-title { font-size:clamp(2rem,3.2vw,3rem); line-height:1.08; letter-spacing:-.04em; margin-bottom:1rem; }
        .panel-copy { color:var(--muted); font-size:1rem; line-height:1.8; max-width:34rem; }
        .panel-stats { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1rem; margin-top:1.8rem; }
        .panel-stat { background:#fff; border:1px solid #e1ebf8; border-radius:1.25rem; padding:1rem 1.1rem; }
        .panel-stat strong { display:block; font-size:1.45rem; line-height:1.1; color:var(--brand); margin-bottom:.25rem; }
        .panel-stat span { color:var(--muted); font-size:.9rem; }
        .login-eyebrow { color:var(--brand); font-size:.84rem; text-transform:uppercase; letter-spacing:.14em; font-weight:800; margin-bottom:.5rem; }
        .login-headline { font-size:2rem; line-height:1.1; letter-spacing:-.04em; margin-bottom:.5rem; }
        .login-subtitle { color:var(--muted); font-size:.98rem; line-height:1.7; }
        .login-logo-frame { width:86px; height:86px; border-radius:1.7rem; background:linear-gradient(180deg,#eff6ff,#dbeafe); display:flex; align-items:center; justify-content:center; margin:0 auto 1.2rem; box-shadow:inset 0 0 0 1px #d7e6fb; }
        .login-logo-frame img { width:58px; height:58px; object-fit:contain; }
        .form-label { font-size:.95rem; margin-bottom:.55rem; color:var(--ink); }
        .form-control { border-radius:1rem; min-height:56px; border-color:#d7e2ee; padding:0 1rem; box-shadow:none; }
        .form-control:focus { border-color:#93c5fd; box-shadow:0 0 0 .2rem rgba(59,130,246,.12); }
        .password-wrap { position:relative; }
        .password-input { padding-right:3.5rem; }
        .password-toggle { position:absolute; top:50%; right:.8rem; transform:translateY(-50%); width:2.4rem; height:2.4rem; border:none; border-radius:.85rem; background:#eef4ff; color:var(--brand); display:inline-flex; align-items:center; justify-content:center; transition:all .18s ease; }
        .password-toggle:hover { background:#dbeafe; color:#1d4ed8; }
        .password-toggle:focus { outline:none; box-shadow:0 0 0 .2rem rgba(59,130,246,.14); }
        .btn-login { min-height:56px; background:linear-gradient(135deg,#1f3b8a,#0f172a); border:none; letter-spacing:-.01em; box-shadow:0 18px 26px rgba(15,23,42,.18); }
        .btn-login:hover { background:linear-gradient(135deg,#1d4ed8,#0f172a); }
        .login-note { margin-top:1.15rem; padding-top:1rem; border-top:1px solid #edf2f7; color:var(--muted); font-size:.88rem; line-height:1.6; }
        .bg-orb { position:absolute; width:22rem; height:22rem; background:linear-gradient(135deg,#bfdbfe,#93c5fd); filter:blur(26px); opacity:.5; border-radius:999px; z-index:1; }
        .bg-orb.one { top:5%; left:6%; }
        .bg-orb.two { right:6%; bottom:6%; }
        .bg-grid { position:absolute; inset:0; background-image:linear-gradient(rgba(148,163,184,.08) 1px, transparent 1px),linear-gradient(90deg, rgba(148,163,184,.08) 1px, transparent 1px); background-size:28px 28px; mask-image:linear-gradient(180deg,rgba(255,255,255,.65),transparent 85%); opacity:.5; pointer-events:none; }
        @media (max-width: 991.98px) {
            .login-shell { grid-template-columns:1fr; }
            .login-panel { display:none; }
            .login-card { max-width:520px; margin-inline:auto; }
        }
        @media (max-width: 575.98px) {
            body { display:block; padding:1rem 0; }
            .login-wrap { width:100%; }
            .login-card { padding:1.35rem 1rem 1.1rem; border-radius:1.35rem; max-width:none; }
            .login-logo-frame { width:74px; height:74px; margin-bottom:1rem; }
            .login-logo-frame img { width:50px; height:50px; }
            .login-eyebrow { font-size:.76rem; letter-spacing:.12em; margin-bottom:.35rem; }
            .login-headline { font-size:1.65rem; margin-bottom:.35rem; }
            .login-subtitle { font-size:.92rem; line-height:1.5; }
            .form-label { font-size:.9rem; margin-bottom:.45rem; }
            .form-control { min-height:50px; border-radius:.9rem; font-size:.97rem; }
            .password-toggle { width:2.2rem; height:2.2rem; right:.65rem; border-radius:.75rem; }
            .btn-login { min-height:50px; font-size:1rem; }
            .login-note { font-size:.82rem; margin-top:.9rem; padding-top:.85rem; }
        }
    </style>
</head>
<body>
    <div class="bg-orb one"></div>
    <div class="bg-orb two"></div>
    <div class="login-wrap px-3">
        <div class="login-shell">
            <div class="login-panel">
                <div class="bg-grid"></div>
                <div class="position-relative">
                    <span class="panel-chip mb-4">
                        <i class="bi bi-shield-lock"></i>
                        Panel Administrasi
                    </span>
                    <div class="brand-mark">
                        <img src="<?= app_logo_url() ?>" alt="Logo UNISAP">
                    </div>
                    <h2 class="fw-bold panel-title">Kelola seluruh formulir kampus dalam satu panel yang rapi dan terstruktur.</h2>
                    <p class="panel-copy mb-0">Akses admin digunakan untuk melihat kiriman formulir, mengelola jenis formulir, menyiapkan ekspor data, serta menjaga alur administrasi kampus tetap tertata.</p>
                    <div class="panel-stats">
                        <div class="panel-stat">
                            <strong>1 Panel</strong>
                            <span>Untuk pengelolaan formulir, data, dan pengaturan sistem</span>
                        </div>
                        <div class="panel-stat">
                            <strong>Terintegrasi</strong>
                            <span>Selaras dengan branding SAFARI UNISAP dan layanan kampus</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="login-card">
                <div class="mb-4 text-center">
                    <div class="login-logo-frame">
                        <img src="<?= app_logo_url() ?>" alt="Logo UNISAP">
                    </div>
                    <div class="login-eyebrow">Akses Admin</div>
                    <h1 class="fw-bold login-headline">Login SAFARI UNISAP</h1>
                    <div class="login-subtitle">Sistem Administrasi Formulir Universitas San Pedro</div>
                </div>
                <?= view('components/alerts') ?>
                <form method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username / Email</label>
                        <input type="text" name="identity" class="form-control form-control-lg" value="<?= old('identity') ?>" placeholder="Masukkan username atau email admin">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="password-wrap">
                            <input type="password" name="password" id="password-field" class="form-control form-control-lg password-input" placeholder="Masukkan password">
                            <button type="button" class="password-toggle" id="password-toggle" aria-label="Tampilkan password" aria-pressed="false">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark btn-lg w-100 rounded-pill btn-login">Masuk ke Panel Admin</button>
                </form>
                <div class="login-note text-center">
                    Gunakan akun admin yang aktif untuk mengelola formulir, melihat kiriman data, dan menyiapkan ekspor administrasi.
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordField = document.getElementById('password-field');
            const toggleButton = document.getElementById('password-toggle');

            if (!passwordField || !toggleButton) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                const showPassword = passwordField.type === 'password';
                passwordField.type = showPassword ? 'text' : 'password';
                toggleButton.setAttribute('aria-pressed', showPassword ? 'true' : 'false');
                toggleButton.setAttribute('aria-label', showPassword ? 'Sembunyikan password' : 'Tampilkan password');
                toggleButton.innerHTML = showPassword
                    ? '<i class="bi bi-eye-slash"></i>'
                    : '<i class="bi bi-eye"></i>';
            });
        });
    </script>
</body>
</html>
