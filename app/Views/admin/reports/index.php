<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="row g-4 mb-4">
    <div class="col-md-6"><div class="panel p-4"><div class="text-secondary">Total Pengajuan</div><div class="display-6 fw-bold"><?= esc($summary['totalSubmissions']) ?></div></div></div>
    <div class="col-md-6"><div class="panel p-4"><div class="text-secondary">Total Jenis Form</div><div class="display-6 fw-bold"><?= esc($summary['totalForms']) ?></div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="panel p-4">
            <h4 class="fw-bold admin-section-title">Rekap per Form</h4>
            <?php foreach ($byForm as $row): ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span><?= esc($row['nama_form']) ?></span>
                    <strong><?= esc($row['total']) ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel p-4">
            <h4 class="fw-bold admin-section-title">Rekap Bulanan</h4>
            <?php foreach ($monthly as $row): ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span><?= esc($row['bulan']) ?></span>
                    <strong><?= esc($row['total']) ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
