<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;

class PdfService
{
    private $domPdf;

    public function __construct()
    {
        $this->domPdf = new Dompdf();

        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Helvetica');

        $this->domPdf->setOptions($pdfOptions);
    }

    public function showPdfFile($html)
    {
        $this->domPdf->loadHtml($html);

        $this->domPdf->setPaper('A4', 'portrait');

        $this->domPdf->render();

        $this->domPdf->stream('details.pdf', [
            'Attachment' => false
        ]);
    }

    public function generateBinaryPdf($html)
    {
        $this->domPdf->loadHtml($html);

        $this->domPdf->setPaper('A4', 'portrait');

        $this->domPdf->render();

        return $this->domPdf->output();
    }
}