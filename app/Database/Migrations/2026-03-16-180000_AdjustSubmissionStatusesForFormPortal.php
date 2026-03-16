<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdjustSubmissionStatusesForFormPortal extends Migration
{
    public function up()
    {
        $statusMap = [
            'submitted' => 'masuk',
            'menunggu_verifikasi' => 'masuk',
            'diproses' => 'ditinjau',
            'perlu_perbaikan' => 'ditinjau',
            'disetujui' => 'ditindaklanjuti',
            'ditolak' => 'arsip',
            'selesai' => 'arsip',
        ];

        foreach ($statusMap as $old => $new) {
            $this->db->table('submissions')
                ->where('status', $old)
                ->update(['status' => $new]);

            $this->db->table('submission_logs')
                ->where('status_lama', $old)
                ->update(['status_lama' => $new]);

            $this->db->table('submission_logs')
                ->where('status_baru', $old)
                ->update(['status_baru' => $new]);
        }

        $this->db->query("ALTER TABLE submissions MODIFY status VARCHAR(50) NOT NULL DEFAULT 'masuk'");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE submissions MODIFY status VARCHAR(50) NOT NULL DEFAULT 'submitted'");
    }
}
