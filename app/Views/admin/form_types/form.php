<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="panel p-4">
    <form method="post" class="row g-4">
        <?= csrf_field() ?>
        <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= esc($category['id']) ?>" <?= old('category_id', $formType['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= esc($category['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Formulir</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $formType['nama_form'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Kode URL</label>
            <input type="text" name="slug" class="form-control" value="<?= old('slug', $formType['slug'] ?? '') ?>">
            <div class="form-text">Dipakai untuk alamat halaman formulir.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Kode</label>
            <input type="text" name="code" class="form-control" value="<?= old('code', $formType['kode_form'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Ikon</label>
            <input type="text" name="icon" class="form-control" value="<?= old('icon', $formType['icon'] ?? 'bi-file-earmark-text') ?>">
            <div class="form-text">Boleh dikosongkan jika memakai ikon bawaan.</div>
        </div>
        <div class="col-12">
            <label class="form-label">Nama Template PDF</label>
            <input type="text" name="template_pdf" class="form-control" value="<?= old('template_pdf', $formType['template_pdf'] ?? '') ?>" placeholder="contoh: registrasi_dosen_baru">
            <div class="form-text">Isi jika ingin membedakan tampilan PDF untuk formulir ini.</div>
        </div>
        <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3"><?= old('description', $formType['deskripsi'] ?? '') ?></textarea>
        </div>
        <div class="col-md-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="sort_order" class="form-control" value="<?= old('sort_order', $formType['urutan'] ?? 0) ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= old('is_active', $formType['is_active'] ?? 1) ? 'checked' : '' ?>>
                <label class="form-check-label">Aktif</label>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-form-actions">
                <button class="btn btn-dark rounded-pill px-3">Simpan Formulir</button>
                <a href="<?= site_url('admin/forms') ?>" class="btn btn-outline-secondary rounded-pill px-3">Kembali</a>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
