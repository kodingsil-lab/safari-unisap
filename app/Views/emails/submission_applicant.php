<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Pengiriman Formulir</title>
</head>
<body style="margin:0;padding:0;background:#eef4fb;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef4fb;margin:0;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="680" cellspacing="0" cellpadding="0" style="width:680px;max-width:680px;">
                    <tr>
                        <td style="padding:0 16px 16px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#ffffff;border-radius:20px;overflow:hidden;border:1px solid #d7e3f1;">
                                <tr>
                                    <td style="background:#eaf2ff;padding:28px 32px 18px;text-align:center;">
                                        <img src="<?= app_logo_url() ?>" alt="Logo Kampus" width="72" style="display:block;margin:0 auto 14px;">
                                        <div style="font-size:16px;font-weight:700;color:#1e3a8a;"><?= esc($siteName) ?></div>
                                        <div style="font-size:13px;color:#475569;"><?= esc($siteTagline) ?></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:24px 32px 32px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fbff;border:1px solid #dbe7f5;border-radius:16px;margin-bottom:18px;">
                                            <tr>
                                                <td style="padding:22px 24px;">
                                                    <div style="font-size:13px;color:#64748b;margin-bottom:6px;">Status Pencatatan</div>
                                                    <div style="font-size:28px;font-weight:700;color:#1e3a8a;margin-bottom:12px;">Formulir Berhasil Diterima</div>
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="font-size:14px;color:#334155;">
                                                        <tr>
                                                            <td style="padding:4px 0;">Nomor Pengajuan</td>
                                                            <td align="right" style="padding:4px 0;font-weight:700;"><?= esc($submission['submission_code']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:4px 0;">Formulir</td>
                                                            <td align="right" style="padding:4px 0;font-weight:700;"><?= esc($submission['form_name']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:4px 0;">Tanggal Pengiriman</td>
                                                            <td align="right" style="padding:4px 0;font-weight:700;"><?= esc($submission['submitted_at']) ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <div style="font-size:15px;line-height:1.7;color:#334155;margin-bottom:18px;">
                                            Yth. <?= esc($submission['applicant_name']) ?>,<br>
                                            Formulir Anda telah berhasil dikirim dan tercatat di sistem. Silakan simpan nomor pengajuan ini sebagai arsip.
                                        </div>

                                        <div style="font-size:16px;font-weight:700;color:#0f172a;margin-bottom:10px;">Ringkasan Data Formulir</div>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #dbe7f5;border-radius:16px;overflow:hidden;background:#ffffff;margin-bottom:18px;">
                                            <?php foreach ($values as $row): ?>
                                                <tr>
                                                    <td style="padding:10px 14px;border-bottom:1px solid #e5edf7;width:38%;font-size:13px;color:#64748b;vertical-align:top;"><?= esc($row['label']) ?></td>
                                                    <td style="padding:10px 14px;border-bottom:1px solid #e5edf7;font-size:13px;color:#0f172a;vertical-align:top;"><?= nl2br(esc($row['value'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach ($files as $file): ?>
                                                <tr>
                                                    <td style="padding:10px 14px;border-bottom:1px solid #e5edf7;width:38%;font-size:13px;color:#64748b;vertical-align:top;"><?= esc($file['label']) ?></td>
                                                    <td style="padding:10px 14px;border-bottom:1px solid #e5edf7;font-size:13px;color:#0f172a;vertical-align:top;"><?= esc($file['value']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>

                                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin-bottom:18px;">
                                            <tr>
                                                <td style="border-radius:999px;background:#1e3a8a;">
                                                    <a href="<?= esc($proofUrl) ?>" style="display:inline-block;padding:12px 20px;color:#ffffff;text-decoration:none;font-weight:700;">Unduh Bukti PDF</a>
                                                </td>
                                            </tr>
                                        </table>

                                        <div style="font-size:13px;line-height:1.7;color:#64748b;">
                                            Jika membutuhkan bantuan lebih lanjut, silakan hubungi <?= esc($supportEmail) ?> atau <?= esc($supportPhone) ?>.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
