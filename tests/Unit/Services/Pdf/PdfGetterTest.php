<?php

namespace Tests\Unit\Services\Pdf;

use App\Exceptions\PdfException;
use App\Pdf;
use App\Repositories\PdfRepository;
use App\Services\Pdf\PdfGetter;
use Tests\TestCase;

class PdfGetterTest extends TestCase
{
    private const USER_ID = 1;
    private const PDF_ID = 12;

    private $pdfGetter;
    private $pdfRepositoryWithException = false;

    public function setUp()
    {
        $pdfRepository = $this->mockPdfRepository();
        $this->pdfGetter = new PdfGetter($pdfRepository);
    }

    public function testUpdate_Correct()
    {
        $response = $this->pdfGetter->get(self::USER_ID, self::PDF_ID);
        $this->assertInstanceOf(PDF::class, $response);
    }

    public function testUpdate_WithException()
    {
        $this->pdfRepositoryWithException = true;
        $this->expectException(PdfException::class);

        $this->pdfGetter->get(self::USER_ID, self::PDF_ID);
        $this->assertTrue(true);
    }

    public function getPdfRepositoryReturn()
    {
        if ($this->pdfRepositoryWithException) {
            return collect();
        }

        $pdf = new Pdf;
        return collect([$pdf]);
    }

    private function mockPdfRepository()
    {
        $mockedClass = \Mockery::mock(PdfRepository::class);
        $mockedClass->shouldReceive('findWhere')
            ->andReturnUsing([$this, 'getPdfRepositoryReturn']);

        return $mockedClass;
    }
}