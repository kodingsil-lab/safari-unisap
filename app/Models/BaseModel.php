<?php

namespace App\Models;

use CodeIgniter\Model;

abstract class BaseModel extends Model
{
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $returnType = 'array';
}
