<?php

namespace App\Services\Pdf;

use App\Exceptions\PdfException;
use App\Repositories\PdfRepository;
use Illuminate\Support\Facades\Storage;

class PdfDeleter
{
    private const PDF_ID_NOT_FOUND = 'The requested pdf id is not found.';

    private $pdfRepository;

    public function __construct(PdfRepository $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    /**
     * @param int $userId
     * @param int $id
     * @throws PdfException
     */
    public function remove(int $userId, int $id): void
    {
        $where = [
            'id' => $id,
            'user_id' => $userId,
        ];
        $pdfCollection = $this->pdfRepository->findWhere($where);
        $pdf = $pdfCollection->first();

        if (!$pdf) {
            throw new PdfException(self::PDF_ID_NOT_FOUND);
        }

        Storage::delete($pdf->filename);
        $this->pdfRepository->delete($id);
    }
}