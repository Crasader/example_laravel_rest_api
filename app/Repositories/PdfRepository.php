<?php

namespace App\Repositories;

use App\Pdf;
use App\Repositories\AbstractRepository;

class PdfRepository extends AbstractRepository
{
    public function model() : string
    {
        return Pdf::class;
    }
}
