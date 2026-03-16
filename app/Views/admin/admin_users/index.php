<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center admin-page-head">
    <div>
        <div class="text-secondary small">Manajemen Pengguna</div>
        <h3 class="fw-bold mb-0">Pengguna Admin</h3>
    </div>
    <a href="<?= site_url('admin/admin-users/create') ?>" class="btn btn-dark rounded-pill px-3">Tambah Admin</a>
</div>
<div class="panel p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama</th><th>Email</th><th>Username</th><th>Peran</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($admins as $item): ?>
                    <tr>
                        <td><?= esc($item['nama']) ?></td>
                        <td><?= esc($item['email']) ?></td>
                        <td><?= esc($item['username']) ?></td>
                        <td><span class="badge text-bg-primary"><?= esc($item['role']) ?></span></td>
                        <td><span class="badge text-bg-<?= $item['is_active'] ? 'success' : 'secondary' ?>"><?= $item['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
                        <td>
                            <div class="btn-toolbar-row btn-toolbar-slim">
                            <a href="<?= site_url('admin/admin-users/edit/' . $item['id']) ?>" class="btn btn-sm btn-outline-dark rounded-pill">Edit</a>
                            <form action="<?= site_url('admin/admin-users/toggle/' . $item['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill"><?= $item['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                            </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
