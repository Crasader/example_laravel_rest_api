<?php

namespace App\Services\Pdf;

use AbstractPdf;

class ShortPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'short_%d.pdf';
    private const HTML_TEMPLATE = 'short.blade';
    private const TYPE = 'short';

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
