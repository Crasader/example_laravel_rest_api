<?php

namespace App\Services\Pdf;

use App\Constants\PdfTypes;

class FullPdf extends AbstractPdf
{
    private const FILENAME_PATTERN = 'full_%d.pdf';
    private const HTML_TEMPLATE = 'pdf.full';

    /**
     * @return int
     */
    public function getType(): int
    {
        return PdfTypes::FULL;
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
