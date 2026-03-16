<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="py-5">
    <div class="container">
        <div class="card-soft rounded-4 p-4 p-lg-5 text-center">
            <div class="icon-pill mx-auto mb-4"><i class="bi bi-check2-circle"></i></div>
            <h1 class="fw-bold">Pengajuan Berhasil Dikirim</h1>
            <p class="text-secondary">Simpan nomor pengajuan berikut sebagai arsip dan unduh bukti PDF bila diperlukan.</p>
            <div class="display-6 fw-bold my-4"><?= esc($submission['submission_code']) ?></div>
            <p class="mb-4">Formulir: <strong><?= esc($submission['form_name']) ?></strong></p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?= site_url('formulir') ?>" class="btn btn-outline-dark rounded-pill px-4">Lihat Formulir Lain</a>
                <a href="<?= site_url('bukti/' . $submission['submission_code']) ?>" class="btn btn-dark rounded-pill px-4">Unduh Bukti PDF</a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
