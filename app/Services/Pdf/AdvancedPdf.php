<?php

namespace App\Services\Pdf;

use AbstractPdf;
use App\Constants\PdfTypes;

class AdvancedPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'advanced_%d.pdf';
    private const HTML_TEMPLATE = 'pdfs.advanced';

    public function getType() : string
    {
        return PdfTypes::$titles[PdfTypes::ADVANCED];
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
