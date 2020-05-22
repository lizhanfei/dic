<?php

declare(strict_types=1);

namespace App\Util;

class WordType
{
    public static function getType(string $fromSystem, string $type)
    {
        return $fromSystem . '_' . $type;
    }
}