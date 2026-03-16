<?php

namespace App\Models;

class ActivityLogModel extends BaseModel
{
    protected $table = 'activity_logs';
    protected $allowedFields = [
        'admin_id',
        'aktivitas',
        'referensi_tabel',
        'referensi_id',
        'ip_address',
    ];
}
