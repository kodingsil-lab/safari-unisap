<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Libraries\FormNotificationService;
use App\Libraries\SubmissionCodeService;
use App\Models\FormCategoryModel;
use App\Models\FormFieldModel;
use App\Models\FormTypeModel;
use App\Models\SubmissionFileModel;
use App\Models\SubmissionModel;
use App\Models\SubmissionValueModel;

class FormController extends BaseController
{
    private FormTypeModel $formTypeModel;
    private FormFieldModel $formFieldModel;

    public function __construct()
    {
        $this->formTypeModel = new FormTypeModel();
        $this->formFieldModel = new FormFieldModel();
    }

    public function index()
    {
        $keyword = trim((string) $this->request->getGet('keyword'));
        $category = trim((string) $this->request->getGet('category'));
        $forms = $this->formTypeModel->getActiveWithCategory();
        $categories = (new FormCategoryModel())->getActiveCategories();

        if ($keyword !== '') {
            $forms = array_values(array_filter($forms, static function (array $form) use ($keyword): bool {
                $haystack = strtolower(($form['name'] ?? '') . ' ' . ($form['description'] ?? '') . ' ' . ($form['category_name'] ?? ''));
                return str_contains($haystack, strtolower($keyword));
            }));
        }

        if ($category !== '') {
            $forms = array_values(array_filter($forms, static fn (array $form): bool => ($form['category_slug'] ?? '') === $category));
        }

        return view('public/forms/index', [
            'forms' => $forms,
            'categories' => $categories,
            'filters' => [
                'keyword' => $keyword,
                'category' => $category,
            ],
        ]);
    }

    public function show(string $slug)
    {
        $form = $this->getFormOrFail($slug);

        return view('public/forms/show', [
            'form' => $form,
            'fields' => $this->formFieldModel->getByFormType((int) $form['id']),
        ]);
    }

    public function fill(string $slug)
    {
        $form = $this->getFormOrFail($slug);
        $fields = $this->normalizeFields($this->formFieldModel->getByFormType((int) $form['id']));

        return view('public/forms/fill', [
            'form' => $form,
            'fields' => $fields,
            'validation' => service('validation'),
        ]);
    }

    public function submit(string $slug)
    {
        $form = $this->getFormOrFail($slug);
        $fields = $this->formFieldModel->getByFormType((int) $form['id']);
        $fields = $this->normalizeFields($fields);
        [$rules, $labels] = $this->buildRules($fields);

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali data formulir.');
        }

        $db = db_connect();
        $db->transStart();

        $submissionCode = (new SubmissionCodeService())->generate();
        $submissionModel = new SubmissionModel();
        $submissionId = $submissionModel->insert([
            'form_type_id' => $form['id'],
            'kode_pengajuan' => $submissionCode,
            'nama_pengaju' => (string) ($this->request->getPost('applicant_name') ?: $this->request->getPost('nama_pengaju')),
            'nidn_nip' => (string) $this->request->getPost('nidn_nip'),
            'unit_kerja' => (string) $this->request->getPost('unit_kerja'),
            'email' => (string) ($this->request->getPost('applicant_email') ?: $this->request->getPost('email')),
            'no_hp' => (string) ($this->request->getPost('applicant_phone') ?: $this->request->getPost('no_hp')),
            'tanggal_pengajuan' => date('Y-m-d H:i:s'),
            'submitted_ip' => $this->request->getIPAddress(),
            'submitted_user_agent' => substr((string) $this->request->getUserAgent(), 0, 500),
        ], true);

        $valueModel = new SubmissionValueModel();
        $fileModel = new SubmissionFileModel();
        foreach ($fields as $field) {
            if ($field['field_type'] === 'file') {
                $upload = $this->request->getFile($field['name']);
                if ($upload && $upload->isValid() && ! $upload->hasMoved()) {
                    $relativeDir = 'uploads/' . $form['slug'] . '/' . $submissionCode;
                    $targetDir = WRITEPATH . $relativeDir;
                    if (! is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $storedName = $upload->getRandomName();
                    $upload->move($targetDir, $storedName);

                    $fileModel->insert([
                        'submission_id' => $submissionId,
                        'form_field_id' => $field['id'],
                        'nama_file_asli' => $upload->getClientName(),
                        'nama_file_simpan' => $storedName,
                        'path_file' => $relativeDir . '/' . $storedName,
                        'mime_type' => $upload->getClientMimeType(),
                        'ukuran_file' => $upload->getSize(),
                    ]);
                }

                continue;
            }

            $value = $this->request->getPost($field['name']);
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            $valueModel->insert([
                'submission_id' => $submissionId,
                'form_field_id' => $field['id'],
                'nama_field' => $field['name'],
                ...$this->mapValuePayload($field['field_type'], $value),
            ]);
        }

        $db->transComplete();

        if ($db->transStatus()) {
            (new FormNotificationService())->sendSubmissionNotifications((int) $submissionId);
        }

        return redirect()->to(site_url('pengajuan/sukses/' . $submissionCode));
    }

