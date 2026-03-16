<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ActivityLogger;
use App\Models\SystemSettingModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class SettingController extends BaseController
{
    public function index()
    {
        $model = new SystemSettingModel();
        $settings = $model->getMappedSettings();

        if ($this->request->getMethod(true) === 'POST') {
            $rules = [
                'site_name' => ['rules' => 'required|min_length[3]', 'errors' => ['required' => 'Nama sistem wajib diisi.']],
                'campus_name' => ['rules' => 'required|min_length[3]', 'errors' => ['required' => 'Nama kampus wajib diisi.']],
                'support_email' => ['rules' => 'required|valid_email', 'errors' => ['required' => 'Email kontak wajib diisi.', 'valid_email' => 'Format email kontak tidak valid.']],
                'support_phone' => ['rules' => 'required', 'errors' => ['required' => 'Nomor kontak wajib diisi.']],
                'office_hours' => ['rules' => 'required', 'errors' => ['required' => 'Jam layanan wajib diisi.']],
                'max_upload_size' => ['rules' => 'permit_empty|integer', 'errors' => ['integer' => 'Batas upload harus berupa angka.']],
                'allowed_file_types' => 'permit_empty',
                'logo_file' => ['rules' => 'permit_empty|max_size[logo_file,2048]|ext_in[logo_file,png,jpg,jpeg,svg,webp]', 'errors' => ['max_size' => 'Ukuran logo maksimal 2MB.', 'ext_in' => 'Logo harus berformat PNG, JPG, JPEG, SVG, atau WEBP.']],
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Silakan periksa pengaturan sistem.');
            }

            $payload = [
                'site_name' => (string) $this->request->getPost('site_name'),
                'site_tagline' => (string) $this->request->getPost('site_tagline'),
                'campus_name' => (string) $this->request->getPost('campus_name'),
                'support_email' => (string) $this->request->getPost('support_email'),
                'support_phone' => (string) $this->request->getPost('support_phone'),
                'office_hours' => (string) $this->request->getPost('office_hours'),
                'max_upload_size' => (string) $this->request->getPost('max_upload_size'),
                'allowed_file_types' => (string) $this->request->getPost('allowed_file_types'),
                'logo_path' => $settings['logo_path']['setting_value'] ?? 'assets/img/logo-unisap.svg',
            ];
            $oldLogoToDelete = null;
            $newLogoPath = null;

            $logoFile = $this->request->getFile('logo_file');
            if ($logoFile instanceof UploadedFile && $logoFile->isValid() && ! $logoFile->hasMoved()) {
                $relativeDir = 'uploads/settings';
                $targetDir = FCPATH . $relativeDir;
                if (! is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $oldLogo = $payload['logo_path'];
                $storedName = $logoFile->getRandomName();
                $logoFile->move($targetDir, $storedName);
                $newLogoPath = $relativeDir . '/' . $storedName;
                $payload['logo_path'] = $newLogoPath;

                if ($oldLogo && str_starts_with($oldLogo, 'uploads/settings/') && is_file(FCPATH . $oldLogo)) {
                    $oldLogoToDelete = FCPATH . $oldLogo;
                }
            }

            $db = db_connect();
            $db->transStart();

            try {
                foreach ($payload as $key => $value) {
                    $existing = $model->where('setting_key', $key)->first();
                    if ($existing) {
                        $model->update($existing['id'], ['setting_value' => $value]);
                    } else {
                        $model->insert(['setting_key' => $key, 'setting_value' => $value]);
                    }
                }

                $db->transComplete();

                if (! $db->transStatus()) {
                    throw new \RuntimeException('Gagal menyimpan pengaturan sistem.');
                }
            } catch (\Throwable $e) {
                if ($newLogoPath && is_file(FCPATH . $newLogoPath)) {
                    @unlink(FCPATH . $newLogoPath);
                }

                return redirect()->back()->withInput()->with('error', 'Pengaturan gagal disimpan. Silakan coba lagi.');
            }

            if ($oldLogoToDelete && is_file($oldLogoToDelete)) {
                @unlink($oldLogoToDelete);
            }

            (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'settings.update', 'Pengaturan sistem diperbarui.', [
                'referensi_tabel' => 'system_settings',
            ]);

            return redirect()->to(site_url('admin/pengaturan'))->with('success', 'Pengaturan sistem berhasil disimpan.');
        }

        return view('admin/settings/index', [
            'pageTitle' => 'Pengaturan',
            'settings' => $settings,
            'validation' => service('validation'),
        ]);
    }
}
