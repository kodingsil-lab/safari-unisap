<?php

namespace App\Libraries;

use CodeIgniter\HTTP\ResponseInterface;
use Mpdf\Mpdf;

class PdfGenerator
{
    public function output(string $html, string $filename, string $destination = 'I'): ResponseInterface|string
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 12,
            'margin_bottom' => 12,
            'margin_left' => 12,
            'margin_right' => 12,
        ]);

        $mpdf->SetTitle($filename);
        $mpdf->WriteHTML($html);

        if ($destination === 'S') {
            return $mpdf->Output($filename, 'S');
        }

        $binary = $mpdf->Output($filename, 'S');

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        return service('response')
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($binary);
    }
}
