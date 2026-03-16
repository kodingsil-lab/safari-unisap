<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Libraries\PdfGenerator;
use App\Models\SubmissionFileModel;
use App\Models\SubmissionModel;
use App\Models\SubmissionValueModel;

class StatusController extends BaseController
{
    public function proof(string $kodePengajuan)
    {
        $submission = (new SubmissionModel())
            ->withRelations()
            ->where('kode_pengajuan', trim($kodePengajuan))
            ->first();

        if (! $submission) {
            return redirect()->to(site_url('/'))->with('error', 'Bukti pengajuan tidak ditemukan.');
        }

        $payload = $this->buildSubmissionPayload($submission);
        $html = view('pdf/submission_receipt', $payload);

        return (new PdfGenerator())->output($html, 'bukti-' . $submission['submission_code'] . '.pdf');
    }

    private function buildSubmissionPayload(array $submission): array
    {
        return [
            'submission' => $submission,
            'values' => (new SubmissionValueModel())
                ->select('submission_values.*, form_fields.label_field')
                ->join('form_fields', 'form_fields.id = submission_values.form_field_id', 'left')
                ->where('submission_id', $submission['id'])
                ->findAll(),
            'files' => (new SubmissionFileModel())
                ->select('submission_files.*, form_fields.label_field')
                ->join('form_fields', 'form_fields.id = submission_files.form_field_id', 'left')
                ->where('submission_id', $submission['id'])
                ->findAll(),
        ];
    }
}
