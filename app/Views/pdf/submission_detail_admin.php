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
    <?php
        $identity = submission_identity($submission, $values);
        $identityRows = [
            [
                ['label' => 'Nomor Pengajuan', 'value' => $submission['submission_code'] ?? ''],
                ['label' => 'Tanggal Pengajuan', 'value' => $submission['submitted_at'] ?? ''],
            ],
            [
                ['label' => 'Pengirim Formulir', 'value' => $identity['applicant_name'] ?? ''],
                ['label' => 'Formulir', 'value' => $submission['form_name'] ?? ''],
            ],
        ];

        $optionalCells = array_values(array_filter([
            ['label' => 'Email', 'value' => $identity['applicant_email'] ?? ''],
            ['label' => 'Telepon', 'value' => $identity['applicant_phone'] ?? ''],
            ['label' => 'NIDN / NIP', 'value' => $identity['nidn_nip'] ?? ''],
            ['label' => 'Unit Kerja', 'value' => $identity['unit_kerja'] ?? ''],
        ], static fn (array $item): bool => trim((string) $item['value']) !== '' && trim((string) $item['value']) !== '-'));

        foreach (array_chunk($optionalCells, 2) as $chunk) {
            $identityRows[] = [
                $chunk[0] ?? null,
                $chunk[1] ?? null,
            ];
        }
    ?>
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
            <?php foreach ($identityRows as [$left, $right]): ?>
                <tr>
                    <td class="identity-label"><?= esc($left['label']) ?></td>
                    <td class="identity-sep">:</td>
                    <td class="identity-value"><?= esc($left['value']) ?></td>
                    <?php if ($right !== null): ?>
                        <td class="identity-label"><?= esc($right['label']) ?></td>
                        <td class="identity-sep">:</td>
                        <td class="identity-value"><?= esc($right['value']) ?></td>
                    <?php else: ?>
                        <td class="identity-label"></td>
                        <td class="identity-sep"></td>
                        <td class="identity-value"></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
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
