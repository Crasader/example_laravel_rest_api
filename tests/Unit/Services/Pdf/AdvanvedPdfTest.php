<?php

namespace Tests\Unit\Services\Pdf;

use App\Constants\PdfTypes;
use App\Services\Pdf\AdvancedPdf;

class AdvanvedPdfTest extends AbstractPdfTestCase
{
    private $pdf;

    public function setUp()
    {
        $pdfRendererWrapper = $this->mockPdfRendererWrapper();
        $pdfRepository = $this->mockPdfRepository();

        $this->pdf = new AdvancedPdf(
            $pdfRendererWrapper,
            $pdfRepository
        );
    }

    public function testGetType()
    {
        $response = $this->pdf->getType();
        $expectedResponse = PdfTypes::$titles[PdfTypes::ADVANCED];
        $this->assertEquals($expectedResponse, $response);
    }
}
