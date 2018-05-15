<?php

namespace App\Services\Pdf;

use App\Wrappers\PdfRenderer;
use App\Repositories\PdfRepository;
use App\Structs\PdfData;
use Illuminate\Support\Facades\Storage;

abstract class AbstractPdf
{
    /**
     * @var PdfRenderer
     */
    private $pdfRenderer;

    /**
     * @var PdfRepository
     */
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
     */
    public function createOrUpdate(int $userId, PdfData $pdfData): string
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

    /**
     * @return int
     */
    abstract public function getType() : int;

    /**
     * @param int $userId
     * @return string
     */
    abstract protected function getFilename(int $userId) : string;

    /**
     * @return string
     */
    abstract protected function getTemplate() : string;
}
