<?php

namespace App\Services\Pdf;

use App\Services\Pdf\AbstractPdf;
use App\Services\Pdf\ShortPdf;
use App\Services\Pdf\FullPdf;
use App\Services\Pdf\AdvancedPdf;
use App\Structs\PdfData;

class PdfFactory
{
    private const PDFS = [
        ShortPdf::class,
        FullPdf::class,
        AdvancedPdf::class,
    ];

    /**
     * @param  int $userId
     * @param  PdfData[] $pdfDataArray
     * @return void
     */
    public function createAll(int $userId, $pdfDataArray) : void
    {
        foreach (self::PDFS as $pdf) {
            if ($pdf instanceof AbstractPdf) {
                $pdf->create($userId, $pdfDataArray);
            }
        }
    }
}
