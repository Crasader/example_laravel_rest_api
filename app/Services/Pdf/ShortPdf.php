<?php

namespace App\Services\Pdf;

use App\Constants\PdfTypes;

class ShortPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'short_%d.pdf';
    private const HTML_TEMPLATE = 'pdf.short';

    /**
     * @return int
     */
    public function getType(): int
    {
        return PdfTypes::SHORT;
    }

    /**
     * @param int $userId
     * @return string
     */
    protected function getFilename(int $userId): string
    {
        return sprintf(self::FILENAME_PATTERN, $userId);
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return self::HTML_TEMPLATE;
    }
}
