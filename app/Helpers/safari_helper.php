<?php

use App\Models\SystemSettingModel;

if (! function_exists('submission_status_options')) {
    function submission_status_options(): array
    {
        return [
            'masuk' => 'Masuk',
            'ditinjau' => 'Ditinjau',
            'ditindaklanjuti' => 'Ditindaklanjuti',
            'arsip' => 'Arsip',
        ];
    }
}

if (! function_exists('submission_status_label')) {
    function submission_status_label(?string $status): string
    {
        return submission_status_options()[$status ?? 'masuk'] ?? ucfirst(str_replace('_', ' ', (string) $status));
    }
}

if (! function_exists('submission_status_badge')) {
    function submission_status_badge(?string $status): string
    {
        return match ($status) {
            'masuk'           => 'secondary',
            'ditinjau'        => 'warning',
            'ditindaklanjuti' => 'primary',
            'arsip'           => 'success',
            default           => 'secondary',
        };
    }
}

if (! function_exists('field_options')) {
    function field_options(?string $options): array
    {
        return field_config($options)['choices'] ?? [];
    }
}

if (! function_exists('field_config')) {
    function field_config(?string $options): array
    {
        if (empty($options)) {
            return [];
        }

        $decoded = json_decode($options, true);

        if (! is_array($decoded)) {
            return [];
        }

        // Kompatibel dengan data lama yang hanya menyimpan array pilihan.
        if (array_is_list($decoded)) {
            return ['choices' => $decoded];
        }

        $decoded['choices'] = isset($decoded['choices']) && is_array($decoded['choices']) ? $decoded['choices'] : [];

        return $decoded;
    }
}

if (! function_exists('setting_value')) {
    function setting_value(array $settings, string $key, ?string $default = null): ?string
    {
        return $settings[$key]['setting_value'] ?? $default;
    }
}

if (! function_exists('system_setting')) {
    function system_setting(string $key, ?string $default = null): ?string
    {
        static $settings = null;

        if ($settings === null) {
            $settings = (new SystemSettingModel())->getMappedSettings();
        }

        return $settings[$key]['setting_value'] ?? $default;
    }
}

if (! function_exists('app_logo_url')) {
    function app_logo_url(): string
    {
        $logoPath = system_setting('logo_path', 'assets/img/logo-unisap.svg');

        return base_url($logoPath ?: 'assets/img/logo-unisap.svg');
    }
}

if (! function_exists('pdf_logo_src')) {
    function pdf_logo_src(): string
    {
        $logoPath = system_setting('logo_path', 'assets/img/logo-unisap.svg') ?: 'assets/img/logo-unisap.svg';
        $absolutePath = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $logoPath), DIRECTORY_SEPARATOR);

        if (is_file($absolutePath)) {
            return $absolutePath;
        }

        return rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo-unisap.svg';
    }
}

if (! function_exists('admin_nav_active')) {
    function admin_nav_active(array|string $patterns): string
    {
        $uri = trim(uri_string(), '/');
        $patterns = (array) $patterns;

        foreach ($patterns as $pattern) {
            $pattern = trim($pattern, '/');
            if ($uri === $pattern || str_starts_with($uri, $pattern . '/')) {
                return 'active';
            }
        }

        return '';
    }
}

if (! function_exists('public_nav_active')) {
    function public_nav_active(array|string $patterns): string
    {
        $uri = trim(uri_string(), '/');
        $patterns = (array) $patterns;

        foreach ($patterns as $pattern) {
            $pattern = trim($pattern, '/');

            if ($pattern === '' && $uri === '') {
                return 'active';
            }

            if ($pattern !== '' && ($uri === $pattern || str_starts_with($uri, $pattern . '/'))) {
                return 'active';
            }
        }

        return '';
    }
}

if (! function_exists('form_field_type_options')) {
    function form_field_type_options(): array
    {
        return [
            'text' => 'Teks Singkat',
            'textarea' => 'Teks Panjang',
            'email' => 'Email',
            'number' => 'Angka',
            'date' => 'Tanggal',
            'select' => 'Pilihan Dropdown',
            'radio' => 'Pilihan Tunggal',
            'checkbox' => 'Pilihan Jamak',
            'file' => 'Unggah Berkas',
        ];
    }
}

if (! function_exists('submission_value_text')) {
    function submission_value_text(array $value): string
    {
        return (string) (
            $value['nilai_longtext']
            ?? $value['nilai_text']
            ?? $value['nilai_date']
            ?? $value['nilai_number']
            ?? $value['nilai_json']
            ?? '-'
        );
    }
}

if (! function_exists('submission_identity')) {
    function submission_identity(array $submission, array $values = []): array
    {
        $identity = [
            'applicant_name' => (string) ($submission['applicant_name'] ?? $submission['nama_pengaju'] ?? ''),
            'applicant_email' => (string) ($submission['applicant_email'] ?? $submission['email'] ?? ''),
            'nidn_nip' => (string) ($submission['nidn_nip'] ?? ''),
            'unit_kerja' => (string) ($submission['unit_kerja'] ?? ''),
            'applicant_phone' => (string) ($submission['applicant_phone'] ?? $submission['no_hp'] ?? ''),
        ];

        if ($values === []) {
            return $identity;
        }

        $indexed = [];
        foreach ($values as $value) {
            $key = strtolower(trim((string) (($value['label_field'] ?? $value['nama_field'] ?? ''))));
            if ($key === '') {
                continue;
            }

            $indexed[$key] = submission_value_text($value);
        }

        if ($identity['applicant_name'] === '') {
            $identity['applicant_name'] = submission_identity_match($indexed, ['nama lengkap', 'nama pengirim', 'nama pemohon', 'nama dosen', 'nama mahasiswa', 'nama']);
        }

        if ($identity['applicant_email'] === '') {
            $identity['applicant_email'] = submission_identity_match($indexed, ['email aktif', 'email pemohon', 'email peneliti', 'email', 'alamat email']);
        }

        if ($identity['nidn_nip'] === '') {
            $identity['nidn_nip'] = submission_identity_match($indexed, ['nidn / nip', 'nidn/nip', 'nidn', 'nip', 'nuptk', 'nik']);
        }

        if ($identity['unit_kerja'] === '') {
            $identity['unit_kerja'] = submission_identity_match($indexed, ['unit kerja', 'fakultas', 'program studi', 'prodi', 'instansi', 'bagian']);
        }

        if ($identity['applicant_phone'] === '') {
            $identity['applicant_phone'] = submission_identity_match($indexed, ['telepon', 'nomor whatsapp', 'whatsapp', 'no hp', 'nomor hp', 'no. hp']);
        }

        return $identity;
    }
}

if (! function_exists('submission_identity_match')) {
    function submission_identity_match(array $indexedValues, array $candidates): string
    {
        foreach ($candidates as $candidate) {
            $candidate = strtolower($candidate);

            foreach ($indexedValues as $key => $value) {
                if ($value === '' || $value === '-') {
                    continue;
                }

                if ($key === $candidate || str_contains($key, $candidate) || str_contains($candidate, $key)) {
                    return $value;
                }
            }
        }

        return '';
    }
}
