<?php

namespace App\Services\Pdf;

use App\Exceptions\PdfException;
use App\Pdf;
use App\Repositories\PdfRepository;

class PdfGetter
{
    private const PDF_ID_NOT_FOUND = 'The requested pdf id is not found.';

    /**
     * @var PdfRepository
     */
    private $pdfRepository;

    public function __construct(PdfRepository $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    /**
     * @param int $userId
     * @param int $id
     * @return Pdf
     * @throws PdfException
     */
    public function get(int $userId, int $id): Pdf
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

        return $pdf;
    }
}