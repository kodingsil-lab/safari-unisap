<?php

namespace App\Models;

class AdminModel extends BaseModel
{
    protected $table = 'admins';
    protected $allowedFields = [
        'nama',
        'email',
        'username',
        'password_hash',
        'role',
        'last_login_at',
        'is_active',
    ];
}
