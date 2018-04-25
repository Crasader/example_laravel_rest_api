<?php

namespace App\Services\Pdf;

use AbstractPdf;
use App\Constants\PdfTypes;

class ShortPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'short_%d.pdf';
    private const HTML_TEMPLATE = 'pdfs.short';

    public function getType() : string
    {
        return PdfTypes::$titles[PdfTypes::SHORT];
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
