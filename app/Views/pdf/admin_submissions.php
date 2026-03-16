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
        .muted{color:#64748b}
        table{width:100%;border-collapse:collapse}
        th,td{border:1px solid #cbd5e1;padding:6px;text-align:left}
        th{background:#eff6ff}
    </style>
</head>
<body>
    <div class="header">
        <table class="brand-table">
            <tr>
                <td style="width:72px;"><img class="logo" src="<?= pdf_logo_src() ?>" alt="Logo Kampus"></td>
                <td>
                    <h2 style="margin:0;">SAFARI UNISAP</h2>
                    <div><strong>Sistem Administrasi Formulir Universitas San Pedro</strong></div>
                    <div class="muted"><?= esc($title) ?> | Dicetak <?= esc($generatedAt ?? '-') ?></div>
                </td>
            </tr>
        </table>
    </div>
    <?php if (($exportType ?? 'summary') === 'detail' && ! empty($headers)): ?>
        <table>
            <thead>
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <th><?= esc($header) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach (($rows ?? []) as $row): ?>
                <tr>
                    <?php foreach ($row as $value): ?>
                        <td><?= esc($value) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <table>
            <thead><tr><th>Nomor Pengajuan</th><th>Formulir</th><th>Pengirim Formulir</th><th>Email</th><th>Tanggal</th></tr></thead>
            <tbody>
            <?php foreach ($submissions as $item): ?>
                <tr>
                    <td><?= esc($item['submission_code']) ?></td>
                    <td><?= esc($item['form_name']) ?></td>
                    <td><?= esc($item['applicant_name']) ?></td>
                    <td><?= esc($item['applicant_email']) ?></td>
                    <td><?= esc($item['submitted_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
