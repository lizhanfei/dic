<?php

declare(strict_types=1);

namespace App\Dao\Word;

use App\Model\Word;

interface WordDao
{
    public function getOne(array $where);

    public function save(Word $word): bool;

    public function delOne(Word $word): bool;

    public function list(array $where, int $offset, int $limit): array;

    public function count(array $where = []);
}