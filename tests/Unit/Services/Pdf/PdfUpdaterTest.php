<?php

namespace Tests\Unit\Services\Pdf;

use App\Exceptions\PdfException;
use App\Pdf;
use App\Repositories\PdfRepository;
use App\Factories\PdfFactory;
use App\Services\Pdf\PdfUpdater;
use App\Structs\PdfData;
use Tests\TestCase;

class PdfUpdaterTest extends TestCase
{
    private const PDF_ID = 1;
    private const PDF_TYPE = 2;
    private const USER_ID = 1;

    private $pdfUpdater;
    private $pdfData;
    private $pdfRepositoryWithException = false;

    public function setUp()
    {
        $this->pdfData = new PdfData();
        $pdfRepository = $this->mockPdfRepository();
        $pdfFactory = $this->mockPdfFactory();
        $this->pdfUpdater = new PdfUpdater($pdfRepository, $pdfFactory);
    }

    public function testUpdate_Correct()
    {
        $this->pdfUpdater->update(self::PDF_ID, self::USER_ID, $this->pdfData);
        $this->assertTrue(true);
    }

    public function testUpdate_WithException()
    {
        $this->pdfRepositoryWithException = true;
        $this->expectException(PdfException::class);

        $this->pdfUpdater->update(self::PDF_ID, self::USER_ID, $this->pdfData);
        $this->assertTrue(true);
    }

    public function getPdfRepositoryReturn()
    {
        if ($this->pdfRepositoryWithException) {
            return collect();
        }

        $pdf = new Pdf;
        $pdf->type = self::PDF_TYPE;

        return collect([$pdf]);
    }

    private function mockPdfRepository()
    {
        $mockedClass = \Mockery::mock(PdfRepository::class);
        $mockedClass->shouldReceive('findWhere')
            ->andReturnUsing([$this, 'getPdfRepositoryReturn']);

        return $mockedClass;
    }

    private function mockPdfFactory()
    {
        $mockedClass = \Mockery::mock(PdfFactory::class);
        $mockedClass->shouldReceive('create')
            ->with(self::PDF_TYPE, self::USER_ID, $this->pdfData);

        return $mockedClass;
    }
}