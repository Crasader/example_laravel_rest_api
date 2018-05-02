<?php

namespace App\Services\Pdf;

use App\Helpers\PdfRenderer;
use App\Repositories\PdfRepository;
use App\Structs\PdfData;
use Illuminate\Support\Facades\Storage;

abstract class AbstractPdf
{
    private $pdfRenderer;
    private $pdfRepository;

    public function __construct(
        PdfRenderer $pdfRenderer,
        PdfRepository $pdfRepository
    ) {
        $this->pdfRenderer = $pdfRenderer;
        $this->pdfRepository = $pdfRepository;
    }

    /**
     * @param  int $userId
     * @param  PdfData $pdfData
     * @return string
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function createOrUpdate(int $userId, PdfData $pdfData) : string
    {
        $template = $this->getTemplate();
        $pdf = $this->pdfRenderer->render($template, $pdfData->toArray());

        $filename = $this->getFilename($userId);
        Storage::put($filename, $pdf->output());

        $attributes = [
            'user_id' => $userId,
            'type' => $this->getType(),
        ];
        $values = [
            'custom_text' => $pdfData->text,
            'filename' => $filename,
            'link' => Storage::url($filename),
        ];
        $this->pdfRepository->updateOrCreate($attributes, $values);

        return $filename;
    }

    abstract public function getType() : int;
    abstract protected function getFilename(int $userId) : string;
    abstract protected function getTemplate() : string;
}
