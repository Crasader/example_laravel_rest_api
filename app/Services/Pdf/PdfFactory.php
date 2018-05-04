<?php

namespace App\Services\Pdf;

use App\Constants\PdfTypes;
use App\Structs\PdfData;
use App\Exceptions\PdfException;

class PdfFactory
{
    private const PDF_DATA_NOT_EXISTS = "PdfData instance doesn't exist for the '%s' pdf type.";
    private const PDF_TYPE_NOT_EXISTS = "The requested pdf type with '%d' id doesn't exist.";

    private const PDFs = [
        PdfTypes::SHORT => ShortPdf::class,
        PdfTypes::FULL => FullPdf::class,
        PdfTypes::ADVANCED => AdvancedPdf::class,
    ];

    /**
     * @param  int $userId
     * @param  PdfData[] $pdfDataArray
     * @return void
     * @throws PdfException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function createAll(int $userId, $pdfDataArray) : void
    {
        foreach (self::PDFs as $type => $className) {
            $pdf = resolve($className);
            if ($pdf instanceof AbstractPdf) {
                $pdfData = $this->getPdfDataFromArray($type, $pdfDataArray);
                $pdf->createOrUpdate($userId, $pdfData);
            }
        }
    }

    /**
     * @param int $type
     * @param int $userId
     * @param PdfData $pdfData
     * @throws PdfException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(int $type, int $userId, PdfData $pdfData): void
    {
        if (!array_key_exists($type, self::PDFs)) {
            throw new PdfException(sprintf(self::PDF_TYPE_NOT_EXISTS, $type));
        }

        $pdf = resolve(self::PDFs[$type]);
        if ($pdf instanceof AbstractPdf) {
            $pdf->createOrUpdate($userId, $pdfData);
        }
    }

    /**
     * @param int $type
     * @param PdfData[] $pdfDataArray
     * @return PdfData
     * @throws PdfException
     */
    private function getPdfDataFromArray(int $type, $pdfDataArray): PdfData
    {
        if (!array_key_exists($type, $pdfDataArray)) {
            $title = PdfTypes::$titles[$type];
            throw new PdfException(sprintf(self::PDF_DATA_NOT_EXISTS, $title));
        }

        return $pdfDataArray[$type];
    }
}
