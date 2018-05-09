<?php

namespace App\Wrappers;

use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade as PDFFacade;

class PdfRenderer
{
    /**
     * @param  string $template
     * @param  array  $data
     * @return PDF
     */
    public function render(string $template, array $data): PDF
    {
        return PDFFacade::loadView($template, $data);
    }
}
