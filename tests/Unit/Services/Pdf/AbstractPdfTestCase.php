<?php

namespace Tests\Unit\Services\Pdf;

use App\Constants\PdfTypes;
use Tests\TestCase;
use App\Helpers\PdfRenderer;
use App\Repositories\PdfRepository;
use App\Structs\PdfData;
use Illuminate\Support\Facades\Storage;

abstract class AbstractPdfTestCase extends TestCase
{
    protected const USER_ID = 1;
    private const USER_NAME = 'John Doe';
    private const USER_EMAIL = 'john.doe@email.com';

    protected $pdf;

    public function setUp()
    {
        $pdfRenderer = $this->mockPdfRenderer();
        $pdfRepository = $this->mockPdfRepository();

        $pdfClassName = $this->getPdfClass();
        $this->pdf = new $pdfClassName(
            $pdfRenderer,
            $pdfRepository
        );
    }

    public function testGetType()
    {
        $response = $this->pdf->getType();
        $expectedResponse = $this->getPdfType();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate_Correct()
    {
        Storage::shouldReceive('put')
            ->once();

        $pdfDataArray = $this->getPdfDataArray();
        $response = $this->pdf->create(self::USER_ID, $pdfDataArray[$this->getPdfType()]);

        $excepectedResponse = $this->getFilename();
        $this->assertEquals($excepectedResponse, $response);
    }

    abstract protected function getFilename(): string;
    abstract protected function getTemplate(): string;
    abstract protected function getPdfData(): PdfData;
    abstract protected function getPdfClass(): string;
    abstract protected function getPdfType(): int;

    protected function getPdfDataArray()
    {
        $shortPdfData = new PdfData;
        $shortPdfData->name = self::USER_NAME;
        $shortPdfData->email = self::USER_EMAIL;
        $shortPdfData->text = 'short_custom_text';

        $fullPdfData = new PdfData;
        $fullPdfData->name = self::USER_NAME;
        $fullPdfData->email = self::USER_EMAIL;
        $fullPdfData->text = 'full_custom_text';

        $advancedPdfData = new PdfData;
        $advancedPdfData->name = self::USER_NAME;
        $advancedPdfData->email = self::USER_EMAIL;
        $advancedPdfData->text = 'advanced_custom_text';

        $pdfDataArray = [
            PdfTypes::SHORT => $shortPdfData,
            PdfTypes::FULL => $fullPdfData,
            PdfTypes::ADVANCED => $advancedPdfData,
        ];

        return $pdfDataArray;
    }

    protected function mockPdfRenderer()
    {
        $mockedClass = \Mockery::mock(PdfRenderer::class);
        $mockedClass->shouldReceive('render')
            ->with($this->getTemplate(), $this->getPdfData()->toArray())
            ->andReturn(new \Dompdf\Dompdf);

        return $mockedClass;
    }

    protected function mockPdfRepository()
    {
        $values = [
            'user_id' => self::USER_ID,
            'custom_text' => $this->getPdfData()->text,
            'link' => $this->getFilename(),
            'type' => $this->getPdfType(),
        ];

        $mockedClass = \Mockery::mock(PdfRepository::class);
        $mockedClass->shouldReceive('create')
            ->with($values);

        return $mockedClass;
    }
}
