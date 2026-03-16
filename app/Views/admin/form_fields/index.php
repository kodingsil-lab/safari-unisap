<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <div class="text-secondary small">Pengaturan Isian Formulir</div>
        <h3 class="fw-bold mb-1"><?= esc($formType['nama_form']) ?></h3>
        <div class="text-secondary">Atur isian, jenis jawaban, petunjuk, dan urutan tampil formulir.</div>
    </div>
    <div class="d-flex gap-2 btn-toolbar-slim">
        <a href="<?= site_url('admin/forms') ?>" class="btn btn-outline-secondary rounded-pill px-3">Kembali</a>
        <a href="<?= site_url('admin/forms/' . $formType['id'] . '/fields/create') ?>" class="btn btn-dark rounded-pill px-3">Tambah Isian</a>
    </div>
</div>

<div class="panel p-4 mb-4">
    <form action="<?= site_url('admin/forms/' . $formType['id'] . '/fields/reorder') ?>" method="post">
        <?= csrf_field() ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Label</th>
                        <th>Nama Sistem</th>
                        <th>Jenis Jawaban</th>
                        <th>Wajib Diisi</th>
                        <th>Status</th>
                        <th>Aturan Isi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fields as $field): ?>
                        <tr>
                            <td style="width: 90px;">
                                <input type="number" class="form-control form-control-sm" name="orders[<?= esc($field['id']) ?>]" value="<?= esc($field['urutan']) ?>">
                            </td>
                            <td>
                                <div class="fw-semibold"><?= esc($field['label_field']) ?></div>
                                <?php if ($field['help_text']): ?>
                                    <div class="small text-secondary"><?= esc($field['help_text']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><code><?= esc($field['nama_field']) ?></code></td>
                            <td><span class="badge text-bg-primary-subtle text-primary-emphasis"><?= esc($fieldTypes[$field['tipe_field']] ?? $field['tipe_field']) ?></span></td>
                            <td><?= $field['is_required'] ? 'Ya' : 'Tidak' ?></td>
                            <td><span class="badge text-bg-<?= $field['is_active'] ? 'success' : 'secondary' ?>"><?= $field['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                            <td><small><?= esc($field['validasi'] ?: 'Tidak ada aturan khusus') ?></small></td>
                            <td>
                                <div class="btn-toolbar-row btn-toolbar-slim">
                                <a href="<?= site_url('admin/forms/' . $formType['id'] . '/fields/edit/' . $field['id']) ?>" class="btn btn-sm btn-outline-dark rounded-pill">Edit</a>
                                <form action="<?= site_url('admin/forms/' . $formType['id'] . '/fields/delete/' . $field['id']) ?>" method="post" onsubmit="return confirm('Hapus field ini?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($fields === []): ?>
                        <tr><td colspan="8" class="text-center text-secondary py-4">Belum ada isian untuk formulir ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="pt-3">
            <button class="btn btn-primary rounded-pill px-3">Simpan Urutan Isian</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
