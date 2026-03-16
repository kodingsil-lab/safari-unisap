<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ActivityLogger;
use App\Models\FormCategoryModel;
use App\Models\FormTypeModel;
use App\Models\SubmissionFileModel;
use App\Models\SubmissionModel;
use App\Models\SubmissionValueModel;

class SubmissionController extends BaseController
{
    public function index()
    {
        $submissionModel = new SubmissionModel();
        $filters = [
            'category_id' => $this->request->getGet('category_id'),
            'form_type_id' => $this->request->getGet('form_type_id'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'keyword' => trim((string) $this->request->getGet('keyword')),
        ];
        $builder = $submissionModel->applyAdminFilters($filters);

        return view('admin/submissions/index', [
            'submissions' => $builder->paginate(10, 'default'),
            'pager' => $submissionModel->pager,
            'filters' => $filters,
            'categories' => (new FormCategoryModel())->orderBy('urutan', 'ASC')->findAll(),
            'forms' => (new FormTypeModel())->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $submission = (new SubmissionModel())->withRelations()->find($id);
        if (! $submission) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        (new ActivityLogger())->log(
            (int) $this->session->get('admin_id'),
            'submission.view',
            'Membuka detail pengajuan.',
            ['referensi_tabel' => 'submissions', 'referensi_id' => $id]
        );

        return view('admin/submissions/show', [
            'submission' => $submission,
            'values' => (new SubmissionValueModel())
                ->select('submission_values.*, form_fields.label_field')
                ->join('form_fields', 'form_fields.id = submission_values.form_field_id', 'left')
                ->where('submission_id', $id)
                ->findAll(),
            'files' => (new SubmissionFileModel())
                ->select('submission_files.*, form_fields.label_field')
                ->join('form_fields', 'form_fields.id = submission_files.form_field_id', 'left')
                ->where('submission_id', $id)
                ->findAll(),
        ]);
    }

    public function downloadFile(int $id)
    {
        $file = (new SubmissionFileModel())->find($id);

        if (! $file) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $path = WRITEPATH . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, (string) $file['path_file']), DIRECTORY_SEPARATOR);

        if (! is_file($path)) {
            return redirect()->back()->with('error', 'File lampiran tidak ditemukan.');
        }

        return $this->response->download($path, null)->setFileName((string) $file['nama_file_asli']);
    }

    public function delete(int $id)
    {
        $submission = (new SubmissionModel())->find($id);

        if (! $submission) {
            return redirect()->to(site_url('admin/pengajuan'))->with('error', 'Pengajuan tidak ditemukan.');
        }

        $this->deleteSubmissionWithFiles([$id]);

        (new ActivityLogger())->log(
            (int) $this->session->get('admin_id'),
            'submission.delete',
            'Pengajuan dihapus.',
            ['referensi_tabel' => 'submissions', 'referensi_id' => $id]
        );

        return redirect()->to(site_url('admin/pengajuan'))->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = array_values(array_filter(array_map('intval', (array) $this->request->getPost('submission_ids'))));

        if ($ids === []) {
            return redirect()->to(site_url('admin/pengajuan'))->with('error', 'Pilih data pengajuan yang ingin dihapus.');
        }

        $existingIds = array_map(
            static fn (array $row): int => (int) $row['id'],
            (new SubmissionModel())->select('id')->whereIn('id', $ids)->findAll()
        );

        if ($existingIds === []) {
            return redirect()->to(site_url('admin/pengajuan'))->with('error', 'Data pengajuan yang dipilih tidak ditemukan.');
        }

        $this->deleteSubmissionWithFiles($existingIds);

        (new ActivityLogger())->log(
            (int) $this->session->get('admin_id'),
            'submission.bulk_delete',
            'Beberapa pengajuan dihapus.',
            ['referensi_tabel' => 'submissions', 'referensi_id' => count($existingIds)]
        );

        return redirect()->to(site_url('admin/pengajuan'))->with('success', count($existingIds) . ' pengajuan berhasil dihapus.');
    }

    private function deleteSubmissionWithFiles(array $ids): void
    {
        $fileModel = new SubmissionFileModel();
        $submissionModel = new SubmissionModel();

        $files = $fileModel->whereIn('submission_id', $ids)->findAll();

        foreach ($files as $file) {
            $path = WRITEPATH . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, (string) $file['path_file']), DIRECTORY_SEPARATOR);

            if (is_file($path)) {
                @unlink($path);
            }
        }

        $submissionModel->whereIn('id', $ids)->delete();
    }
}
