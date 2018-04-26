<?php

namespace Tests\Unit\Services\Pdf;

use App\Constants\PdfTypes;
use App\Services\Pdf\FullPdf;

class FullPdfTest extends AbstractPdfTestCase
{
    private $pdf;

    public function setUp()
    {
        $pdfRendererWrapper = $this->mockPdfRendererWrapper();
        $pdfRepository = $this->mockPdfRepository();

        $this->pdf = new FullPdf(
            $pdfRendererWrapper,
            $pdfRepository
        );
    }

    public function testGetType()
    {
        $response = $this->pdf->getType();
        $expectedResponse = PdfTypes::$titles[PdfTypes::FULL];
        $this->assertEquals($expectedResponse, $response);
    }
}
