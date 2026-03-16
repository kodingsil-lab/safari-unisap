<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FormTypeModel;
use App\Models\SubmissionModel;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('admin/dashboard/index', [
            'pageTitle' => 'Beranda',
            'summary' => [
                'totalSubmissions' => (new SubmissionModel())->countAllResults(),
                'submittedToday' => (new SubmissionModel())->where('DATE(tanggal_pengajuan)', date('Y-m-d'))->countAllResults(),
                'totalForms' => (new FormTypeModel())->countAllResults(),
            ],
            'recentSubmissions' => (new SubmissionModel())->withRelations()->orderBy('tanggal_pengajuan', 'DESC')->findAll(8),
        ]);
    }
}
