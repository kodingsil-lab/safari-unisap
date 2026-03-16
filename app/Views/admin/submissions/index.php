<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="panel p-4 mb-4">
    <form method="get" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= esc($category['id']) ?>" <?= (string) ($filters['category_id'] ?? '') === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Formulir</label>
            <select name="form_type_id" class="form-select">
                <option value="">Semua Formulir</option>
                <?php foreach ($forms as $form): ?>
                    <option value="<?= esc($form['id']) ?>" <?= (string) $filters['form_type_id'] === (string) $form['id'] ? 'selected' : '' ?>><?= esc($form['nama_form']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?= esc($filters['date_from']) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?= esc($filters['date_to']) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Kata Kunci</label>
            <input type="text" name="keyword" class="form-control" value="<?= esc($filters['keyword']) ?>" placeholder="Nama / kode / email">
        </div>
        <div class="col-md-12 admin-filter-actions btn-toolbar-slim">
            <button class="btn btn-dark rounded-pill px-3">Terapkan Filter</button>
            <a href="<?= current_url() ?>" class="btn btn-outline-secondary rounded-pill px-3">Reset</a>
        </div>
    </form>
    <div class="d-flex gap-2 flex-wrap mt-3 btn-toolbar-slim">
        <a href="<?= site_url('admin/export/excel?' . http_build_query($filters + ['export_type' => 'summary'])) ?>" class="btn btn-success rounded-pill">Ekspor Rekap Excel</a>
        <a href="<?= site_url('admin/export/pdf?' . http_build_query($filters + ['export_type' => 'summary'])) ?>" class="btn btn-danger rounded-pill">Ekspor Rekap PDF</a>
        <?php if (! empty($filters['form_type_id'])): ?>
            <a href="<?= site_url('admin/export/excel?' . http_build_query($filters + ['export_type' => 'detail'])) ?>" class="btn btn-outline-success rounded-pill">Ekspor Detail Formulir</a>
            <a href="<?= site_url('admin/export/pdf?' . http_build_query($filters + ['export_type' => 'detail'])) ?>" class="btn btn-outline-danger rounded-pill">Unduh PDF Detail Formulir</a>
        <?php else: ?>
            <button type="button" class="btn btn-outline-secondary rounded-pill" disabled>Ekspor Detail Formulir</button>
        <?php endif; ?>
    </div>
    <div class="small text-secondary mt-2">
        Ekspor rekap cocok untuk semua pengajuan. Ekspor detail formulir akan mengikuti isian formulir yang dipilih pada filter.
    </div>
</div>

<div class="panel p-4">
    <form id="bulk-delete-form" method="post" action="<?= site_url('admin/pengajuan/bulk-delete') ?>" onsubmit="return confirm('Hapus semua pengajuan yang dipilih?')">
        <?= csrf_field() ?>
    </form>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 btn-toolbar-slim">
        <div class="small text-secondary">Centang data yang ingin dihapus sekaligus.</div>
        <button type="submit" form="bulk-delete-form" class="btn btn-outline-danger rounded-pill px-3">Hapus yang Dipilih</button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:4%;"><input type="checkbox" id="select-all-submissions"></th><th style="width:18%;">Nomor Pengajuan</th><th style="width:24%;">Formulir</th><th style="width:24%;">Pengirim Formulir</th><th style="width:18%;">Tanggal</th><th style="width:12%;">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($submissions as $item): ?>
                <tr>
                    <td><input type="checkbox" name="submission_ids[]" value="<?= esc($item['id']) ?>" class="submission-checkbox" form="bulk-delete-form"></td>
                    <td><?= esc($item['submission_code']) ?></td>
                    <td><?= esc($item['form_name']) ?></td>
                    <td><?= esc($item['applicant_name']) ?><div class="small text-secondary"><?= esc($item['applicant_email']) ?></div></td>
                    <td><?= esc($item['submitted_at']) ?></td>
                    <td>
                        <div class="btn-toolbar-row btn-toolbar-slim">
                            <a href="<?= site_url('admin/pengajuan/' . $item['id']) ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">Detail</a>
                            <form method="post" action="<?= site_url('admin/pengajuan/' . $item['id'] . '/delete') ?>" onsubmit="return confirm('Hapus pengajuan ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($submissions === []): ?>
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Belum ada pengajuan yang sesuai dengan filter.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4"><?= $pager->links('default', 'admin_pager') ?></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all-submissions');
    const checkboxes = document.querySelectorAll('.submission-checkbox');

    if (!selectAll || checkboxes.length === 0) {
        return;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAll.checked;
        });
    });

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const allChecked = Array.from(checkboxes).every(function (item) {
                return item.checked;
            });
            selectAll.checked = allChecked;
        });
    });
});
</script>
<?= $this->endSection() ?>
