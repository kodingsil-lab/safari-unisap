<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="panel p-4 mb-4">
    <div class="admin-page-head d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <div class="small text-secondary">Unduh data pengajuan</div>
            <h3 class="fw-bold">Ekspor Data</h3>
        </div>
    </div>
    <p class="text-secondary mb-0">
        Menu ini dipakai untuk mengunduh data pengajuan. Jika ingin file rekap umum, gunakan ekspor rekap.
        Jika ingin hasil sesuai satu jenis formulir, pilih formulir terlebih dahulu lalu gunakan ekspor detail formulir.
    </p>
</div>

<div class="panel p-4">
    <form method="get" action="<?= site_url('admin/export') ?>" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= esc($category['id']) ?>" <?= (string) ($filters['category_id'] ?? '') === (string) $category['id'] ? 'selected' : '' ?>>
                        <?= esc($category['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Formulir</label>
            <select name="form_type_id" class="form-select">
                <option value="">Semua Formulir</option>
                <?php foreach ($forms as $form): ?>
                    <option value="<?= esc($form['id']) ?>" <?= (string) ($filters['form_type_id'] ?? '') === (string) $form['id'] ? 'selected' : '' ?>>
                        <?= esc($form['nama_form']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
        </div>
        <div class="col-md-12">
            <label class="form-label">Kata Kunci</label>
            <input type="text" name="keyword" class="form-control" value="<?= esc($filters['keyword'] ?? '') ?>" placeholder="Nama / kode / email">
        </div>
        <div class="col-12 admin-filter-actions btn-toolbar-slim">
            <button type="submit" class="btn btn-dark rounded-pill px-3">Terapkan Filter</button>
            <a href="<?= site_url('admin/export') ?>" class="btn btn-outline-secondary rounded-pill px-3">Reset</a>
        </div>
    </form>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="border rounded-4 p-3 h-100">
                <div class="fw-semibold mb-2">Ekspor Rekap</div>
                <div class="text-secondary small mb-3">
                    Cocok untuk melihat daftar semua pengajuan secara ringkas. Kolom yang diunduh berisi data umum seperti kode, formulir, nama pengaju, email, telepon, dan tanggal.
                </div>
                <div class="d-flex flex-wrap gap-2 btn-toolbar-slim">
                    <a href="<?= site_url('admin/export/excel?' . http_build_query(($filters ?? []) + ['export_type' => 'summary'])) ?>" class="btn btn-success rounded-pill">Unduh Excel Rekap</a>
                    <a href="<?= site_url('admin/export/pdf?' . http_build_query(($filters ?? []) + ['export_type' => 'summary'])) ?>" class="btn btn-danger rounded-pill">Unduh PDF Rekap</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="border rounded-4 p-3 h-100">
                <div class="fw-semibold mb-2">Ekspor per Formulir</div>
                <div class="text-secondary small mb-3">
                    Cocok jika ingin data sesuai satu jenis formulir. Hasil unduhan akan mengikuti isian pada formulir yang dipilih, jadi kolomnya lebih lengkap dan lebih rapi.
                </div>
                <div class="d-flex flex-wrap gap-2 btn-toolbar-slim">
                    <?php if (! empty($filters['form_type_id'])): ?>
                        <a href="<?= site_url('admin/export/excel?' . http_build_query(($filters ?? []) + ['export_type' => 'detail'])) ?>" class="btn btn-outline-success rounded-pill">Unduh Excel per Formulir</a>
                        <a href="<?= site_url('admin/export/pdf?' . http_build_query(($filters ?? []) + ['export_type' => 'detail'])) ?>" class="btn btn-outline-danger rounded-pill">Unduh PDF per Formulir</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-secondary rounded-pill" disabled>Pilih formulir terlebih dahulu</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
