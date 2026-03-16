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
        .section-title{font-size:16px;font-weight:bold;margin:0 0 8px}
        .identity-box{border:1px solid #cbd5e1;padding:12px 14px;margin-bottom:16px}
        .identity-table{width:100%;border-collapse:collapse;margin:0}
        .identity-table td{border:none;padding:6px 8px 6px 0;vertical-align:top}
        .identity-label{width:26%;font-weight:bold;color:#334155}
        .identity-sep{width:3%}
        .identity-value{width:21%}
        table{width:100%;border-collapse:collapse;margin-bottom:14px}
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
                    <div class="muted">Detail pengajuan untuk admin | Dicetak <?= esc($generatedAt ?? '-') ?></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="identity-box">
        <div class="section-title">Identitas Pengirim Formulir</div>
        <table class="identity-table">
            <tr>
                <td class="identity-label">Nomor Pengajuan</td>
                <td class="identity-sep">:</td>
                <td class="identity-value"><?= esc($submission['submission_code']) ?></td>
                <td class="identity-label">Tanggal Pengajuan</td>
                <td class="identity-sep">:</td>
                <td class="identity-value"><?= esc($submission['submitted_at']) ?></td>
            </tr>
            <tr>
                <td class="identity-label">Formulir</td>
                <td class="identity-sep">:</td>
                <td class="identity-value"><?= esc($submission['form_name']) ?></td>
                <td class="identity-label">Pengirim Formulir</td>
                <td class="identity-sep">:</td>
                <td class="identity-value"><?= esc($submission['applicant_name']) ?></td>
            </tr>
            <tr>
                <td class="identity-label">Email</td>
                <td class="identity-sep">:</td>
                <td class="identity-value"><?= esc($submission['applicant_email']) ?></td>
                <td class="identity-label">Telepon</td>
                <td class="identity-sep">:</td>
                <td class="identity-value"><?= esc($submission['applicant_phone'] ?: '-') ?></td>
            </tr>
            <tr>
                <td class="identity-label">NIDN / NIP</td>
                <td class="identity-sep">:</td>
                <td class="identity-value"><?= esc($submission['nidn_nip'] ?: '-') ?></td>
                <td class="identity-label">Unit Kerja</td>
                <td class="identity-sep">:</td>
                <td class="identity-value"><?= esc($submission['unit_kerja'] ?: '-') ?></td>
            </tr>
        </table>
    </div>
    <h3 class="section-title">Data Formulir</h3>
    <table>
        <tbody>
        <?php foreach ($values as $value): ?>
            <tr>
                <th><?= esc($value['label_field'] ?: $value['nama_field']) ?></th>
                <td><?= esc($value['nilai_longtext'] ?: $value['nilai_text'] ?: $value['nilai_date'] ?: $value['nilai_number'] ?: $value['nilai_json'] ?: '-') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <h3 class="section-title">Lampiran</h3>
    <table>
        <thead><tr><th>Nama Isian</th><th>Nama File</th><th>Keterangan</th></tr></thead>
        <tbody>
        <?php foreach ($files as $file): ?>
            <tr>
                <td><?= esc($file['label_field'] ?: ('Isian #' . $file['form_field_id'])) ?></td>
                <td><?= esc($file['nama_file_asli']) ?></td>
                <td>Terlampir di sistem</td>
            </tr>
        <?php endforeach; ?>
        <?php if ($files === []): ?>
            <tr>
                <td colspan="3">Tidak ada lampiran.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
