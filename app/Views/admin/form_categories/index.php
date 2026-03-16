<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 admin-page-head">
    <div>
        <div class="text-secondary small">Manajemen Kategori</div>
        <h3 class="fw-bold mb-0">Kategori Formulir</h3>
    </div>
    <a href="<?= site_url('admin/form-categories/create') ?>" class="btn btn-dark rounded-pill px-3">Tambah Kategori</a>
</div>
<div class="panel p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:34%;">Nama Kategori</th><th style="width:24%;">Kode URL</th><th style="width:12%;">Urutan</th><th style="width:14%;">Jumlah Form</th><th style="width:10%;">Status</th><th style="width:16%;">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($categories as $item): ?>
                <tr>
                    <td><?= esc($item['nama_kategori']) ?></td>
                    <td><?= esc($item['slug']) ?></td>
                    <td><?= esc($item['urutan']) ?></td>
                    <td><?= esc($formCounts[$item['id']] ?? 0) ?></td>
                    <td><span class="badge text-bg-<?= $item['is_active'] ? 'success' : 'secondary' ?>"><?= $item['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                    <td>
                        <div class="btn-toolbar-row btn-toolbar-slim">
                            <a href="<?= site_url('admin/form-categories/edit/' . $item['id']) ?>" class="btn btn-sm btn-outline-dark rounded-pill">Edit</a>
                            <form action="<?= site_url('admin/form-categories/toggle/' . $item['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill"><?= $item['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                            </form>
                            <form action="<?= site_url('admin/form-categories/delete/' . $item['id']) ?>" method="post" onsubmit="return confirm('Hapus kategori formulir ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($categories === []): ?>
                <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada kategori formulir.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
