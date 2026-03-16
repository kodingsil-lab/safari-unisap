<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body{font-family:sans-serif;font-size:11px;color:#1e293b}
        .header{border-bottom:3px solid #1e3a8a;padding-bottom:12px;margin-bottom:20px}
        .brand-table{width:100%}
        .brand-table td{vertical-align:middle}
        .logo{width:60px}
        h1,h2,h3,p{margin:0}
        .muted{color:#64748b}
        .box{border:1px solid #cbd5e1;border-radius:10px;padding:10px 12px;margin-bottom:14px}
        table{width:100%;border-collapse:collapse}
        th,td{border:1px solid #cbd5e1;padding:8px;text-align:left}
        th{background:#eff6ff;width:34%}
    </style>
</head>
<body>
    <div class="header">
        <table class="brand-table">
            <tr>
                <td style="width:72px;"><img class="logo" src="<?= pdf_logo_src() ?>" alt="Logo Kampus"></td>
                <td>
                    <h2>SAFARI UNISAP</h2>
                    <p><strong>Sistem Administrasi Formulir Universitas San Pedro</strong></p>
                    <p class="muted">Bukti pengajuan formulir</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="box">
        <p><strong>Nomor Pengajuan:</strong> <?= esc($submission['submission_code']) ?></p>
        <p><strong>Nama Formulir:</strong> <?= esc($submission['form_name']) ?></p>
        <p><strong>Pengirim Formulir:</strong> <?= esc($submission['applicant_name']) ?></p>
        <p><strong>Email:</strong> <?= esc($submission['applicant_email']) ?></p>
        <p><strong>Tanggal Pengajuan:</strong> <?= esc($submission['submitted_at']) ?></p>
    </div>

    <h3>Detail Data Pengajuan</h3>
    <table>
        <tbody>
        <?php foreach ($values as $value): ?>
            <tr><th><?= esc($value['label_field'] ?: $value['nama_field']) ?></th><td><?= esc($value['nilai_longtext'] ?: $value['nilai_text'] ?: $value['nilai_date'] ?: $value['nilai_number'] ?: $value['nilai_json'] ?: '-') ?></td></tr>
        <?php endforeach; ?>
        <?php foreach ($files as $file): ?>
            <tr><th><?= esc($file['label_field'] ?: ('Lampiran #' . $file['form_field_id'])) ?></th><td><?= esc($file['nama_file_asli']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
