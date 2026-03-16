<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 admin-page-head">
    <div>
        <div class="text-secondary small">Manajemen Formulir</div>
        <h3 class="fw-bold mb-0">Jenis Formulir</h3>
    </div>
    <div class="d-flex gap-2 btn-toolbar-slim">
        <a href="<?= site_url('admin/form-categories') ?>" class="btn btn-outline-secondary rounded-pill px-3">Atur Kategori</a>
        <a href="<?= site_url('admin/forms/create') ?>" class="btn btn-dark rounded-pill px-3">Tambah Jenis Form</a>
    </div>
</div>
<div class="panel p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:32%;">Nama</th><th style="width:15%;">Kategori</th><th style="width:10%;">Kode</th><th style="width:12%;">Jumlah Isian</th><th style="width:10%;">Status</th><th style="width:21%;">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($formTypes as $item): ?>
                <tr>
                    <td><?= esc($item['name']) ?><div class="small text-secondary">Kode URL: <?= esc($item['slug']) ?></div></td>
                    <td><?= esc($item['category_name']) ?></td>
                    <td><?= esc($item['code']) ?></td>
                    <td><?= esc($fieldCounts[$item['id']] ?? 0) ?></td>
                    <td><span class="badge text-bg-<?= $item['is_active'] ? 'success' : 'secondary' ?>"><?= $item['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                    <td>
                        <div class="btn-toolbar-row btn-toolbar-slim">
                        <a href="<?= site_url('admin/forms/' . $item['id'] . '/fields') ?>" class="btn btn-sm btn-primary rounded-pill">Atur Isian</a>
                        <a href="<?= site_url('admin/forms/edit/' . $item['id']) ?>" class="btn btn-sm btn-outline-dark rounded-pill">Edit</a>
                        <form action="<?= site_url('admin/forms/toggle/' . $item['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill"><?= $item['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                        </form>
                        <form action="<?= site_url('admin/forms/delete/' . $item['id']) ?>" method="post" onsubmit="return confirm('Hapus jenis formulir ini?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                        </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($formTypes === []): ?>
                <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada jenis formulir.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
