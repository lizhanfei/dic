<?php

declare(strict_types=1);

namespace App\Service\Dic;

/**
 * 词典持久化服务
 * Interface PersistentService
 * @package App\Service\Dic
 */
interface PersistentService
{
    public function add(string $word, string $fromSystem, string $type): bool;

    public function del(string $word, string $fromSystem, string $type): bool;

}