<?php

namespace App\Constants;

abstract class PdfTypes
{
    public const SHORT = 0;
    public const FULL = 1;
    public const ADVANCED = 2;

    /**
     * @var string[]
     */
    public static $titles = [
        self::SHORT => 'short',
        self::FULL => 'full',
        self::ADVANCED => 'advanced',
    ];
}
