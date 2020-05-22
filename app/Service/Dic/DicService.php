<?php

declare(strict_types=1);

namespace App\Service\Dic;

interface DicService
{
    public function add(string $word, string $fromSystem, string $type): bool;

    public function del(string $word, string $fromSystem, string $type): bool;

    public function db2Dic(): bool;

    public function releaseDb2Dic(): bool;
}