    private function getFormOrFail(string $slug): array
    {
        $form = $this->formTypeModel->getActiveWithCategory($slug);

        if ($form === []) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $form;
    }

    private function buildRules(array $fields): array
    {
        $rules = [];
        $labels = [];

        foreach ($fields as $field) {
            $labels[$field['name']] = $field['label'];

            if ($field['field_type'] === 'file') {
                $upload = $this->request->getFile($field['name']);
                $fileRules = [];
                if ((int) $field['is_required'] === 1) {
                    $fileRules[] = 'uploaded[' . $field['name'] . ']';
                }
                if ($upload && $upload->getError() !== UPLOAD_ERR_NO_FILE) {
                    $fileRules[] = 'max_size[' . $field['name'] . ',' . ((int) $field['file_max_size'] ?: 2048) . ']';

                    $extensions = array_filter(array_map(static fn ($item) => ltrim(trim($item), '.'), explode(',', (string) $field['accept'])));
                    if ($extensions !== []) {
                        $fileRules[] = 'ext_in[' . $field['name'] . ',' . implode(',', $extensions) . ']';
                    }
                }

                $rules[$field['name']] = [
                    'label' => $field['label'],
                    'rules' => $fileRules === [] ? 'permit_empty' : implode('|', $fileRules),
                ];
                continue;
            }

            $fieldRules = $this->buildFieldRuleString($field);

            $rules[$field['name']] = [
                'label' => $field['label'],
                'rules' => $fieldRules,
            ];
        }

        return [$rules, $labels];
    }

    private function normalizeFields(array $fields): array
    {
        foreach ($fields as &$field) {
            $config = field_config($field['options'] ?? null);
            $field['config'] = $config;
            $field['accept'] = isset($config['extensions']) && is_array($config['extensions']) && $config['extensions'] !== []
                ? '.' . implode(',.', $config['extensions'])
                : '.pdf,.jpg,.jpeg,.png,.doc,.docx';
            $field['file_max_size'] = (int) ($config['max_size_kb'] ?? 2048);
            $field['min_length'] = $config['min_length'] ?? null;
            $field['max_length'] = $config['max_length'] ?? null;
            $field['min_number'] = $config['min'] ?? null;
            $field['max_number'] = $config['max'] ?? null;
            $field['step_number'] = $config['step'] ?? null;
            $field['min_date'] = $config['min_date'] ?? null;
            $field['max_date'] = $config['max_date'] ?? null;
            $field['column_width'] = $field['column_width'] ?? 12;
        }

        return $fields;
    }

    private function buildFieldRuleString(array $field): string
    {
        $ruleParts = [];

        if ((int) $field['is_required'] === 1) {
            $ruleParts[] = 'required';
        }

        if ($field['field_type'] === 'email') {
            $ruleParts[] = 'valid_email';
        }

        if (in_array($field['field_type'], ['text', 'textarea'], true)) {
            if (! empty($field['min_length'])) {
                $ruleParts[] = 'min_length[' . (int) $field['min_length'] . ']';
            }
            if (! empty($field['max_length'])) {
                $ruleParts[] = 'max_length[' . (int) $field['max_length'] . ']';
            }
        }

        if ($field['field_type'] === 'number') {
            $ruleParts[] = 'decimal';
            if ($field['min_number'] !== null && $field['min_number'] !== '') {
                $ruleParts[] = 'greater_than_equal_to[' . $field['min_number'] . ']';
            }
            if ($field['max_number'] !== null && $field['max_number'] !== '') {
                $ruleParts[] = 'less_than_equal_to[' . $field['max_number'] . ']';
            }
        }

        if ($field['field_type'] === 'date') {
            $ruleParts[] = 'valid_date[Y-m-d]';
        }

        if (in_array($field['field_type'], ['select', 'radio'], true)) {
            $choices = field_options($field['options'] ?? null);
            if ($choices !== []) {
                $ruleParts[] = 'in_list[' . implode(',', array_map(static fn ($item) => str_replace(',', '\,', (string) $item), $choices)) . ']';
            }
        }

        $customRules = trim((string) $field['validation_rules']);
        if ($customRules !== '') {
            $ruleParts[] = $customRules;
        }

        if ($ruleParts === []) {
            return 'permit_empty';
        }

        return implode('|', array_unique($ruleParts));
    }

    private function mapValuePayload(string $fieldType, mixed $value): array
    {
        return match ($fieldType) {
            'textarea' => ['nilai_longtext' => (string) $value],
            'date' => ['nilai_date' => $value ?: null],
            'number' => ['nilai_number' => ($value === '' || $value === null) ? null : (float) $value],
            'checkbox', 'select', 'radio' => ['nilai_json' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value !== null ? json_encode([$value], JSON_UNESCAPED_UNICODE) : null), 'nilai_text' => is_array($value) ? implode(', ', $value) : (string) $value],
            default => ['nilai_text' => (string) $value],
        };
    }
}
