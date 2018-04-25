<?php

namespace App\Services\Pdf;

use AbstractPdf;
use App\Constants\PdfTypes;

class FullPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'full_%d.pdf';
    private const HTML_TEMPLATE = 'pdfs.full';

    public function getType() : string
    {
        return PdfTypes::$titles[PdfTypes::FULL];
    }

    protected function getFilename(int $userId) : string
    {
        return sprintf(self::FILENAME_PATTERN, $userId);
    }

    protected function getTemplate() : string
    {
        return self::HTML_TEMPLATE;
    }
}
