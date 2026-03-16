<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h1 class="fw-bold">Daftar Formulir</h1>
            <p class="text-secondary">Pilih layanan yang sesuai, baca detailnya, lalu isi formulir secara online.</p>
        </div>
        <div class="card-soft rounded-4 p-4 mb-4">
            <form method="get" class="row g-3">
                <div class="col-lg-5">
                    <label class="form-label fw-semibold">Pencarian</label>
                    <input type="text" name="keyword" class="form-control form-control-lg" value="<?= esc($filters['keyword'] ?? '') ?>" placeholder="Cari nama formulir, deskripsi, atau kategori">
                </div>
                <div class="col-lg-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="category" class="form-select form-select-lg">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc($category['slug']) ?>" <?= ($filters['category'] ?? '') === $category['slug'] ? 'selected' : '' ?>><?= esc($category['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-4 d-flex align-items-end gap-2">
                    <button class="btn btn-dark btn-lg w-100 rounded-pill">Cari Formulir</button>
                    <a href="<?= site_url('formulir') ?>" class="btn btn-outline-secondary btn-lg rounded-pill">Reset</a>
                </div>
            </form>
        </div>
        <div class="row g-4">
            <?php foreach ($forms as $form): ?>
                <div class="col-md-6">
                    <div class="card-soft rounded-4 p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div>
                                <div class="small text-secondary mb-2"><?= esc($form['category_name']) ?></div>
                                <h4 class="fw-bold"><?= esc($form['name']) ?></h4>
                            </div>
                            <div class="icon-pill"><i class="bi <?= esc($form['icon']) ?>"></i></div>
                        </div>
                        <p class="text-secondary"><?= esc(mb_strimwidth(strip_tags((string) $form['description']), 0, 140, '...')) ?></p>
                        <div class="d-flex gap-2 mt-auto flex-wrap">
                            <a class="btn btn-outline-dark rounded-pill" href="<?= site_url('formulir/' . $form['slug']) ?>">Detail</a>
                            <a class="btn btn-dark rounded-pill" href="<?= site_url('formulir/' . $form['slug'] . '/isi') ?>">Isi Formulir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($forms === []): ?>
                <div class="col-12">
                    <div class="card-soft rounded-4 p-5 text-center text-secondary">Tidak ada formulir yang cocok dengan pencarian Anda.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
