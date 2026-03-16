<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public array $adminLogin = [
        'identity' => 'required|min_length[3]',
        'password' => 'required|min_length[6]',
    ];

    public array $adminLoginErrors = [
        'identity' => [
            'required' => 'Username atau email wajib diisi.',
        ],
        'password' => [
            'required' => 'Password wajib diisi.',
        ],
    ];

    public array $statusCheck = [
        'submission_code' => 'required|min_length[10]',
        'applicant_email' => 'required|valid_email',
    ];

    public array $statusCheckErrors = [
        'submission_code' => [
            'required' => 'Kode pengajuan wajib diisi.',
        ],
        'applicant_email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
        ],
    ];
}
