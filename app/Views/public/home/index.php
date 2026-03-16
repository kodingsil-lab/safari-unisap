<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>
<section class="hero position-relative overflow-hidden">
    <div class="hero-orb one"></div>
    <div class="hero-orb two"></div>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge text-bg-light border mb-3">Portal Formulir Resmi Universitas San Pedro</span>
                <h1 class="display-5 fw-bold mb-3">SAFARI UNISAP<br>Sistem Administrasi Formulir Universitas San Pedro</h1>
                <p class="lead text-secondary mb-4">Portal pengajuan formulir administrasi bagi dosen dan mahasiswa Universitas San Pedro.</p>
                <form action="<?= site_url('formulir') ?>" method="get" class="card-soft rounded-4 p-3 p-lg-4 mb-4">
                    <label class="form-label fw-semibold">Cari formulir kampus</label>
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="keyword" placeholder="Contoh: registrasi dosen, surat tugas, izin penelitian">
                        <button class="btn btn-dark px-4" type="submit">Cari Formulir</button>
                    </div>
                </form>
                <div class="d-flex gap-3 flex-wrap mb-4">
                    <a href="<?= site_url('formulir') ?>" class="btn btn-dark btn-lg rounded-pill px-4">Lihat Semua Formulir</a>
                    <a href="#kategori-layanan" class="btn btn-outline-dark btn-lg rounded-pill px-4">Lihat Kategori</a>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($categories as $category): ?>
                        <a href="<?= site_url('formulir?category=' . $category['slug']) ?>" class="btn btn-light border rounded-pill px-3 py-2">
                            <?= esc($category['nama_kategori']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card-soft rounded-4 p-4 p-lg-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-light h-100">
                                <div class="small text-secondary mb-1">Kategori</div>
                                <div class="fw-bold fs-4"><?= count($categories) ?></div>
                                <div class="small text-secondary">Kelompok layanan utama</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-light h-100">
                                <div class="small text-secondary mb-1">Formulir</div>
                                <div class="fw-bold fs-4"><?= esc($totalForms) ?></div>
                                <div class="small text-secondary">Siap diisi secara online</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-4 rounded-4 bg-light h-100">
                                <div class="icon-pill mb-3"><i class="bi bi-building-check"></i></div>
                                <h5 class="fw-bold mb-2">Layanan Formulir Terintegrasi</h5>
                                <p class="small text-secondary mb-0">Berbagai formulir layanan kampus tersedia dalam satu sistem untuk memudahkan pengajuan administrasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-5" id="kategori-layanan">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <div class="text-uppercase small text-secondary">Kategori Utama</div>
                <h2 class="fw-bold mb-0 public-section-title">Pilih sesuai kebutuhan layanan</h2>
            </div>
            <a href="<?= site_url('formulir') ?>" class="btn btn-outline-dark rounded-pill">Lihat Semua Formulir</a>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $index => $category): ?>
                <div class="col-md-6 col-xl-3">
                    <div class="card-soft rounded-4 p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="icon-pill"><i class="bi <?= ['bi-journal-bookmark', 'bi-people', 'bi-mortarboard', 'bi-folder2-open'][$index] ?? 'bi-grid' ?>"></i></div>
                            <span class="badge text-bg-light border"><?= esc($category['total_formulir']) ?> formulir</span>
                        </div>
                        <h5 class="fw-bold mb-2"><?= esc($category['nama_kategori']) ?></h5>
                        <p class="text-secondary small mb-4">
                            <?php
                            $descriptions = [
                                'akademik' => 'Layanan yang berkaitan dengan kegiatan akademik dosen dan kebutuhan pembelajaran.',
                                'kepegawaian' => 'Layanan administrasi dosen dan pegawai, mulai dari registrasi sampai surat keterangan.',
                                'kemahasiswaan' => 'Layanan yang mendukung kegiatan mahasiswa melalui pengajuan dari dosen atau unit.',
                                'administrasi' => 'Layanan umum yang berkaitan dengan surat, legalitas dokumen, dan permohonan data.',
                            ];
                            ?>
                            <?= esc($descriptions[$category['slug']] ?? 'Kategori layanan formulir kampus.') ?>
                        </p>
                        <a href="<?= site_url('formulir?category=' . $category['slug']) ?>" class="btn btn-outline-dark rounded-pill mt-auto">Lihat Formulir</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="card-soft rounded-4 p-4 p-lg-5">
            <div class="row g-4 text-center text-lg-start">
                <div class="col-lg-3">
                    <div class="icon-pill mx-lg-0 mx-auto mb-3"><i class="bi bi-search"></i></div>
                    <h5 class="fw-bold">1. Pilih Formulir</h5>
                    <p class="text-secondary mb-0">Tentukan formulir sesuai kategori dan layanan yang Anda butuhkan.</p>
                </div>
                <div class="col-lg-3">
                    <div class="icon-pill mx-lg-0 mx-auto mb-3"><i class="bi bi-pencil-square"></i></div>
                    <h5 class="fw-bold">2. Lengkapi Data</h5>
                    <p class="text-secondary mb-0">Isi data pengaju dan informasi yang diminta pada formulir.</p>
                </div>
                <div class="col-lg-3">
                    <div class="icon-pill mx-lg-0 mx-auto mb-3"><i class="bi bi-upload"></i></div>
                    <h5 class="fw-bold">3. Unggah Berkas</h5>
                    <p class="text-secondary mb-0">Lampirkan dokumen pendukung bila formulir membutuhkannya.</p>
                </div>
                <div class="col-lg-3">
                    <div class="icon-pill mx-lg-0 mx-auto mb-3"><i class="bi bi-patch-check"></i></div>
                    <h5 class="fw-bold">4. Simpan Nomor</h5>
                    <p class="text-secondary mb-0">Simpan nomor pengajuan dan bukti PDF sebagai arsip setelah formulir berhasil dikirim.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <div class="text-uppercase small text-secondary">Formulir Unggulan</div>
                <h2 class="fw-bold mb-0 public-section-title">Formulir yang sering digunakan</h2>
            </div>
            <a href="<?= site_url('formulir') ?>" class="btn btn-outline-dark rounded-pill">Semua Formulir</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredForms as $form): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card-soft rounded-4 p-4 h-100">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <div class="small text-secondary mb-2"><?= esc($form['category_name']) ?></div>
                                <h5 class="fw-bold mb-0"><?= esc($form['name']) ?></h5>
                            </div>
                            <div class="icon-pill"><i class="bi <?= esc($form['icon']) ?>"></i></div>
                        </div>
                        <p class="text-secondary"><?= esc(mb_strimwidth(strip_tags((string) $form['description']), 0, 125, '...')) ?></p>
                        <div class="d-flex gap-2 mt-auto flex-wrap">
                            <a href="<?= site_url('formulir/' . $form['slug']) ?>" class="btn btn-outline-dark rounded-pill">Detail</a>
                            <a href="<?= site_url('formulir/' . $form['slug'] . '/isi') ?>" class="btn btn-dark rounded-pill">Isi Formulir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
