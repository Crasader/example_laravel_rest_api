<?php

namespace App\Helpers;

class PdfRendererWrapper
{
    /**
     * @param  string $template
     * @param  array  $data
     * @return [type]
     */
    public function render(string $template, array $data)
    {
        return PDF::loadView($template, $data);
    }
}
