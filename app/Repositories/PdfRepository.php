<?php

namespace App\Repositories;

use App\Pdf;

class PdfRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return Pdf::class;
    }
}
