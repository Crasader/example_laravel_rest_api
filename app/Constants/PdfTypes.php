<?php

namespace App\Constants;

abstract class PdfTypes
{
    const SHORT = 0;
    const FULL = 1;
    const ADVANCED = 2;

    /**
     * @var string[]
     */
    public static $titles = [
        self::SHORT => 'short',
        self::FULL => 'full',
        self::ADVANCED => 'advanced',
    ];
}
