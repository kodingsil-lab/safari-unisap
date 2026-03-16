<?= $identity = submission_identity($submission, $values); ?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel p-4 mb-4">
            <div class="small text-secondary">Nomor Pengajuan</div>
            <h3 class="fw-bold"><?= esc($submission['submission_code']) ?></h3>
            <hr>
            <p class="mb-1"><strong>Formulir:</strong> <?= esc($submission['form_name']) ?></p>
            <p class="mb-1"><strong>Nama:</strong> <?= esc($identity['applicant_name'] ?: '-') ?></p>
            <p class="mb-1"><strong>NIDN/NIP:</strong> <?= esc($identity['nidn_nip'] ?: '-') ?></p>
            <p class="mb-1"><strong>Unit Kerja:</strong> <?= esc($identity['unit_kerja'] ?: '-') ?></p>
            <p class="mb-1"><strong>Email:</strong> <?= esc($identity['applicant_email'] ?: '-') ?></p>
            <p class="mb-0"><strong>Telepon:</strong> <?= esc($identity['applicant_phone'] ?: '-') ?></p>
            <div class="admin-form-actions mt-4">
                <a href="<?= site_url('admin/export/pengajuan/' . $submission['id'] . '/pdf') ?>" class="btn btn-danger rounded-pill px-3">Unduh PDF Detail</a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel p-4 mb-4">
            <h4 class="fw-bold admin-section-title">Data Formulir</h4>
            <div class="row g-3">
                        <?php foreach ($values as $value): ?>
                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100">
                                    <div class="small text-secondary"><?= esc($value['label_field'] ?: $value['nama_field']) ?></div>
                                    <div class="fw-semibold"><?= nl2br(esc($value['nilai_longtext'] ?: $value['nilai_text'] ?: $value['nilai_date'] ?: $value['nilai_number'] ?: $value['nilai_json'] ?: '-')) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
            </div>
        </div>
        <div class="panel p-4 mb-4">
            <h4 class="fw-bold admin-section-title">Lampiran</h4>
            <?php if ($files): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Nama Isian</th><th>Nama File</th><th>Lokasi File</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php foreach ($files as $file): ?>
                            <tr>
                                <td><?= esc($file['label_field'] ?: ('Isian #' . $file['form_field_id'])) ?></td>
                                <td><?= esc($file['nama_file_asli']) ?></td>
                                <td><?= esc($file['path_file']) ?></td>
                                <td><a href="<?= site_url('admin/pengajuan/file/' . $file['id']) ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">Unduh</a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-secondary mb-0">Tidak ada lampiran.</p>
            <?php endif; ?>
        </div>
        <?php if (! empty($submission['admin_notes'])): ?>
            <div class="panel p-4">
                <h4 class="fw-bold admin-section-title">Catatan Admin</h4>
                <div><?= nl2br(esc($submission['admin_notes'])) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
