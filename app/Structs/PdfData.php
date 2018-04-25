<?php

namespace App\Structs;

use Illuminate\Contracts\Support\Arrayable;

class PdfData implements Arrayable
{
    public $name;
    public $email;
    public $text = '';

    public function toArray() : array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'name' => $this->text,
        ];
    }
}
