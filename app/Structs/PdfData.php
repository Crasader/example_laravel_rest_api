<?php

namespace App\Structs;

use Illuminate\Contracts\Support\Arrayable;

class PdfData implements Arrayable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $text = '';

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'text' => $this->text,
        ];
    }
}
