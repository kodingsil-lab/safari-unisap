<?php

namespace App\Libraries;

use App\Models\SubmissionModel;

class SubmissionCodeService
{
    public function generate(): string
    {
        $datePart = date('Ymd');
        $model = new SubmissionModel();
        $count = $model->like('kode_pengajuan', 'FRM-UNISAP-' . $datePart . '-', 'after')->countAllResults();
        $runningNumber = str_pad((string) ($count + 1), 4, '0', STR_PAD_LEFT);

        return 'FRM-UNISAP-' . $datePart . '-' . $runningNumber;
    }
}
