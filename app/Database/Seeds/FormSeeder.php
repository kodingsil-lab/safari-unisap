<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $categories = [
            ['nama_kategori' => 'Akademik', 'slug' => 'akademik', 'urutan' => 1],
            ['nama_kategori' => 'Kepegawaian', 'slug' => 'kepegawaian', 'urutan' => 2],
            ['nama_kategori' => 'Kemahasiswaan', 'slug' => 'kemahasiswaan', 'urutan' => 3],
            ['nama_kategori' => 'Administrasi', 'slug' => 'administrasi', 'urutan' => 4],
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $this->db->table('form_categories')->insert($category + [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $categoryIds[$category['slug']] = (int) $this->db->insertID();
        }

        $forms = [
            ['category_slug' => 'kepegawaian', 'nama_form' => 'Form Registrasi Dosen Baru', 'slug' => 'registrasi-dosen-baru', 'kode_form' => 'REGDOS', 'deskripsi' => 'Form registrasi dosen baru Universitas San Pedro', 'icon' => 'bi-person-plus', 'template_pdf' => 'registrasi_dosen_baru', 'urutan' => 1],
            ['category_slug' => 'kepegawaian', 'nama_form' => 'Form Pengajuan Surat Cuti', 'slug' => 'pengajuan-surat-cuti', 'kode_form' => 'CUTI', 'deskripsi' => 'Form pengajuan surat cuti dosen', 'icon' => 'bi-file-earmark-text', 'template_pdf' => 'pengajuan_surat_cuti', 'urutan' => 2],
            ['category_slug' => 'akademik', 'nama_form' => 'Form Permohonan Surat Tugas', 'slug' => 'permohonan-surat-tugas', 'kode_form' => 'STUGAS', 'deskripsi' => 'Form permohonan surat tugas kegiatan', 'icon' => 'bi-briefcase', 'template_pdf' => 'permohonan_surat_tugas', 'urutan' => 3],
            ['category_slug' => 'akademik', 'nama_form' => 'Form Izin Penelitian', 'slug' => 'izin-penelitian', 'kode_form' => 'IZINLIT', 'deskripsi' => 'Form permohonan izin penelitian', 'icon' => 'bi-journal-text', 'template_pdf' => 'izin_penelitian', 'urutan' => 4],
        ];

        $formIds = [];
        foreach ($forms as $form) {
            $this->db->table('form_types')->insert([
                'category_id' => $categoryIds[$form['category_slug']],
                'nama_form' => $form['nama_form'],
                'slug' => $form['slug'],
                'kode_form' => $form['kode_form'],
                'deskripsi' => $form['deskripsi'],
                'icon' => $form['icon'],
                'template_pdf' => $form['template_pdf'],
                'is_active' => 1,
                'urutan' => $form['urutan'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $formIds[$form['slug']] = (int) $this->db->insertID();
        }

        $fields = [
            'registrasi-dosen-baru' => [
                ['nama_field' => 'applicant_name', 'label_field' => 'Nama Lengkap', 'tipe_field' => 'text', 'placeholder' => 'Nama lengkap sesuai identitas', 'is_required' => 1, 'urutan' => 1, 'validasi' => 'required|min_length[3]', 'help_text' => 'Gunakan nama resmi.'],
                ['nama_field' => 'email', 'label_field' => 'Email Aktif', 'tipe_field' => 'email', 'placeholder' => 'nama@domain.ac.id', 'is_required' => 1, 'urutan' => 2, 'validasi' => 'required|valid_email', 'help_text' => null],
                ['nama_field' => 'no_hp', 'label_field' => 'Nomor HP', 'tipe_field' => 'text', 'placeholder' => '08xxxxxxxxxx', 'is_required' => 1, 'urutan' => 3, 'validasi' => 'required|min_length[10]', 'help_text' => null],
                ['nama_field' => 'nidn_nip', 'label_field' => 'NIDN / NIP', 'tipe_field' => 'text', 'placeholder' => 'Isi jika tersedia', 'is_required' => 0, 'urutan' => 4, 'validasi' => 'permit_empty', 'help_text' => null],
                ['nama_field' => 'unit_kerja', 'label_field' => 'Unit Kerja', 'tipe_field' => 'text', 'placeholder' => 'Program studi / fakultas', 'is_required' => 1, 'urutan' => 5, 'validasi' => 'required', 'help_text' => null],
                ['nama_field' => 'cv_file', 'label_field' => 'CV / Daftar Riwayat Hidup', 'tipe_field' => 'file', 'placeholder' => null, 'is_required' => 1, 'urutan' => 6, 'validasi' => 'permit_empty', 'help_text' => 'Format PDF/JPG/PNG.'],
            ],
            'pengajuan-surat-cuti' => [
                ['nama_field' => 'applicant_name', 'label_field' => 'Nama Pemohon', 'tipe_field' => 'text', 'placeholder' => 'Nama lengkap', 'is_required' => 1, 'urutan' => 1, 'validasi' => 'required|min_length[3]', 'help_text' => null],
                ['nama_field' => 'email', 'label_field' => 'Email Pemohon', 'tipe_field' => 'email', 'placeholder' => 'nama@domain.ac.id', 'is_required' => 1, 'urutan' => 2, 'validasi' => 'required|valid_email', 'help_text' => null],
                ['nama_field' => 'no_hp', 'label_field' => 'Nomor HP', 'tipe_field' => 'text', 'placeholder' => '08xxxxxxxxxx', 'is_required' => 1, 'urutan' => 3, 'validasi' => 'required|min_length[10]', 'help_text' => null],
                ['nama_field' => 'unit_kerja', 'label_field' => 'Unit Kerja', 'tipe_field' => 'text', 'placeholder' => 'Fakultas / Unit', 'is_required' => 1, 'urutan' => 4, 'validasi' => 'required', 'help_text' => null],
                ['nama_field' => 'jenis_cuti', 'label_field' => 'Jenis Cuti', 'tipe_field' => 'select', 'placeholder' => null, 'opsi_json' => json_encode(['Cuti Tahunan', 'Cuti Sakit', 'Cuti Alasan Penting']), 'is_required' => 1, 'urutan' => 5, 'validasi' => 'required', 'help_text' => null],
                ['nama_field' => 'alasan_cuti', 'label_field' => 'Alasan Cuti', 'tipe_field' => 'textarea', 'placeholder' => 'Jelaskan alasan cuti', 'is_required' => 1, 'urutan' => 6, 'validasi' => 'required|min_length[10]', 'help_text' => null],
            ],
            'permohonan-surat-tugas' => [
                ['nama_field' => 'applicant_name', 'label_field' => 'Nama Pemohon', 'tipe_field' => 'text', 'placeholder' => 'Nama lengkap', 'is_required' => 1, 'urutan' => 1, 'validasi' => 'required|min_length[3]', 'help_text' => null],
                ['nama_field' => 'email', 'label_field' => 'Email Pemohon', 'tipe_field' => 'email', 'placeholder' => 'nama@domain.ac.id', 'is_required' => 1, 'urutan' => 2, 'validasi' => 'required|valid_email', 'help_text' => null],
                ['nama_field' => 'nama_kegiatan', 'label_field' => 'Nama Kegiatan', 'tipe_field' => 'text', 'placeholder' => 'Nama kegiatan resmi', 'is_required' => 1, 'urutan' => 3, 'validasi' => 'required', 'help_text' => null],
                ['nama_field' => 'lokasi_kegiatan', 'label_field' => 'Lokasi Kegiatan', 'tipe_field' => 'text', 'placeholder' => 'Lokasi kegiatan', 'is_required' => 1, 'urutan' => 4, 'validasi' => 'required', 'help_text' => null],
                ['nama_field' => 'tanggal_kegiatan', 'label_field' => 'Tanggal Kegiatan', 'tipe_field' => 'date', 'placeholder' => null, 'is_required' => 1, 'urutan' => 5, 'validasi' => 'required', 'help_text' => null],
                ['nama_field' => 'dokumen_tugas', 'label_field' => 'Lampiran Undangan / TOR', 'tipe_field' => 'file', 'placeholder' => null, 'is_required' => 0, 'urutan' => 6, 'validasi' => 'permit_empty', 'help_text' => 'Format PDF/JPG/PNG.'],
            ],
            'izin-penelitian' => [
                ['nama_field' => 'applicant_name', 'label_field' => 'Nama Peneliti', 'tipe_field' => 'text', 'placeholder' => 'Nama lengkap', 'is_required' => 1, 'urutan' => 1, 'validasi' => 'required|min_length[3]', 'help_text' => null],
                ['nama_field' => 'email', 'label_field' => 'Email Peneliti', 'tipe_field' => 'email', 'placeholder' => 'nama@domain.ac.id', 'is_required' => 1, 'urutan' => 2, 'validasi' => 'required|valid_email', 'help_text' => null],
                ['nama_field' => 'institusi', 'label_field' => 'Institusi', 'tipe_field' => 'text', 'placeholder' => 'Asal institusi', 'is_required' => 1, 'urutan' => 3, 'validasi' => 'required', 'help_text' => null],
                ['nama_field' => 'judul_penelitian', 'label_field' => 'Judul Penelitian', 'tipe_field' => 'text', 'placeholder' => 'Judul penelitian', 'is_required' => 1, 'urutan' => 4, 'validasi' => 'required|min_length[10]', 'help_text' => null],
                ['nama_field' => 'lokasi_penelitian', 'label_field' => 'Lokasi Penelitian', 'tipe_field' => 'textarea', 'placeholder' => 'Objek atau lokasi penelitian', 'is_required' => 1, 'urutan' => 5, 'validasi' => 'required|min_length[10]', 'help_text' => null],
                ['nama_field' => 'proposal_file', 'label_field' => 'Proposal Penelitian', 'tipe_field' => 'file', 'placeholder' => null, 'is_required' => 1, 'urutan' => 6, 'validasi' => 'permit_empty', 'help_text' => 'Format PDF.'],
            ],
        ];

        foreach ($fields as $slug => $items) {
            foreach ($items as $item) {
                $this->db->table('form_fields')->insert($item + [
                    'form_type_id' => $formIds[$slug],
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
