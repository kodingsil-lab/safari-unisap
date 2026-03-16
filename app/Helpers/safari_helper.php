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
