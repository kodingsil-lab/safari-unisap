<?php

namespace App\Libraries;

use App\Models\ActivityLogModel;

class ActivityLogger
{
    public function log(?int $adminId, string $type, string $description, array $payload = []): void
    {
        $model = new ActivityLogModel();
        $request = service('request');

        $model->insert([
            'admin_id' => $adminId,
            'aktivitas' => $type . ': ' . $description,
            'referensi_tabel' => $payload['referensi_tabel'] ?? null,
            'referensi_id' => $payload['referensi_id'] ?? null,
            'ip_address' => $request->getIPAddress(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
