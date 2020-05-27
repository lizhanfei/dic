<?php

declare(strict_types=1);

namespace App\Service\Dic;

interface DicService
{
    public function db2Dic(): bool;

    public function releaseDb2Dic(): bool;
}