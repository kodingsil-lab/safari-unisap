<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\SubmissionModel;

class SubmissionController extends BaseController
{
    public function success(string $code)
    {
        $submission = (new SubmissionModel())->withRelations()->where('kode_pengajuan', $code)->first();

        if (! $submission) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('public/forms/success', [
            'submission' => $submission,
        ]);
    }
}
