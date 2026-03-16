<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PdfGenerator;
use App\Models\FormCategoryModel;
use App\Models\FormFieldModel;
use App\Models\FormTypeModel;
use App\Models\SubmissionFileModel;
use App\Models\SubmissionModel;
use App\Models\SubmissionValueModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends BaseController
{
    public function index()
    {
        $filters = [
            'category_id' => $this->request->getGet('category_id'),
            'form_type_id' => $this->request->getGet('form_type_id'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'keyword' => trim((string) $this->request->getGet('keyword')),
        ];

        return view('admin/export/index', [
            'pageTitle' => 'Ekspor Data',
            'filters' => $filters,
            'categories' => (new FormCategoryModel())->orderBy('urutan', 'ASC')->findAll(),
            'forms' => (new FormTypeModel())->orderBy('urutan', 'ASC')->findAll(),
        ]);
    }

    public function excel()
    {
        $submissions = $this->filteredSubmissions();
        $exportType = $this->resolveExportType();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $fileLinks = [];

        if ($exportType === 'detail' && $this->request->getGet('form_type_id')) {
            [$headers, $rows, $fileLinks] = $this->buildDetailedExportRows($submissions, (int) $this->request->getGet('form_type_id'));
            $sheet->setTitle('Detail Form');
        } else {
            [$headers, $rows] = $this->buildSummaryExportRows($submissions);
            $sheet->setTitle('Rekap Pengajuan');
        }

        $sheet->fromArray([$headers]);

        $rowNumber = 2;
        foreach ($rows as $item) {
            $sheet->fromArray([$item], null, 'A' . $rowNumber++);
        }

        foreach ($fileLinks as $cell => $url) {
            $sheet->getCell($cell)->getHyperlink()->setUrl($url);
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('1D4ED8');
            $sheet->getStyle($cell)->getFont()->setUnderline(true);
        }

        for ($columnIndex = 1; $columnIndex <= count($headers); $columnIndex++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex))->setAutoSize(true);
        }

        $lastColumn = Coordinate::stringFromColumnIndex(count($headers));
        $sheet->getStyle('A1:' . $lastColumn . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $lastColumn . '1')->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DBEAFE');

        $writer = new Xlsx($spreadsheet);
        $filename = $this->buildExcelFilename();

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($content ?: '');
    }

    public function pdf()
    {
        $submissions = $this->filteredSubmissions();
        $exportType = $this->resolveExportType();
        $html = view('pdf/admin_submissions', [
            'submissions' => $submissions,
            'headers' => $exportType === 'detail' && $this->request->getGet('form_type_id')
                ? $this->buildDetailedPdfHeaders((int) $this->request->getGet('form_type_id'))
                : [],
            'rows' => $exportType === 'detail' && $this->request->getGet('form_type_id')
                ? $this->buildDetailedPdfRows($submissions, (int) $this->request->getGet('form_type_id'))
                : [],
            'exportType' => $exportType,
            'title' => 'Laporan Pengajuan SAFARI UNISAP',
            'generatedAt' => date('d-m-Y H:i'),
        ]);

        return (new PdfGenerator())->output($html, 'laporan-pengajuan.pdf');
    }

    public function submissionPdf(int $id)
    {
        $submission = (new SubmissionModel())->withRelations()->find($id);
        if (! $submission) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $html = view('pdf/submission_detail_admin', [
            'submission' => $submission,
            'values' => (new SubmissionValueModel())
                ->select('submission_values.*, form_fields.label_field')
                ->join('form_fields', 'form_fields.id = submission_values.form_field_id', 'left')
                ->where('submission_id', $id)
                ->findAll(),
            'files' => (new SubmissionFileModel())
                ->select('submission_files.*, form_fields.label_field')
                ->join('form_fields', 'form_fields.id = submission_files.form_field_id', 'left')
                ->where('submission_id', $id)
                ->findAll(),
            'generatedAt' => date('d-m-Y H:i'),
        ]);

        return (new PdfGenerator())->output($html, 'detail-' . $submission['submission_code'] . '.pdf');
    }

    private function filteredSubmissions(): array
    {
        return (new SubmissionModel())->applyAdminFilters([
            'category_id' => $this->request->getGet('category_id'),
            'form_type_id' => $this->request->getGet('form_type_id'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'keyword' => trim((string) $this->request->getGet('keyword')),
        ])->findAll();
    }

    private function buildExcelFilename(): string
    {
        $parts = [
            $this->resolveExportType() === 'detail' ? 'detail-pengajuan' : 'rekap-pengajuan',
            'safari-unisap',
        ];

        if ($this->request->getGet('form_type_id')) {
            $form = (new FormTypeModel())->find((int) $this->request->getGet('form_type_id'));
            if ($form) {
                $parts[] = url_title($form['nama_form'], '-', true);
            }
        }

        $parts[] = date('Ymd-His');

        return implode('-', $parts) . '.xlsx';
    }

    private function resolveExportType(): string
    {
        $exportType = strtolower((string) $this->request->getGet('export_type'));

        if ($exportType === 'detail' && ! $this->request->getGet('form_type_id')) {
            return 'summary';
        }

        return $exportType === 'detail' ? 'detail' : 'summary';
    }

    private function buildSummaryExportRows(array $submissions): array
    {
        $headers = ['Nomor Pengajuan', 'Formulir', 'Pengirim Formulir', 'Email', 'Telepon', 'Tanggal Pengajuan'];
        $rows = [];

        foreach ($submissions as $item) {
            $rows[] = [
                $item['submission_code'],
                $item['form_name'],
                $item['applicant_name'],
                $item['applicant_email'],
                $item['applicant_phone'],
                $item['submitted_at'],
            ];
        }

        return [$headers, $rows];
    }

    private function buildDetailedExportRows(array $submissions, int $formTypeId): array
    {
        $fields = (new FormFieldModel())->getByFormType($formTypeId);
        $headers = [
            'Nomor Pengajuan',
            'Tanggal Pengajuan',
        ];

        foreach ($fields as $field) {
            $headers[] = $field['label_field'];
        }

        $submissionIds = array_map(static fn ($submission) => (int) $submission['id'], $submissions);
        $valueMap = $this->loadSubmissionValueMap($submissionIds);
        $fileMap = $this->loadSubmissionFileMap($submissionIds);
        $rows = [];
        $fileLinks = [];
        $excelRowNumber = 2;

        foreach ($submissions as $item) {
            $row = [
                $item['submission_code'],
                $item['submitted_at'],
            ];

            foreach ($fields as $field) {
                $fieldId = (int) $field['id'];
                $submissionId = (int) $item['id'];
                $columnNumber = count($row) + 1;

                if ($field['tipe_field'] === 'file') {
                    if (isset($fileMap[$submissionId][$fieldId])) {
                        $fileInfo = $fileMap[$submissionId][$fieldId];
                        $row[] = $fileInfo['nama_file_asli'];
                        $fileLinks[Coordinate::stringFromColumnIndex($columnNumber) . $excelRowNumber] = site_url('admin/pengajuan/file/' . $fileInfo['id']);
                    } else {
                        $row[] = '-';
                    }
                    continue;
                }

                $row[] = $valueMap[$submissionId][$fieldId] ?? '-';
            }

            $rows[] = $row;
            $excelRowNumber++;
        }

        return [$headers, $rows, $fileLinks];
    }

    private function buildDetailedPdfHeaders(int $formTypeId): array
    {
        $headers = ['Nomor', 'Tanggal'];

        foreach ((new FormFieldModel())->getByFormType($formTypeId) as $field) {
            $headers[] = $field['label_field'];
        }

        return $headers;
    }

    private function buildDetailedPdfRows(array $submissions, int $formTypeId): array
    {
        [$headers, $rows] = $this->buildDetailedExportRows($submissions, $formTypeId);

        $pdfRows = [];
        foreach ($rows as $row) {
            $pdfRows[] = array_merge(
                [$row[0], $row[1]],
                array_slice($row, 2)
            );
        }

        return $pdfRows;
    }

    private function loadSubmissionValueMap(array $submissionIds): array
    {
        if ($submissionIds === []) {
            return [];
        }

        $rows = (new SubmissionValueModel())
            ->whereIn('submission_id', $submissionIds)
            ->findAll();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['submission_id']][(int) $row['form_field_id']] = $this->normalizeValueRow($row);
        }

        return $map;
    }

    private function loadSubmissionFileMap(array $submissionIds): array
    {
        if ($submissionIds === []) {
            return [];
        }

        $rows = (new SubmissionFileModel())
            ->whereIn('submission_id', $submissionIds)
            ->findAll();

        $map = [];
        foreach ($rows as $row) {
            if ($row['form_field_id'] === null) {
                continue;
            }

            $map[(int) $row['submission_id']][(int) $row['form_field_id']] = [
                'id' => (int) $row['id'],
                'nama_file_asli' => $row['nama_file_asli'],
            ];
        }

        return $map;
    }

    private function normalizeValueRow(array $row): string
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
