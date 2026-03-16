<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('admins')->insert([
            'nama' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@unisap.ac.id',
            'password_hash' => password_hash('admin12345', PASSWORD_DEFAULT),
            'role' => 'superadmin',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
