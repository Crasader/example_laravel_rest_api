<?php

namespace App\Services\Pdf;

use App\Structs\PdfData;
use App\Exceptions\PdfException;

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
     * @throws PdfException
     */
    public function createAll(int $userId, $pdfDataArray) : void
    {
        foreach (self::PDFS as $pdfClassName) {
            $pdf = resolve($pdfClassName);
            if ($pdf instanceof AbstractPdf) {
                $pdf->create($userId, $pdfDataArray);
            }
        }
    }
}
