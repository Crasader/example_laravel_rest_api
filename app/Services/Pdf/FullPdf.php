<?php

namespace App\Services\Pdf;

use AbstractPdf;

class FullPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'full_%d.pdf';
    private const HTML_TEMPLATE = 'full.blade';
    private const TYPE = 'full';

    public function getType() : string
    {
        return self::TYPE;
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
