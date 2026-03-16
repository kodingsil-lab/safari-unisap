<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SimulationRegistrasiDosenSeeder extends Seeder
{
    public function run()
    {
        $form = $this->db->table('form_types')
            ->where('slug', 'registrasi-dosen-baru')
            ->get()
            ->getRowArray();

        if (! $form) {
            throw new \RuntimeException('Form Registrasi Dosen Baru belum tersedia.');
        }

        $fields = $this->db->table('form_fields')
            ->where('form_type_id', $form['id'])
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResultArray();

        if ($fields === []) {
            throw new \RuntimeException('Field Form Registrasi Dosen Baru belum tersedia.');
        }

        $fieldMap = [];
        foreach ($fields as $field) {
            $fieldMap[$field['nama_field']] = $field;
        }

        $samples = $this->sampleRows();
        $now = date('Y-m-d H:i:s');

        foreach ($samples as $index => $sample) {
            $submittedAt = date('Y-m-d H:i:s', strtotime('-' . (14 - $index) . ' days 09:' . str_pad((string) (($index % 5) * 7), 2, '0', STR_PAD_LEFT) . ':00'));
            $code = 'FRM-UNISAP-' . date('Ymd', strtotime($submittedAt)) . '-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);

            $exists = $this->db->table('submissions')
                ->where('kode_pengajuan', $code)
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            $this->db->table('submissions')->insert([
                'form_type_id' => $form['id'],
                'kode_pengajuan' => $code,
                'nama_pengaju' => $sample['nama_pengaju'],
                'nidn_nip' => $sample['nidn_nip'],
                'unit_kerja' => $sample['unit_kerja'],
                'email' => $sample['email'],
                'no_hp' => $sample['no_hp'],
                'tanggal_pengajuan' => $submittedAt,
                'catatan_admin' => null,
                'submitted_ip' => '127.0.0.1',
                'submitted_user_agent' => 'Seeder Simulation Registrasi Dosen Baru',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $submissionId = (int) $this->db->insertID();

            foreach (['applicant_name', 'email', 'no_hp', 'nidn_nip', 'unit_kerja'] as $fieldName) {
                if (! isset($fieldMap[$fieldName])) {
                    continue;
                }

                $this->db->table('submission_values')->insert([
                    'submission_id' => $submissionId,
                    'form_field_id' => $fieldMap[$fieldName]['id'],
                    'nama_field' => $fieldName,
                    'nilai_text' => (string) $sample[$fieldName === 'applicant_name' ? 'nama_pengaju' : $fieldName],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if (isset($fieldMap['cv_file'])) {
                $storedName = 'cv-' . strtolower(str_replace(' ', '-', $sample['nama_pengaju'])) . '.pdf';
                $this->db->table('submission_files')->insert([
                    'submission_id' => $submissionId,
                    'form_field_id' => $fieldMap['cv_file']['id'],
                    'nama_file_asli' => 'CV-' . $sample['nama_pengaju'] . '.pdf',
                    'nama_file_simpan' => $storedName,
                    'path_file' => 'uploads/registrasi-dosen-baru/' . $code . '/' . $storedName,
                    'mime_type' => 'application/pdf',
                    'ukuran_file' => 245760,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function sampleRows(): array
    {
        return [
            ['nama_pengaju' => 'Anita Lestari', 'nidn_nip' => '1986011201', 'unit_kerja' => 'Fakultas Teknik', 'email' => 'anita.lestari@unisap.ac.id', 'no_hp' => '081234560101'],
            ['nama_pengaju' => 'Budi Santoso', 'nidn_nip' => '1987021402', 'unit_kerja' => 'Fakultas Ekonomi', 'email' => 'budi.santoso@unisap.ac.id', 'no_hp' => '081234560102'],
            ['nama_pengaju' => 'Citra Maharani', 'nidn_nip' => '1988031603', 'unit_kerja' => 'Fakultas Hukum', 'email' => 'citra.maharani@unisap.ac.id', 'no_hp' => '081234560103'],
            ['nama_pengaju' => 'Deni Pratama', 'nidn_nip' => '1989041804', 'unit_kerja' => 'Fakultas Pertanian', 'email' => 'deni.pratama@unisap.ac.id', 'no_hp' => '081234560104'],
            ['nama_pengaju' => 'Eka Wulandari', 'nidn_nip' => '1990052005', 'unit_kerja' => 'Fakultas Keguruan', 'email' => 'eka.wulandari@unisap.ac.id', 'no_hp' => '081234560105'],
            ['nama_pengaju' => 'Fajar Nugroho', 'nidn_nip' => '1991062206', 'unit_kerja' => 'Fakultas Teknik', 'email' => 'fajar.nugroho@unisap.ac.id', 'no_hp' => '081234560106'],
            ['nama_pengaju' => 'Gita Permata', 'nidn_nip' => '1992072407', 'unit_kerja' => 'Fakultas Ilmu Sosial', 'email' => 'gita.permata@unisap.ac.id', 'no_hp' => '081234560107'],
            ['nama_pengaju' => 'Hendra Wijaya', 'nidn_nip' => '1993082608', 'unit_kerja' => 'Fakultas Kedokteran', 'email' => 'hendra.wijaya@unisap.ac.id', 'no_hp' => '081234560108'],
            ['nama_pengaju' => 'Indah Puspita', 'nidn_nip' => '1994092809', 'unit_kerja' => 'Fakultas Teknik', 'email' => 'indah.puspita@unisap.ac.id', 'no_hp' => '081234560109'],
            ['nama_pengaju' => 'Joko Saputra', 'nidn_nip' => '1995103010', 'unit_kerja' => 'Fakultas Ekonomi', 'email' => 'joko.saputra@unisap.ac.id', 'no_hp' => '081234560110'],
            ['nama_pengaju' => 'Kurnia Dewi', 'nidn_nip' => '1996110211', 'unit_kerja' => 'Fakultas Hukum', 'email' => 'kurnia.dewi@unisap.ac.id', 'no_hp' => '081234560111'],
            ['nama_pengaju' => 'Lukman Hakim', 'nidn_nip' => '1997120412', 'unit_kerja' => 'Fakultas Pertanian', 'email' => 'lukman.hakim@unisap.ac.id', 'no_hp' => '081234560112'],
            ['nama_pengaju' => 'Maya Sari', 'nidn_nip' => '1998010613', 'unit_kerja' => 'Fakultas Keguruan', 'email' => 'maya.sari@unisap.ac.id', 'no_hp' => '081234560113'],
            ['nama_pengaju' => 'Nanda Putri', 'nidn_nip' => '1999020814', 'unit_kerja' => 'Fakultas Ilmu Sosial', 'email' => 'nanda.putri@unisap.ac.id', 'no_hp' => '081234560114'],
            ['nama_pengaju' => 'Oki Ramadhan', 'nidn_nip' => '2000031015', 'unit_kerja' => 'Fakultas Kedokteran', 'email' => 'oki.ramadhan@unisap.ac.id', 'no_hp' => '081234560115'],
        ];
    }
}
