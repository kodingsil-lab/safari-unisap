<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="panel p-4">
    <form method="post" class="row g-4">
        <?= csrf_field() ?>
        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control <?= $validation->getError('nama') ? 'is-invalid' : '' ?>" value="<?= esc(old('nama', $admin['nama'] ?? '')) ?>">
            <?php if ($validation->getError('nama')): ?><div class="invalid-feedback"><?= esc($validation->getError('nama')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control <?= $validation->getError('email') ? 'is-invalid' : '' ?>" value="<?= esc(old('email', $admin['email'] ?? '')) ?>">
            <?php if ($validation->getError('email')): ?><div class="invalid-feedback"><?= esc($validation->getError('email')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control <?= $validation->getError('username') ? 'is-invalid' : '' ?>" value="<?= esc(old('username', $admin['username'] ?? '')) ?>">
            <?php if ($validation->getError('username')): ?><div class="invalid-feedback"><?= esc($validation->getError('username')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
            <label class="form-label">Peran</label>
            <select name="role" class="form-select <?= $validation->getError('role') ? 'is-invalid' : '' ?>">
                <option value="admin" <?= old('role', $admin['role'] ?? 'admin') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="superadmin" <?= old('role', $admin['role'] ?? 'admin') === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
            </select>
            <?php if ($validation->getError('role')): ?><div class="invalid-feedback"><?= esc($validation->getError('role')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
            <label class="form-label">Password <?= isset($admin) ? '(Kosongkan jika tidak diubah)' : '' ?></label>
            <input type="password" name="password" class="form-control <?= $validation->getError('password') ? 'is-invalid' : '' ?>">
            <?php if ($validation->getError('password')): ?><div class="invalid-feedback"><?= esc($validation->getError('password')) ?></div><?php endif; ?>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= old('is_active', $admin['is_active'] ?? 1) ? 'checked' : '' ?>>
                <label class="form-check-label">Aktif</label>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-form-actions">
                <button class="btn btn-dark rounded-pill px-3">Simpan Admin</button>
                <a href="<?= site_url('admin/admin-users') ?>" class="btn btn-outline-secondary rounded-pill px-3">Kembali</a>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
