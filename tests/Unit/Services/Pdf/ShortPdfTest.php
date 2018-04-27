<?php

namespace Tests\Unit\Services\Pdf;

use App\Constants\PdfTypes;
use App\Services\Pdf\ShortPdf;
use App\Structs\PdfData;

class ShortPdfTest extends AbstractPdfTestCase
{
    private const FILENAME_PATTERN = 'short_%d.pdf';
    private const HTML_TEMPLATE = 'pdfs.short';

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
        return ShortPdf::class;
    }

    protected function getPdfTypeTitle(): string
    {
        return PdfTypes::$titles[PdfTypes::SHORT];
    }
}
