<?php

declare(strict_types=1);

namespace App\Service\Sentence;

interface SentenceService
{
    public function match(string $sendtence, string $fromSystem, string $type): array;
}