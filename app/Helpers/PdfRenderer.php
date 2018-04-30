<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use PDF;

class PdfRenderer
{
    /**
     * @param  string $template
     * @param  array  $data
     * @return Dompdf
     */
    public function render(string $template, array $data)
    {
        return PDF::loadView($template, $data);
    }
}
