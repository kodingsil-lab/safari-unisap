<?php

namespace App\Models;

class SubmissionValueModel extends BaseModel
{
    protected $table = 'submission_values';
    protected $allowedFields = [
        'submission_id',
        'form_field_id',
        'nama_field',
        'nilai_text',
        'nilai_longtext',
        'nilai_date',
        'nilai_number',
        'nilai_json',
    ];
}
