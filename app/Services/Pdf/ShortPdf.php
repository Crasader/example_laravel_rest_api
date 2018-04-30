<?php

namespace App\Services\Pdf;

use App\Constants\PdfTypes;

class ShortPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'short_%d.pdf';
    private const HTML_TEMPLATE = 'pdfs.short';

    public function getType() : int
    {
        return PdfTypes::SHORT;
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
