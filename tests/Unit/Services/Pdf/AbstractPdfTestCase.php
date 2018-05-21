<?php

namespace Tests\Unit\Services\Pdf;

use App\Constants\PdfTypes;
use App\Services\Pdf\AbstractPdf;
use Tests\TestCase;
use App\Wrappers\PdfRenderer;
use App\Repositories\PdfRepository;
use App\Structs\PdfData;
use Illuminate\Support\Facades\Storage;

abstract class AbstractPdfTestCase extends TestCase
{
    protected const USER_ID = 1;
    private const USER_NAME = 'John Doe';
    private const USER_EMAIL = 'john.doe@email.com';

    /**
     * @var AbstractPdf
     */
    protected $pdf;

    public function setUp()
    {
        parent::setUp();

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

    /**
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function testCreate_Correct()
    {
        Storage::shouldReceive('put')
            ->once();

        $url = $this->getStorageUrl($this->getFilename());
        Storage::shouldReceive('url')
            ->with($this->getFilename())
            ->andReturn($url)
            ->once();

        $pdfDataArray = $this->getPdfDataArray();
        $response = $this->pdf->createOrUpdate(self::USER_ID, $pdfDataArray[$this->getPdfType()]);

        $excepectedResponse = $this->getFilename();
        $this->assertEquals($excepectedResponse, $response);
    }

    /**
     * @return string
     */
    abstract protected function getFilename(): string;

    /**
     * @return string
     */
    abstract protected function getTemplate(): string;

    /**
     * @return PdfData
     */
    abstract protected function getPdfData(): PdfData;

    /**
     * @return string
     */
    abstract protected function getPdfClass(): string;

    /**
     * @return int
     */
    abstract protected function getPdfType(): int;

    /**
     * @return PdfData[]
     */
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

    /**
     * @return \Mockery\MockInterface
     */
    protected function mockPdfRenderer()
    {
        $pdfMockedClass = \Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $pdfMockedClass->shouldReceive('output');

        $mockedClass = \Mockery::mock(PdfRenderer::class);
        $mockedClass->shouldReceive('render')
            ->with($this->getTemplate(), $this->getPdfData()->toArray())
            ->andReturn($pdfMockedClass);

        return $mockedClass;
    }

    /**
     * @return \Mockery\MockInterface
     */
    protected function mockPdfRepository()
    {
        $attributes = [
            'user_id' => self::USER_ID,
            'type' => $this->getPdfType(),
        ];
        $values = [
            'custom_text' => $this->getPdfData()->text,
            'filename' => $this->getFilename(),
            'link' => $this->getStorageUrl($this->getFilename()),
        ];

        $mockedClass = \Mockery::mock(PdfRepository::class);
        $mockedClass->shouldReceive('updateOrCreate')
            ->with($attributes, $values);

        return $mockedClass;
    }

    /**
     * @param string $filename
     * @return string
     */
    private function getStorageUrl(string $filename): string
    {
        return sprintf('%s/%s', config('filesystems.disks.public.url'), $filename);
    }
}
