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
    <style>
        body { font-family:'Manrope',sans-serif; min-height:100vh; background:linear-gradient(180deg,#eff6ff 0,#f8fafc 30%,#ffffff 100%); display:grid; place-items:center; position:relative; overflow:hidden; }
        .login-card { width:min(100%, 460px); background:#fff; border-radius:1.5rem; box-shadow:0 25px 60px rgba(30,58,138,.18); border:1px solid #dbe4f0; position:relative; z-index:2; }
        .bg-orb { position:absolute; width:20rem; height:20rem; background:linear-gradient(135deg,#bfdbfe,#93c5fd); filter:blur(22px); opacity:.45; border-radius:999px; z-index:1; }
        .bg-orb.one { top:6%; left:8%; }
        .bg-orb.two { right:8%; bottom:8%; }
    </style>
</head>
<body>
    <div class="bg-orb one"></div>
    <div class="bg-orb two"></div>
    <div class="login-card p-4 p-lg-5">
        <div class="mb-4 text-center">
            <img src="<?= app_logo_url() ?>" alt="Logo UNISAP" width="64" height="64" class="mb-3" style="object-fit:contain">
            <div class="text-secondary small">Akses Admin</div>
            <h1 class="h3 fw-bold">Login SAFARI UNISAP</h1>
            <div class="text-secondary">Sistem Administrasi Formulir Universitas San Pedro</div>
        </div>
        <?= view('components/alerts') ?>
        <form method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username / Email</label>
                <input type="text" name="identity" class="form-control form-control-lg" value="<?= old('identity') ?>">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control form-control-lg">
            </div>
            <button type="submit" class="btn btn-dark btn-lg w-100 rounded-pill">Masuk</button>
        </form>
    </div>
</body>
</html>
