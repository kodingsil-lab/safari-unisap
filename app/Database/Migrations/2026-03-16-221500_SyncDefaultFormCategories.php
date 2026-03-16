<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SyncDefaultFormCategories extends Migration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');
        $categories = [
            'akademik' => [
                'nama_kategori' => 'Akademik',
                'slug' => 'akademik',
                'urutan' => 1,
                'is_active' => 1,
            ],
            'kepegawaian' => [
                'nama_kategori' => 'Kepegawaian',
                'slug' => 'kepegawaian',
                'urutan' => 2,
                'is_active' => 1,
            ],
            'kemahasiswaan' => [
                'nama_kategori' => 'Kemahasiswaan',
                'slug' => 'kemahasiswaan',
                'urutan' => 3,
                'is_active' => 1,
            ],
            'administrasi' => [
                'nama_kategori' => 'Administrasi',
                'slug' => 'administrasi',
                'urutan' => 4,
                'is_active' => 1,
            ],
        ];

        $categoryTable = $this->db->table('form_categories');
        $categoryIds = [];

        foreach ($categories as $slug => $payload) {
            $existing = $categoryTable->where('slug', $slug)->get()->getRowArray();

            if ($existing) {
                $categoryTable->where('id', $existing['id'])->update($payload + ['updated_at' => $now]);
                $categoryIds[$slug] = (int) $existing['id'];
                continue;
            }

            $categoryTable->insert($payload + [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $categoryIds[$slug] = (int) $this->db->insertID();
        }

        $formMap = [
            'registrasi-dosen-baru' => 'kepegawaian',
            'pengajuan-surat-cuti' => 'kepegawaian',
            'permohonan-surat-tugas' => 'akademik',
            'izin-penelitian' => 'akademik',
        ];

        $formTable = $this->db->table('form_types');
        foreach ($formMap as $formSlug => $categorySlug) {
            if (! isset($categoryIds[$categorySlug])) {
                continue;
            }

            $formTable->where('slug', $formSlug)->update([
                'category_id' => $categoryIds[$categorySlug],
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        // Perubahan ini bersifat sinkronisasi data, jadi tidak dibalik otomatis.
    }
}
