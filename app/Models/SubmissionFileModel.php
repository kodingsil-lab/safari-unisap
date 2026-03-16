<?php

namespace App\Models;

class SubmissionFileModel extends BaseModel
{
    protected $table = 'submission_files';
    protected $allowedFields = [
        'submission_id',
        'form_field_id',
        'nama_file_asli',
        'nama_file_simpan',
        'path_file',
        'mime_type',
        'ukuran_file',
    ];
}
