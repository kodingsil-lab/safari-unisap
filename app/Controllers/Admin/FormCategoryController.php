<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ActivityLogger;
use App\Models\FormCategoryModel;
use App\Models\FormTypeModel;

class FormCategoryController extends BaseController
{
    public function index()
    {
        return view('admin/form_categories/index', [
            'pageTitle' => 'Kategori Formulir',
            'categories' => (new FormCategoryModel())->orderBy('urutan', 'ASC')->findAll(),
            'formCounts' => $this->getFormCounts(),
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
        $model = new FormCategoryModel();
        $category = $model->find($id);

        if (! $category) {
            return redirect()->to(site_url('admin/form-categories'))->with('error', 'Kategori formulir tidak ditemukan.');
        }

        $newStatus = (int) ! ((int) $category['is_active']);
        $model->update($id, ['is_active' => $newStatus]);

        (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_category.toggle', 'Status kategori formulir diperbarui.', [
            'referensi_tabel' => 'form_categories',
            'referensi_id' => $id,
        ]);

        return redirect()->to(site_url('admin/form-categories'))->with('success', 'Status kategori formulir berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new FormCategoryModel();
        $category = $model->find($id);

        if (! $category) {
            return redirect()->to(site_url('admin/form-categories'))->with('error', 'Kategori formulir tidak ditemukan.');
        }

        $usedCount = (new FormTypeModel())->where('category_id', $id)->countAllResults();
        if ($usedCount > 0) {
            return redirect()->to(site_url('admin/form-categories'))->with('error', 'Kategori ini masih dipakai oleh jenis formulir, jadi belum bisa dihapus.');
        }

        $model->delete($id);

        (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_category.delete', 'Kategori formulir dihapus.', [
            'referensi_tabel' => 'form_categories',
            'referensi_id' => $id,
        ]);

        return redirect()->to(site_url('admin/form-categories'))->with('success', 'Kategori formulir berhasil dihapus.');
    }

    private function save(?int $id = null)
    {
        $model = new FormCategoryModel();
        $category = $id ? $model->find($id) : null;

        if ($id && ! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getMethod(true) === 'POST') {
            $rules = [
                'nama_kategori' => 'required|min_length[3]',
                'slug' => 'required|alpha_dash',
                'urutan' => 'required|integer',
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali data kategori formulir.');
            }

            $payload = [
                'nama_kategori' => trim((string) $this->request->getPost('nama_kategori')),
                'slug' => trim((string) $this->request->getPost('slug')),
                'urutan' => (int) $this->request->getPost('urutan'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            ];

            if ($id) {
                $model->update($id, $payload);
                (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_category.update', 'Kategori formulir diperbarui.', [
                    'referensi_tabel' => 'form_categories',
                    'referensi_id' => $id,
                ]);
            } else {
                $id = (int) $model->insert($payload, true);
                (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_category.create', 'Kategori formulir ditambahkan.', [
                    'referensi_tabel' => 'form_categories',
                    'referensi_id' => $id,
                ]);
            }

            return redirect()->to(site_url('admin/form-categories'))->with('success', 'Data kategori formulir berhasil disimpan.');
        }

        return view('admin/form_categories/form', [
            'pageTitle' => $id ? 'Ubah Kategori Formulir' : 'Tambah Kategori Formulir',
            'category' => $category,
            'validation' => service('validation'),
        ]);
    }

    private function getFormCounts(): array
    {
        $rows = (new FormTypeModel())->select('category_id, COUNT(*) AS total')->groupBy('category_id')->findAll();
        $counts = [];

        foreach ($rows as $row) {
            $counts[(int) $row['category_id']] = (int) $row['total'];
        }

        return $counts;
    }
}
