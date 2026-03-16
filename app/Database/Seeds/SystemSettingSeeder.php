<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $settings = [
            ['setting_key' => 'site_name', 'setting_value' => 'SAFARI UNISAP'],
            ['setting_key' => 'site_tagline', 'setting_value' => 'Sistem Administrasi Formulir Universitas San Pedro'],
            ['setting_key' => 'campus_name', 'setting_value' => 'Universitas San Pedro'],
            ['setting_key' => 'support_email', 'setting_value' => 'layanan@unisap.ac.id'],
            ['setting_key' => 'support_phone', 'setting_value' => '+62 821-0000-0000'],
            ['setting_key' => 'office_hours', 'setting_value' => 'Senin - Jumat, 08.00 - 16.00 WITA'],
        ];

        foreach ($settings as &$setting) {
            $setting['created_at'] = $now;
            $setting['updated_at'] = $now;
        }

        $this->db->table('system_settings')->insertBatch($settings);
    }
}
