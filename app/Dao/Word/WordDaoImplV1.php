<?php

declare(strict_types=1);

namespace App\Dao\Word;

use App\Model\Word;

class WordDaoImplV1 implements WordDao
{
    public function getOne(array $where)
    {
        return Word::where($where)->first();
    }

    public function save(Word $word): bool
    {
        return $word->save();
    }

    public function delOne(Word $word): bool
    {
        return $word->delete();
    }

    public function list(array $where, int $offset, int $limit): array
    {
        $wordModelList = Word::offset($offset)->limit($limit)->get();
        if (0 == count($wordModelList)) {
            return [];
        }
        return $wordModelList->toArray();
    }
}