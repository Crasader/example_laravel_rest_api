<?php

namespace App\Constants;

abstract class PdfTypes
{
    const SHORT = 1;
    const FULL = 1;
    const ADVANCED = 1;

    public $titles = [
        self::SHORT => 'short',
        self::FULL => 'full',
        self::ADVANCED => 'advanced',
    ];
}
