<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ActivityLogger;
use App\Models\AdminModel;

class AdminUserController extends BaseController
{
    public function index()
    {
        return view('admin/admin_users/index', [
            'pageTitle' => 'Pengguna Admin',
            'admins' => (new AdminModel())->orderBy('id', 'DESC')->findAll(),
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
        $model = new AdminModel();
        $admin = $model->find($id);

        if (! $admin) {
            return redirect()->to(site_url('admin/admin-users'))->with('error', 'Pengguna admin tidak ditemukan.');
        }

        $model->update($id, ['is_active' => (int) ! ((int) $admin['is_active'])]);

        (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'admin_user.toggle', 'Status admin user diperbarui.', [
            'referensi_tabel' => 'admins',
            'referensi_id' => $id,
        ]);

        return redirect()->to(site_url('admin/admin-users'))->with('success', 'Status pengguna admin berhasil diperbarui.');
    }

    private function save(?int $id = null)
    {
        $model = new AdminModel();
        $admin = $id ? $model->find($id) : null;

        if ($id && ! $admin) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getMethod(true) === 'POST') {
            $rules = [
                'nama' => [
                    'rules' => 'required|min_length[3]',
                    'errors' => [
                        'required' => 'Nama admin wajib diisi.',
                        'min_length' => 'Nama admin minimal 3 karakter.',
                    ],
                ],
                'email' => [
                    'rules' => 'required|valid_email|' . $this->uniqueRule('admins.email', $id),
                    'errors' => [
                        'required' => 'Email wajib diisi.',
                        'valid_email' => 'Format email tidak valid.',
                        'is_unique' => 'Email ini sudah dipakai admin lain.',
                    ],
                ],
                'username' => [
                    'rules' => 'required|min_length[3]|' . $this->uniqueRule('admins.username', $id),
                    'errors' => [
                        'required' => 'Username wajib diisi.',
                        'min_length' => 'Username minimal 3 karakter.',
                        'is_unique' => 'Username ini sudah digunakan.',
                    ],
                ],
                'role' => [
                    'rules' => 'required|in_list[superadmin,admin]',
                    'errors' => [
                        'required' => 'Role admin wajib dipilih.',
                    ],
                ],
            ];

            if (! $id) {
                $rules['password'] = [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password wajib diisi untuk admin baru.',
                        'min_length' => 'Password minimal 6 karakter.',
                    ],
                ];
            } else {
                $rules['password'] = [
                    'rules' => 'permit_empty|min_length[6]',
                    'errors' => [
                        'min_length' => 'Password minimal 6 karakter.',
                    ],
                ];
            }

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Silakan periksa data pengguna admin.');
            }

            $payload = [
                'nama' => (string) $this->request->getPost('nama'),
                'email' => (string) $this->request->getPost('email'),
                'username' => (string) $this->request->getPost('username'),
                'role' => (string) $this->request->getPost('role'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            ];

            $password = (string) $this->request->getPost('password');
            if ($password !== '') {
                $payload['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($id) {
                $model->update($id, $payload);
                (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'admin_user.update', 'Admin user diperbarui.', [
                    'referensi_tabel' => 'admins',
                    'referensi_id' => $id,
                ]);
            } else {
                $id = (int) $model->insert($payload, true);
                (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'admin_user.create', 'Admin user ditambahkan.', [
                    'referensi_tabel' => 'admins',
                    'referensi_id' => $id,
                ]);
            }

            return redirect()->to(site_url('admin/admin-users'))->with('success', 'Data pengguna admin berhasil disimpan.');
        }

        return view('admin/admin_users/form', [
            'pageTitle' => $id ? 'Ubah Pengguna Admin' : 'Tambah Pengguna Admin',
            'admin' => $admin,
            'validation' => service('validation'),
        ]);
    }

    private function uniqueRule(string $field, ?int $id = null): string
    {
        if ($id === null) {
            return 'is_unique[' . $field . ']';
        }

        return 'is_unique[' . $field . ',id,' . $id . ']';
    }
}
