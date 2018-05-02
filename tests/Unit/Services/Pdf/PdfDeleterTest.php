<?php

namespace Tests\Unit\Services\Pdf;

use App\Exceptions\PdfException;
use App\Pdf;
use App\Repositories\PdfRepository;
use App\Services\Pdf\PdfDeleter;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PdfDeleterTest extends TestCase
{
    private const USER_ID = 1;
    private const PDF_ID = 12;
    private const PDF_FILENAME = 'test_filename.pdf';

    private $pdfDeleter;
    private $pdfRepositoryWithException = false;

    public function setUp()
    {
        $pdfRepository = $this->mockPdfRepository();
        $this->pdfDeleter = new PdfDeleter($pdfRepository);
    }

    public function testUpdate_Correct()
    {
        Storage::shouldReceive('delete')
            ->with(self::PDF_FILENAME)
            ->once();

        $this->pdfDeleter->remove(self::USER_ID, self::PDF_ID);
        $this->assertTrue(true);
    }

    public function testUpdate_WithException()
    {
        $this->pdfRepositoryWithException = true;
        $this->expectException(PdfException::class);

        $this->pdfDeleter->remove(self::USER_ID, self::PDF_ID);
        $this->assertTrue(true);
    }

    public function getPdfRepositoryReturn()
    {
        if ($this->pdfRepositoryWithException) {
            return collect();
        }

        $pdf = new Pdf;
        $pdf->filename = self::PDF_FILENAME;

        return collect([$pdf]);
    }

    private function mockPdfRepository()
    {
        $mockedClass = \Mockery::mock(PdfRepository::class);
        $mockedClass->shouldReceive('findWhere')
            ->andReturnUsing([$this, 'getPdfRepositoryReturn']);

        if (!$this->pdfRepositoryWithException) {
            $mockedClass->shouldReceive('delete')
                ->with(self::PDF_ID);
        }

        return $mockedClass;
    }
}