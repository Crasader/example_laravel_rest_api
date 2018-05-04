<?php

namespace App\Services\Pdf;

use App\Exceptions\PdfException;
use App\Repositories\PdfRepository;
use App\Structs\PdfData;

class PdfUpdater
{
    private const PDF_ID_NOT_FOUND = 'The requested pdf id is not found.';

    /**
     * @var PdfRepository
     */
    private $pdfRepository;

    /**
     * @var PdfFactory
     */
    private $pdfFactory;

    public function __construct(PdfRepository $pdfRepository, PdfFactory $pdfFactory)
    {
        $this->pdfRepository = $pdfRepository;
        $this->pdfFactory = $pdfFactory;
    }

    /**
     * @param int $id
     * @param int $userId
     * @param PdfData $pdfData
     * @throws PdfException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(int $id, int $userId, PdfData $pdfData): void
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

        $this->pdfFactory->create($pdf->type, $userId, $pdfData);
    }
}