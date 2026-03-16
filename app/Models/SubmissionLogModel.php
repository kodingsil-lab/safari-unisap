<?php

namespace App\Models;

class SubmissionLogModel extends BaseModel
{
    protected $table = 'submission_logs';
    protected $allowedFields = [
        'submission_id',
        'status_lama',
        'status_baru',
        'catatan',
        'changed_by_admin_id',
        'changed_at',
    ];
}
