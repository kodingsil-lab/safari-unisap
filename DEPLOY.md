# Deploy SAFARI UNISAP

Pola deploy yang dipakai:

- source code disimpan di `~/apps/safari-unisap/releases/{timestamp}`
- symlink aktif ada di `~/apps/safari-unisap/current`
- folder web root tetap:
  - `/home/u541589701/domains/safari.unisap.ac.id/public_html`
- isi folder `public` dari release aktif akan disalin ke `public_html`
- file yang dipertahankan antar deploy:
  - `.env`
  - `writable/`
  - `public/uploads/`

## 1. Upload script deploy ke server

Simpan script ini di:

`~/bin/safari_unisap-deploy`

Sumber script:

`deploy/safari_unisap-deploy.sh`

Lalu beri izin eksekusi:

```bash
chmod +x ~/bin/safari_unisap-deploy
```

## 2. Jalankan deploy

```bash
~/bin/safari_unisap-deploy
```

Atau jika ingin branch tertentu:

```bash
~/bin/safari_unisap-deploy main
```

## 3. Edit konfigurasi produksi

File produksi akan disimpan di:

```bash
~/apps/safari-unisap/shared/.env
```

Yang wajib diisi:

- database host
- database name
- database user
- database password
- email Gmail / SMTP

## 4. Catatan penting

- Pastikan `composer` tersedia di server.
- Pastikan domain `safari.unisap.ac.id` sudah mengarah ke folder `public_html`.
- Script deploy ini tidak lagi mengandalkan symlink `public_html`, jadi lebih aman untuk Hostinger.
- File `public_html/index.php` akan otomatis disesuaikan agar mengarah ke `~/apps/safari-unisap/current/app/Config/Paths.php`.
- Upload logo kampus dari aplikasi akan tetap aman karena `public/uploads/` ikut dishare.
- Lampiran formulir tetap aman karena `writable/uploads/` ikut dishare.
