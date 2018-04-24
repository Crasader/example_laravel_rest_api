<?php

namespace App\Services\Pdf;

use AbstractPdf;

class AdvancedPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'advanced_%d.pdf';
    private const HTML_TEMPLATE = 'advanced.blade';
    private const TYPE = 'advanced';

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
