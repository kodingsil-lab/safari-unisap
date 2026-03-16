<?php

namespace App\Models;

class FormTypeModel extends BaseModel
{
    protected $table = 'form_types';
    protected $allowedFields = [
        'category_id',
        'nama_form',
        'slug',
        'kode_form',
        'deskripsi',
        'icon',
        'template_pdf',
        'urutan',
        'is_active',
    ];

    public function getActiveWithCategory(?string $slug = null): array
    {
        $builder = $this->select('form_types.*, form_types.nama_form AS name, form_types.kode_form AS code, form_types.deskripsi AS description, form_types.urutan AS sort_order, form_categories.nama_kategori AS category_name, form_categories.slug AS category_slug')
            ->join('form_categories', 'form_categories.id = form_types.category_id', 'left')
            ->where('form_types.is_active', 1)
            ->where('form_categories.is_active', 1)
            ->orderBy('form_categories.urutan', 'ASC')
            ->orderBy('form_types.urutan', 'ASC');

        if ($slug !== null) {
            return $builder->where('form_types.slug', $slug)->first() ?? [];
        }

        return $builder->findAll();
    }

    public function getByCategory(int $categoryId): array
    {
        return $this->select('form_types.*, form_types.nama_form AS name, form_types.kode_form AS code, form_types.deskripsi AS description, form_types.urutan AS sort_order, form_categories.nama_kategori AS category_name, form_categories.slug AS category_slug')
            ->join('form_categories', 'form_categories.id = form_types.category_id', 'left')
            ->where('form_types.category_id', $categoryId)
            ->orderBy('form_types.urutan', 'ASC')
            ->findAll();
    }
}
