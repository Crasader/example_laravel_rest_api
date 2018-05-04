<?php

namespace App\Wrappers;

use Dompdf\Dompdf;

class PdfRenderer
{
    /**
     * @param  string $template
     * @param  array  $data
     * @return Dompdf
     */
    public function render(string $template, array $data): Dompdf
    {
        return PDF::loadView($template, $data);
    }
}
