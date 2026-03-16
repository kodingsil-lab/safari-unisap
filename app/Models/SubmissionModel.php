<?php

namespace App\Models;

class SubmissionModel extends BaseModel
{
    protected $table = 'submissions';
    protected $allowedFields = [
        'form_type_id',
        'kode_pengajuan',
        'nama_pengaju',
        'nidn_nip',
        'unit_kerja',
        'email',
        'no_hp',
        'tanggal_pengajuan',
        'status',
        'catatan_admin',
        'submitted_ip',
        'submitted_user_agent',
        'is_archived',
    ];

    public function withRelations()
    {
        return $this->select('submissions.*, submissions.kode_pengajuan AS submission_code, submissions.nama_pengaju AS applicant_name, submissions.email AS applicant_email, submissions.no_hp AS applicant_phone, submissions.tanggal_pengajuan AS submitted_at, submissions.catatan_admin AS admin_notes, form_types.nama_form AS form_name, form_types.slug AS form_slug')
            ->join('form_types', 'form_types.id = submissions.form_type_id', 'left')
            ->orderBy('submissions.tanggal_pengajuan', 'DESC');
    }

    public function applyAdminFilters(array $filters)
    {
        $builder = $this->withRelations();

        if (! empty($filters['category_id'])) {
            $builder->where('form_types.category_id', $filters['category_id']);
        }

        if (! empty($filters['form_type_id'])) {
            $builder->where('submissions.form_type_id', $filters['form_type_id']);
        }

        if (! empty($filters['date_from'])) {
            $builder->where('DATE(submissions.tanggal_pengajuan) >=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $builder->where('DATE(submissions.tanggal_pengajuan) <=', $filters['date_to']);
        }

        if (! empty($filters['keyword'])) {
            $keyword = trim((string) $filters['keyword']);
            $builder->groupStart()
                ->like('submissions.kode_pengajuan', $keyword)
                ->orLike('submissions.nama_pengaju', $keyword)
                ->orLike('submissions.email', $keyword)
                ->groupEnd();
        }

        return $builder;
    }
}
