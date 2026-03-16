<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FormTypeModel;
use App\Models\SubmissionModel;

class ReportController extends BaseController
{
    public function index()
    {
        $submissionModel = new SubmissionModel();
        $db = db_connect();

        $monthly = $db->table('submissions')
            ->select("DATE_FORMAT(tanggal_pengajuan, '%Y-%m') AS bulan, COUNT(*) AS total")
            ->groupBy("DATE_FORMAT(tanggal_pengajuan, '%Y-%m')")
            ->orderBy('bulan', 'ASC')
            ->get()->getResultArray();

        $byForm = $db->table('submissions')
            ->select('form_types.nama_form, COUNT(submissions.id) AS total')
            ->join('form_types', 'form_types.id = submissions.form_type_id', 'left')
            ->groupBy('submissions.form_type_id')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        return view('admin/reports/index', [
            'pageTitle' => 'Laporan',
            'summary' => [
                'totalSubmissions' => $submissionModel->countAllResults(),
                'totalForms' => (new FormTypeModel())->countAllResults(),
            ],
            'monthly' => $monthly,
            'byForm' => $byForm,
        ]);
    }
}
