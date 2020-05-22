<?php

declare(strict_types=1);

namespace App\Util\Dic;

/**
 * Interface WordStorage
 * @package App\Util\Dic
 */
interface WordStorage
{
    public function add(string $word, string $wordType): bool;

    public function del(string $word, string $wordType): bool;

    public function clear(string $wordType): bool;

    /**
     * 用 $wordTypeNew 的字典覆盖 $wordType
     * @param string $wordType
     * @param string $wordTypeNew
     * @return bool
     */
    public function replace(string $wordType, string $wordTypeNew): bool;

    public function match(string $sentence, string $wordType): array;

    public function findWord(string $word, string $wordType): array;

}