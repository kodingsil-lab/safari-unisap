<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ActivityLogger;
use App\Models\FormFieldModel;
use App\Models\FormTypeModel;

class FormFieldController extends BaseController
{
    public function index(int $formTypeId)
    {
        $formType = (new FormTypeModel())->find($formTypeId);
        if (! $formType) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/form_fields/index', [
            'pageTitle' => 'Susunan Isian Formulir',
            'formType' => $formType,
            'fields' => (new FormFieldModel())->where('form_type_id', $formTypeId)->orderBy('urutan', 'ASC')->findAll(),
            'fieldTypes' => form_field_type_options(),
        ]);
    }

    public function create(int $formTypeId)
    {
        return $this->save($formTypeId);
    }

    public function edit(int $formTypeId, int $id)
    {
        return $this->save($formTypeId, $id);
    }

    public function delete(int $formTypeId, int $id)
    {
        $field = (new FormFieldModel())->where('form_type_id', $formTypeId)->find($id);
        if (! $field) {
            return redirect()->to(site_url('admin/forms/' . $formTypeId . '/fields'))->with('error', 'Isian formulir tidak ditemukan.');
        }

        (new FormFieldModel())->delete($id);
        (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_field.delete', 'Isian formulir dihapus.', [
            'referensi_tabel' => 'form_fields',
            'referensi_id' => $id,
        ]);

        return redirect()->to(site_url('admin/forms/' . $formTypeId . '/fields'))->with('success', 'Isian formulir berhasil dihapus.');
    }

    public function reorder(int $formTypeId)
    {
        $orders = $this->request->getPost('orders') ?? [];
        $model = new FormFieldModel();

        foreach ($orders as $fieldId => $urutan) {
            $model->update((int) $fieldId, ['urutan' => (int) $urutan]);
        }

        (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_field.reorder', 'Urutan field formulir diperbarui.', [
            'referensi_tabel' => 'form_types',
            'referensi_id' => $formTypeId,
        ]);

        return redirect()->to(site_url('admin/forms/' . $formTypeId . '/fields'))->with('success', 'Urutan isian berhasil diperbarui.');
    }

    private function save(int $formTypeId, ?int $id = null)
    {
        $formType = (new FormTypeModel())->find($formTypeId);
        if (! $formType) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $model = new FormFieldModel();
        $field = $id ? $model->where('form_type_id', $formTypeId)->find($id) : null;

        if ($id && ! $field) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getMethod(true) === 'POST') {
            $rules = [
                'nama_field' => 'required|alpha_dash',
                'label_field' => 'required|min_length[3]',
                'tipe_field' => 'required|in_list[' . implode(',', array_keys(form_field_type_options())) . ']',
                'urutan' => 'required|integer',
                'validasi' => 'permit_empty',
                'placeholder' => 'permit_empty',
                'help_text' => 'permit_empty',
                'choice_options' => 'permit_empty',
                'min_length' => 'permit_empty|integer',
                'max_length' => 'permit_empty|integer',
                'min_number' => 'permit_empty|decimal',
                'max_number' => 'permit_empty|decimal',
                'step_number' => 'permit_empty|decimal',
                'min_date' => 'permit_empty|valid_date[Y-m-d]',
                'max_date' => 'permit_empty|valid_date[Y-m-d]',
                'allowed_extensions' => 'permit_empty',
                'max_size_kb' => 'permit_empty|integer',
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali data isian formulir.');
            }

            $payload = [
                'form_type_id' => $formTypeId,
                'nama_field' => trim((string) $this->request->getPost('nama_field')),
                'label_field' => trim((string) $this->request->getPost('label_field')),
                'tipe_field' => (string) $this->request->getPost('tipe_field'),
                'placeholder' => trim((string) $this->request->getPost('placeholder')),
                'opsi_json' => $this->buildFieldSettings(),
                'is_required' => $this->request->getPost('is_required') ? 1 : 0,
                'urutan' => (int) $this->request->getPost('urutan'),
                'validasi' => trim((string) $this->request->getPost('validasi')),
                'help_text' => trim((string) $this->request->getPost('help_text')),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            ];

            if ($id) {
                $model->update($id, $payload);
                (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_field.update', 'Isian formulir diperbarui.', [
                    'referensi_tabel' => 'form_fields',
                    'referensi_id' => $id,
                ]);
            } else {
                $id = (int) $model->insert($payload, true);
                (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'form_field.create', 'Isian formulir ditambahkan.', [
                    'referensi_tabel' => 'form_fields',
                    'referensi_id' => $id,
                ]);
            }

            return redirect()->to(site_url('admin/forms/' . $formTypeId . '/fields'))->with('success', 'Isian formulir berhasil disimpan.');
        }

        return view('admin/form_fields/form', [
            'pageTitle' => $id ? 'Ubah Isian Formulir' : 'Tambah Isian Formulir',
            'formType' => $formType,
            'field' => $field,
            'fieldConfig' => field_config($field['opsi_json'] ?? null),
            'fieldTypes' => form_field_type_options(),
            'validation' => service('validation'),
        ]);
    }

    private function buildFieldSettings(): ?string
    {
        $fieldType = (string) $this->request->getPost('tipe_field');
        $settings = [];

        if (in_array($fieldType, ['select', 'radio', 'checkbox'], true)) {
            $choices = $this->normalizeChoices((string) $this->request->getPost('choice_options'));
            if ($choices !== []) {
                $settings['choices'] = $choices;
            }
        }

        if (in_array($fieldType, ['text', 'textarea'], true)) {
            $minLength = trim((string) $this->request->getPost('min_length'));
            $maxLength = trim((string) $this->request->getPost('max_length'));

            if ($minLength !== '') {
                $settings['min_length'] = (int) $minLength;
            }
            if ($maxLength !== '') {
                $settings['max_length'] = (int) $maxLength;
            }
        }

        if ($fieldType === 'number') {
            foreach (['min_number' => 'min', 'max_number' => 'max', 'step_number' => 'step'] as $input => $key) {
                $value = trim((string) $this->request->getPost($input));
                if ($value !== '') {
                    $settings[$key] = (float) $value;
                }
            }
        }

        if ($fieldType === 'date') {
            foreach (['min_date' => 'min_date', 'max_date' => 'max_date'] as $input => $key) {
                $value = trim((string) $this->request->getPost($input));
                if ($value !== '') {
                    $settings[$key] = $value;
                }
            }
        }

        if ($fieldType === 'file') {
            $extensions = $this->normalizeExtensionList((string) $this->request->getPost('allowed_extensions'));
            $maxSizeKb = trim((string) $this->request->getPost('max_size_kb'));

            if ($extensions !== []) {
                $settings['extensions'] = $extensions;
            }

            if ($maxSizeKb !== '') {
                $settings['max_size_kb'] = (int) $maxSizeKb;
            }
        }

        return $settings === [] ? null : json_encode($settings, JSON_UNESCAPED_UNICODE);
    }

    private function normalizeChoices(string $raw): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', trim($raw)))));
    }

    private function normalizeExtensionList(string $raw): array
    {
        $items = array_map(static fn ($item) => ltrim(trim($item), '.'), explode(',', strtolower($raw)));

        return array_values(array_filter($items));
    }
}
