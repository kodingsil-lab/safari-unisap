<?php

namespace App\Libraries;

use App\Models\AdminModel;
use App\Models\SubmissionFileModel;
use App\Models\SubmissionModel;
use App\Models\SubmissionValueModel;
use Config\Services;

class FormNotificationService
{
    public function sendSubmissionNotifications(int $submissionId): void
    {
        if (! $this->isMailConfigured()) {
            log_message('warning', 'Notifikasi email dilewati karena konfigurasi email belum lengkap.');
            return;
        }

        try {
            $payload = $this->buildPayload($submissionId);

            if ($payload === null) {
                return;
            }

            $this->sendToApplicant($payload);
            $this->sendToAdmins($payload);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal memproses notifikasi email formulir: {message}', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function isMailConfigured(): bool
    {
        $protocol = (string) env('email.protocol', 'mail');
        $fromEmail = trim((string) env('email.fromEmail', ''));

        if ($fromEmail === '') {
            return false;
        }

        if ($protocol !== 'smtp') {
            return true;
        }

        return trim((string) env('email.SMTPHost', '')) !== ''
            && trim((string) env('email.SMTPUser', '')) !== ''
            && trim((string) env('email.SMTPPass', '')) !== ''
            && (int) env('email.SMTPPort', 0) > 0;
    }

    private function buildPayload(int $submissionId): ?array
    {
        $submission = (new SubmissionModel())
            ->withRelations()
            ->find($submissionId);

        if (! $submission) {
            return null;
        }

        $values = (new SubmissionValueModel())
            ->select('submission_values.*, form_fields.label_field, form_fields.tipe_field')
            ->join('form_fields', 'form_fields.id = submission_values.form_field_id', 'left')
            ->where('submission_id', $submissionId)
            ->orderBy('form_fields.urutan', 'ASC')
            ->findAll();

        $files = (new SubmissionFileModel())
            ->select('submission_files.*, form_fields.label_field, form_fields.tipe_field')
            ->join('form_fields', 'form_fields.id = submission_files.form_field_id', 'left')
            ->where('submission_id', $submissionId)
            ->orderBy('form_fields.urutan', 'ASC')
            ->findAll();

        return [
            'submission' => $submission,
            'values' => $this->normalizeValues($values),
            'files' => $this->normalizeFiles($files),
            'proofUrl' => site_url('bukti/' . $submission['submission_code']),
            'adminUrl' => site_url('admin/pengajuan/' . $submission['id']),
            'siteName' => system_setting('site_name', 'SAFARI UNISAP'),
            'siteTagline' => system_setting('site_tagline', 'Sistem Administrasi Formulir Universitas San Pedro'),
            'campusName' => system_setting('campus_name', 'Universitas San Pedro'),
            'supportEmail' => system_setting('support_email', 'layanan@unisap.ac.id'),
            'supportPhone' => system_setting('support_phone', '-'),
        ];
    }

    private function sendToApplicant(array $payload): void
    {
        $recipient = trim((string) ($payload['submission']['applicant_email'] ?? ''));

        if ($recipient === '') {
            return;
        }

        $email = Services::email();
        $email->clear(true);
        $email->setTo($recipient);
        $email->setSubject('Konfirmasi Pengiriman Formulir - ' . $payload['submission']['form_name']);
        $email->setMessage(view('emails/submission_applicant', $payload));
        $email->setMailType('html');

        if (! $email->send(false)) {
            log_message('error', 'Gagal mengirim email ke pengirim formulir {email}: {debug}', [
                'email' => $recipient,
                'debug' => strip_tags((string) $email->printDebugger(['headers'])),
            ]);
        }
    }

    private function sendToAdmins(array $payload): void
    {
        $adminRecipients = (new AdminModel())
            ->where('is_active', 1)
            ->findAll();

        foreach ($adminRecipients as $admin) {
            $recipient = trim((string) ($admin['email'] ?? ''));

            if ($recipient === '') {
                continue;
            }

            $email = Services::email();
            $email->clear(true);
            $email->setTo($recipient);
            $email->setSubject('Formulir Baru Masuk - ' . $payload['submission']['form_name']);
            $email->setMessage(view('emails/submission_admin', $payload + ['admin' => $admin]));
            $email->setMailType('html');

            if (! $email->send(false)) {
                log_message('error', 'Gagal mengirim email ke admin {email}: {debug}', [
                    'email' => $recipient,
                    'debug' => strip_tags((string) $email->printDebugger(['headers'])),
                ]);
            }
        }
    }

    private function normalizeValues(array $values): array
    {
        $rows = [];

        foreach ($values as $value) {
            $rows[] = [
                'label' => $value['label_field'] ?: $value['nama_field'],
                'value' => $this->normalizeValue($value),
            ];
        }

        return $rows;
    }

    private function normalizeFiles(array $files): array
    {
        $rows = [];

        foreach ($files as $file) {
            $rows[] = [
                'label' => $file['label_field'] ?: ('Isian #' . $file['form_field_id']),
                'value' => $file['nama_file_asli'],
            ];
        }

        return $rows;
    }

    private function normalizeValue(array $row): string
    {
        if (! empty($row['nilai_longtext'])) {
            return (string) $row['nilai_longtext'];
        }

        if (! empty($row['nilai_text'])) {
            return (string) $row['nilai_text'];
        }

        if (! empty($row['nilai_date'])) {
            return (string) $row['nilai_date'];
        }

        if ($row['nilai_number'] !== null && $row['nilai_number'] !== '') {
            return (string) $row['nilai_number'];
        }

        if (! empty($row['nilai_json'])) {
            $decoded = json_decode((string) $row['nilai_json'], true);

            if (is_array($decoded)) {
                return implode(', ', array_map('strval', $decoded));
            }

            return (string) $row['nilai_json'];
        }

        return '-';
    }
}
