<?php

namespace App\Services\Pdf;

use App\Services\Pdf\AbstractPdf;
use App\Services\Pdf\ShortPdf;
use App\Services\Pdf\FullPdf;
use App\Services\Pdf\AdvancedPdf;

class PdfFactory
{
    private const PDFS = [
        ShortPdf::class,
        FullPdf::class,
        AdvancedPdf::class,
    ];
    private const TEXT_FIELD_PATTERN = 'text_%s';

    public function createAll(int $userId, array $customTexts) : void
    {
        foreach (self::PDFS as $pdf) {
            if ($pdf instanceof AbstractPdf) {
                $pdfType = $pdf->getType();
                $pdf->create($userId, $this->getPdfText($customTexts, $pdfType));
            }
        }
    }

    private function getPdfText(array $customTexts, string $pdfType) : string
    {
        $field = sprintf(self::TEXT_FIELD_PATTERN, $pdfType);
        if (array_key_exists($field, $customTexts)) {
            return $customTexts[$field];
        }

        return '';
    }
}
