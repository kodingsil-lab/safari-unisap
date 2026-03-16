<?php

namespace App\Models;

class FormCategoryModel extends BaseModel
{
    protected $table = 'form_categories';
    protected $allowedFields = ['nama_kategori', 'slug', 'urutan', 'is_active'];

    public function getActiveCategories(): array
    {
        return $this->where('is_active', 1)
            ->orderBy('urutan', 'ASC')
            ->findAll();
    }
}
