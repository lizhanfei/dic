<?php

declare(strict_types=1);

namespace App\Util;

class Response
{
    public static function arr(int $code, string $msg, array $data = [])
    {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }
}