<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\ActivityLogger;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->session->get('admin_id')) {
            return redirect()->to(site_url('admin/dashboard'));
        }

        if ($this->request->getMethod(true) === 'POST') {
            if (! $this->validate('adminLogin')) {
                return redirect()->back()->withInput()->with('error', 'Silakan lengkapi form login.');
            }

            $identity = (string) $this->request->getPost('identity');
            $password = (string) $this->request->getPost('password');
            $admin = (new AdminModel())
                ->groupStart()
                ->where('username', $identity)
                ->orWhere('email', $identity)
                ->groupEnd()
                ->first();

            if (! $admin || ! password_verify($password, $admin['password_hash']) || (int) $admin['is_active'] !== 1) {
                return redirect()->back()->withInput()->with('error', 'Login gagal. Periksa kembali akun Anda.');
            }

            $this->session->set([
                'admin_id' => $admin['id'],
                'admin_name' => $admin['nama'],
                'admin_username' => $admin['username'],
            ]);

            (new AdminModel())->update($admin['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
            (new ActivityLogger())->log((int) $admin['id'], 'auth.login', 'Admin login ke sistem.');

            return redirect()->to(site_url('admin/dashboard'))->with('success', 'Selamat datang, ' . $admin['nama'] . '.');
        }

        return view('admin/auth/login', ['validation' => service('validation')]);
    }

    public function logout()
    {
        (new ActivityLogger())->log((int) $this->session->get('admin_id'), 'auth.logout', 'Admin logout dari sistem.');
        $this->session->destroy();

        return redirect()->to(site_url('admin/login'))->with('success', 'Anda telah logout.');
    }
}
