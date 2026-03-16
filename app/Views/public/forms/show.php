<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-soft rounded-4 p-4 p-lg-5">
                    <span class="badge text-bg-light border mb-3"><?= esc($form['category_name']) ?></span>
                    <h1 class="fw-bold"><?= esc($form['name']) ?></h1>
                    <p class="lead text-secondary"><?= esc($form['description']) ?></p>
                    <hr>
                    <h5 class="fw-bold public-section-title">Informasi</h5>
                    <p class="text-secondary mb-0">Silakan isi data dengan lengkap dan pastikan dokumen pendukung sudah siap sebelum mengirim pengajuan.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-soft rounded-4 p-4">
                    <h5 class="fw-bold public-section-title">Informasi Formulir</h5>
                    <p class="text-secondary">Jumlah isian: <?= count($fields) ?> pertanyaan.</p>
                    <a href="<?= site_url('formulir/' . $form['slug'] . '/isi') ?>" class="btn btn-dark rounded-pill w-100">Mulai Isi Formulir</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
