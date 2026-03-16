<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ActivityLogger;
use App\Models\FormCategoryModel;
use App\Models\FormFieldModel;
use App\Models\FormTypeModel;

class FormTypeController extends BaseController
{
    public function index()
    {
        $formTypeModel = new FormTypeModel();

        return view('admin/form_types/index', [
            'formTypes' => $formTypeModel->select('form_types.*, form_types.nama_form AS name, form_types.kode_form AS code, form_types.urutan AS sort_order, form_categories.nama_kategori AS category_name')
                ->join('form_categories', 'form_categories.id = form_types.category_id', 'left')
                ->orderBy('form_types.urutan', 'ASC')
                ->findAll(),
            'fieldCounts' => $this->getFieldCounts(),
        ]);
    }

    public function create()
    {
        return $this->save();
    }

    public function edit(int $id)
    {
        return $this->save($id);
    }

    public function toggle(int $id)
    {
        $model = new FormTypeModel();
        $form = $model->find($id);

        if (! $form) {
            return redirect()->to(site_url('admin/forms'))->with('error', 'Jenis formulir tidak ditemukan.');
        }

        $newStatus = (int) ! ((int) $form['is_active']);
        $model->update($id, ['is_active' => $newStatus]);

        (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_type.toggle', 'Status aktif formulir diubah.', [
            'referensi_tabel' => 'form_types',
            'referensi_id' => $id,
        ]);

        return redirect()->to(site_url('admin/forms'))->with('success', 'Status formulir berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $form = (new FormTypeModel())->find($id);
        if (! $form) {
            return redirect()->to(site_url('admin/forms'))->with('error', 'Jenis formulir tidak ditemukan.');
        }

        (new FormTypeModel())->delete($id);
        (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_type.delete', 'Jenis formulir dihapus.', ['id' => $id]);

        return redirect()->to(site_url('admin/forms'))->with('success', 'Jenis formulir berhasil dihapus.');
    }

    private function save(?int $id = null)
    {
        $formTypeModel = new FormTypeModel();
        $categoryModel = new FormCategoryModel();
        $form = $id ? $formTypeModel->find($id) : null;

        if ($id && ! $form) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getMethod(true) === 'POST') {
            $rules = [
                'category_id' => 'required|integer',
                'name' => 'required|min_length[3]',
                'slug' => 'required|alpha_dash',
                'code' => 'required|alpha_numeric_punct',
                'icon' => 'permit_empty',
                'template_pdf' => 'permit_empty',
                'sort_order' => 'required|integer',
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali data formulir.');
            }

            $payload = [
                'category_id' => (int) $this->request->getPost('category_id'),
                'nama_form' => (string) $this->request->getPost('name'),
                'slug' => (string) $this->request->getPost('slug'),
                'kode_form' => strtoupper((string) $this->request->getPost('code')),
                'deskripsi' => (string) $this->request->getPost('description'),
                'icon' => (string) ($this->request->getPost('icon') ?: 'bi-file-earmark-text'),
                'template_pdf' => (string) $this->request->getPost('template_pdf'),
                'urutan' => (int) $this->request->getPost('sort_order'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            ];

            if ($id) {
                $formTypeModel->update($id, $payload);
                (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_type.update', 'Jenis formulir diperbarui.', ['id' => $id]);
            } else {
                $id = (int) $formTypeModel->insert($payload, true);
                (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_type.create', 'Jenis formulir ditambahkan.', ['id' => $id]);
            }

            return redirect()->to(site_url('admin/forms'))->with('success', 'Data jenis formulir berhasil disimpan.');
        }

        return view('admin/form_types/form', [
            'formType' => $form,
            'categories' => $categoryModel->where('is_active', 1)->orderBy('urutan', 'ASC')->findAll(),
            'validation' => service('validation'),
        ]);
    }

    private function getFieldCounts(): array
    {
        $rows = (new FormFieldModel())->select('form_type_id, COUNT(*) AS total')->groupBy('form_type_id')->findAll();
        $counts = [];
        foreach ($rows as $row) {
            $counts[$row['form_type_id']] = (int) $row['total'];
        }

        return $counts;
    }
}
