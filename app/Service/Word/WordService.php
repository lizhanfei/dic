<?php

declare(strict_types=1);

namespace App\Service\Word;

interface WordService
{
    public function find(string $word, string $fromSystem, string $type): array;
}