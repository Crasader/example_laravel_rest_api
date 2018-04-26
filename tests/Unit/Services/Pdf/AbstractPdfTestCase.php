<?php

namespace Tests\Unit\Services\Pdf;

use Tests\TestCase;
use App\Helpers\PdfRendererWrapper;
use App\Repositories\PdfRepository;
use App\Services\Pdf\AbstractPdf;
use App\Structs\PdfData;

class AbstractPdfTestCase extends TestCase
{
    const USER_ID = 1;
    const USER_NAME = 'John Doe';
    const USER_EMAIL = 'john.doe@email.com';
    const SHORT_PDF_CUSTOM_TEXT = 'short_custom_text';
    const FULL_PDF_CUSTOM_TEXT = 'full_custom_text';
    const ADVANCED_PDF_CUSTOM_TEXT = 'advanced_custom_text';

    public function testCreate_EverythingOkReturnFilename()
    {

    }

    protected function getPdfDataArray()
    {
        $shortPdfData = new PdfData;
        $shortPdfData->name = self::USER_NAME;
        $shortPdfData->email = self::USER_EMAIL;
        $shortPdfData->text = self::SHORT_PDF_CUSTOM_TEXT;

        $fullPdfData = new PdfData;
        $fullPdfData->name = self::USER_NAME;
        $fullPdfData->email = self::USER_EMAIL;
        $fullPdfData->text = self::FULL_PDF_CUSTOM_TEXT;

        $advancedPdfData = new PdfData;
        $advancedPdfData->name = self::USER_NAME;
        $advancedPdfData->email = self::USER_EMAIL;
        $advancedPdfData->text = self::ADVANCED_PDF_CUSTOM_TEXT;

        $pdfDataArray = [$shortPdfData, $fullPdfData, $advancedPdfData];

        return $pdfDataArray;
    }

    protected function mockPdfRendererWrapper()
    {
        $mockedClass = \Mockery::mock(PdfRendererWrapper::class);
        $mockedClass->shouldReceive('render')
            ->andReturn(null);

        return $mockedClass;
    }

    protected function mockPdfRepository()
    {
        $mockedClass = \Mockery::mock(PdfRepository::class);
        $mockedClass->shouldReceive('create')
            ->andReturn(null);

        return $mockedClass;
    }
}
