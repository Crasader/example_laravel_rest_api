<?php

namespace App\Services\Pdf;

use App\Helpers\StorageHelper;
use App\Repositories\PdfRepository;

abstract class AbstractPdf
{
    private $pdfRenderer;
    private $storageHelper;
    private $pdfRepository;

    public function __construct(
        $pdfRenderer,
        StorageHelper $storageHelper,
        PdfRepository $pdfRepository
    ) {
        $this->pdfRenderer = $pdfRenderer;
        $this->storageHelper = $storageHelper;
        $this->pdfRepository = $pdfRepository;
    }

    public function create(int $userId, string $text) : string
    {
        $filename = $this->pdfRenderer->render();

        $accessUrl = $this->storageHelper->save($filename);

        $values = [
            'user_id' => $userId,
            'custom_text' => $text,
            'link' => $accessUrl,
        ];
        $this->pdfRepository->create($values);

        return $accessUrl;
    }

    abstract public function getType() : string;
    abstract protected function getFilename(int $userId) : string;
    abstract protected function getTemplate() : string;
}
