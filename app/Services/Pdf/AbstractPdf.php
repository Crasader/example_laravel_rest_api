<?php

namespace App\Services\Pdf;

use App\Helpers\PdfRendererWrapper;
use App\Repositories\PdfRepository;
use App\Structs\PdfData;
use App\Exceptions\PdfException;

abstract class AbstractPdf
{
    const PDF_DATA_NOT_EXISTS = "PdfData instance doesn't exist for the '%s' pdf type.";

    private $pdfRendererWrapper;
    private $pdfRepository;

    public function __construct(
        PdfRendererWrapper $pdfRendererWrapper,
        PdfRepository $pdfRepository
    ) {
        $this->pdfRendererWrapper = $pdfRendererWrapper;
        $this->pdfRepository = $pdfRepository;
    }


    /**
     * @param  int $userId
     * @param  PdfData[] $pdfDataArray
     * @throws PdfException
     * @return string
     */
    public function create(int $userId, $pdfDataArray) : string
    {
        $pdfType = $this->getType();
        if (!array_key_exists($pdfType, $pdfDataArray)) {
            throw new PdfException(sprintf(self::PDF_DATA_NOT_EXISTS, $pdfType));
        }

        $template = $this->getTemplate();
        $pdfData = $pdfDataArray[$pdfType];
        $pdf = $this->pdfRendererWrapper->render($template, $pdfData->toArray());

        $filename = $this->getFilename($userId);
        Storage::put($filename, $pdf->output());

        $values = [
            'user_id' => $userId,
            'custom_text' => $pdfData->text,
            'link' => $filename,
        ];
        $this->pdfRepository->create($values);

        return $filename;
    }

    abstract public function getType() : string;
    abstract protected function getFilename(int $userId) : string;
    abstract protected function getTemplate() : string;
}
