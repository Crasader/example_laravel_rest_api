<?php

namespace Tests\Unit\Services\Pdf;

use App\Constants\PdfTypes;
use App\Services\Pdf\ShortPdf;

class ShortPdfTest extends AbstractPdfTestCase
{
    private $pdf;

    public function setUp()
    {
        $pdfRendererWrapper = $this->mockPdfRendererWrapper();
        $pdfRepository = $this->mockPdfRepository();

        $this->pdf = new ShortPdf(
            $pdfRendererWrapper,
            $pdfRepository
        );
    }

    public function testGetType()
    {
        $response = $this->pdf->getType();
        $expectedResponse = PdfTypes::$titles[PdfTypes::SHORT];
        $this->assertEquals($expectedResponse, $response);
    }
}
