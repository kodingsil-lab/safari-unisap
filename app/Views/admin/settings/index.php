<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="panel p-4">
    <form method="post" enctype="multipart/form-data" class="row g-4">
        <?= csrf_field() ?>
        <div class="col-md-6">
            <label class="form-label">Nama Sistem</label>
            <input type="text" name="site_name" class="form-control <?= $validation->getError('site_name') ? 'is-invalid' : '' ?>" value="<?= esc(old('site_name', $settings['site_name']['setting_value'] ?? 'SAFARI UNISAP')) ?>">
            <?php if ($validation->getError('site_name')): ?><div class="invalid-feedback"><?= esc($validation->getError('site_name')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Kampus</label>
            <input type="text" name="campus_name" class="form-control <?= $validation->getError('campus_name') ? 'is-invalid' : '' ?>" value="<?= esc(old('campus_name', $settings['campus_name']['setting_value'] ?? 'Universitas San Pedro')) ?>">
            <?php if ($validation->getError('campus_name')): ?><div class="invalid-feedback"><?= esc($validation->getError('campus_name')) ?></div><?php endif; ?>
        </div>
        <div class="col-12">
            <label class="form-label">Tagline</label>
            <input type="text" name="site_tagline" class="form-control" value="<?= esc(old('site_tagline', $settings['site_tagline']['setting_value'] ?? 'Sistem Administrasi Formulir Universitas San Pedro')) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Email Kontak</label>
            <input type="email" name="support_email" class="form-control <?= $validation->getError('support_email') ? 'is-invalid' : '' ?>" value="<?= esc(old('support_email', $settings['support_email']['setting_value'] ?? '')) ?>">
            <?php if ($validation->getError('support_email')): ?><div class="invalid-feedback"><?= esc($validation->getError('support_email')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nomor WA / Telepon</label>
            <input type="text" name="support_phone" class="form-control <?= $validation->getError('support_phone') ? 'is-invalid' : '' ?>" value="<?= esc(old('support_phone', $settings['support_phone']['setting_value'] ?? '')) ?>">
            <?php if ($validation->getError('support_phone')): ?><div class="invalid-feedback"><?= esc($validation->getError('support_phone')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Jam Layanan</label>
            <input type="text" name="office_hours" class="form-control <?= $validation->getError('office_hours') ? 'is-invalid' : '' ?>" value="<?= esc(old('office_hours', $settings['office_hours']['setting_value'] ?? '')) ?>">
            <?php if ($validation->getError('office_hours')): ?><div class="invalid-feedback"><?= esc($validation->getError('office_hours')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-3">
            <label class="form-label">Batas Upload (KB)</label>
            <input type="number" name="max_upload_size" class="form-control <?= $validation->getError('max_upload_size') ? 'is-invalid' : '' ?>" value="<?= esc(old('max_upload_size', $settings['max_upload_size']['setting_value'] ?? '2048')) ?>">
            <?php if ($validation->getError('max_upload_size')): ?><div class="invalid-feedback"><?= esc($validation->getError('max_upload_size')) ?></div><?php endif; ?>
        </div>
        <div class="col-md-3">
            <label class="form-label">Upload Logo Kampus</label>
            <input type="file" name="logo_file" class="form-control <?= $validation->getError('logo_file') ? 'is-invalid' : '' ?>" accept=".png,.jpg,.jpeg,.svg,.webp">
            <?php if ($validation->getError('logo_file')): ?><div class="invalid-feedback"><?= esc($validation->getError('logo_file')) ?></div><?php endif; ?>
            <div class="form-text">Format PNG, JPG, SVG, atau WEBP. Maksimal 2MB.</div>
        </div>
        <div class="col-12">
            <label class="form-label">Format File Diizinkan</label>
            <input type="text" name="allowed_file_types" class="form-control <?= $validation->getError('allowed_file_types') ? 'is-invalid' : '' ?>" value="<?= esc(old('allowed_file_types', $settings['allowed_file_types']['setting_value'] ?? 'pdf,jpg,jpeg,png,doc,docx')) ?>">
            <?php if ($validation->getError('allowed_file_types')): ?><div class="invalid-feedback"><?= esc($validation->getError('allowed_file_types')) ?></div><?php endif; ?>
        </div>
        <div class="col-12">
            <label class="form-label">Logo Saat Ini</label>
            <div class="border rounded-4 p-4 bg-light d-flex align-items-center gap-3">
                <img id="logoPreview" src="<?= app_logo_url() ?>" alt="Logo Kampus" width="72" height="72" style="object-fit:contain">
                <div class="text-secondary">
                    <div id="logoPathText"><?= esc($settings['logo_path']['setting_value'] ?? 'assets/img/logo-unisap.svg') ?></div>
                    <small class="text-muted">Preview akan berubah saat file dipilih.</small>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-form-actions">
                <button class="btn btn-dark rounded-pill px-3">Simpan Pengaturan</button>
            </div>
        </div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('input[name="logo_file"]');
    const preview = document.getElementById('logoPreview');
    const pathText = document.getElementById('logoPathText');

    if (!input || !preview || !pathText) return;

    input.addEventListener('change', function (event) {
        const file = event.target.files && event.target.files[0];
        if (!file) return;

        pathText.textContent = file.name;

        if (file.type === 'image/svg+xml') {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
            return;
        }

        const objectUrl = URL.createObjectURL(file);
        preview.src = objectUrl;
        preview.onload = function () {
            URL.revokeObjectURL(objectUrl);
        };
    });
});
</script>
<?= $this->endSection() ?>
