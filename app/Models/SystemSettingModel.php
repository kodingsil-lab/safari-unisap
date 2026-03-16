<?php

namespace App\Models;

class SystemSettingModel extends BaseModel
{
    protected $table = 'system_settings';
    protected $allowedFields = [
        'setting_key',
        'setting_value',
    ];

    public function getMappedSettings(bool $onlyPublic = false): array
    {
        $settings = $this->findAll();
        $mapped = [];

        foreach ($settings as $setting) {
            $mapped[$setting['setting_key']] = $setting;
        }

        return $mapped;
    }
}
