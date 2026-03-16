<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="row g-4 mb-4">
    <div class="col-md-4"><div class="panel p-4"><div class="text-secondary">Total Pengajuan</div><div class="fs-1 fw-bold"><?= esc($summary['totalSubmissions']) ?></div></div></div>
    <div class="col-md-4"><div class="panel p-4"><div class="text-secondary">Pengajuan Hari Ini</div><div class="fs-1 fw-bold"><?= esc($summary['submittedToday']) ?></div></div></div>
    <div class="col-md-4"><div class="panel p-4"><div class="text-secondary">Jenis Formulir</div><div class="fs-1 fw-bold"><?= esc($summary['totalForms']) ?></div></div></div>
</div>
<div class="panel p-4">
    <h4 class="fw-bold admin-section-title">Pengajuan Terbaru</h4>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:18%;">Nomor Pengajuan</th><th style="width:28%;">Formulir</th><th style="width:28%;">Pengirim Formulir</th><th style="width:18%;">Tanggal</th><th style="width:8%;"></th></tr></thead>
            <tbody>
            <?php foreach ($recentSubmissions as $item): ?>
                <tr>
                    <td><?= esc($item['submission_code']) ?></td>
                    <td><?= esc($item['form_name']) ?></td>
                    <td><?= esc($item['applicant_name']) ?></td>
                    <td><?= esc($item['submitted_at']) ?></td>
                    <td><a href="<?= site_url('admin/pengajuan/' . $item['id']) ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">Detail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
