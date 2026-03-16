<?php

namespace App\Models;

class FormFieldModel extends BaseModel
{
    protected $table = 'form_fields';
    protected $allowedFields = [
        'form_type_id',
        'nama_field',
        'label_field',
        'tipe_field',
        'placeholder',
        'opsi_json',
        'is_required',
        'urutan',
        'validasi',
        'help_text',
        'is_active',
    ];

    public function getByFormType(int $formTypeId): array
    {
        return $this->select('form_fields.*, form_fields.nama_field AS name, form_fields.label_field AS label, form_fields.tipe_field AS field_type, form_fields.opsi_json AS options, form_fields.validasi AS validation_rules, form_fields.urutan AS sort_order')
            ->where('form_type_id', $formTypeId)
            ->where('is_active', 1)
            ->orderBy('urutan', 'ASC')
            ->findAll();
    }
}
