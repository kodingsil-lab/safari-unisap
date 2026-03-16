<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="panel p-4">
    <form method="post" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-6">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control<?= $validation->hasError('nama_kategori') ? ' is-invalid' : '' ?>" value="<?= esc(old('nama_kategori', $category['nama_kategori'] ?? '')) ?>">
            <div class="invalid-feedback"><?= esc($validation->getError('nama_kategori')) ?></div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Kode URL</label>
            <input type="text" name="slug" class="form-control<?= $validation->hasError('slug') ? ' is-invalid' : '' ?>" value="<?= esc(old('slug', $category['slug'] ?? '')) ?>" placeholder="contoh: akademik">
            <div class="invalid-feedback"><?= esc($validation->getError('slug')) ?></div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control<?= $validation->hasError('urutan') ? ' is-invalid' : '' ?>" value="<?= esc(old('urutan', $category['urutan'] ?? 0)) ?>">
            <div class="invalid-feedback"><?= esc($validation->getError('urutan')) ?></div>
        </div>
        <div class="col-12">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" <?= old('is_active', $category['is_active'] ?? 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-form-actions">
                <button class="btn btn-dark rounded-pill px-3">Simpan Kategori</button>
                <a href="<?= site_url('admin/form-categories') ?>" class="btn btn-outline-secondary rounded-pill px-3">Kembali</a>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
