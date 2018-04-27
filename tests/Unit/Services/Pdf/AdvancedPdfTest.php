<?php

namespace Tests\Unit\Services\Pdf;

use App\Constants\PdfTypes;
use App\Services\Pdf\AdvancedPdf;
use App\Structs\PdfData;

class AdvancedPdfTest extends AbstractPdfTestCase
{
    private const FILENAME_PATTERN = 'advanced_%d.pdf';
    private const HTML_TEMPLATE = 'pdfs.advanced';

    protected function getFilename(): string
    {
        return sprintf(self::FILENAME_PATTERN, self::USER_ID);
    }

    protected function getTemplate(): string
    {
        return self::HTML_TEMPLATE;
    }

    protected function getPdfData(): PdfData
    {
        $pdfDataArray = $this->getPdfDataArray();
        $pdfData = $pdfDataArray[$this->getPdfTypeTitle()];
        return $pdfData;
    }

    protected function getPdfType(): string
    {
        return AdvancedPdf::class;
    }

    protected function getPdfTypeTitle(): string
    {
        return PdfTypes::$titles[PdfTypes::ADVANCED];
    }
}
