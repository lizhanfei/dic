<?php

declare(strict_types=1);

namespace App\Util\Dic;

use \AbelZhou\Tree\TrieTree;

class WordMemory implements WordStorage
{
    public static $triTree = [];

    private function init(string $wordType)
    {
        if (!isset(static::$triTree[$wordType])) {
            static::$triTree[$wordType] = new TrieTree();
        }
    }

    public function add(string $word, string $wordType): bool
    {
        $this->init($wordType);
        //static::$triTree[$wordType]->append($word, array("replace" => str_pad("", mb_strlen($word), "*")));
        static::$triTree[$wordType]->append($word);
        return true;
    }

    public function del(string $word, string $wordType): bool
    {
        if (!isset(static::$triTree[$wordType])) {
            return true;
        }
        return static::$triTree[$wordType]->delete($word);
    }

    public function clear(string $wordType): bool
    {
        static::$triTree[$wordType] = new TrieTree();
        return true;
    }

    public function replace(string $wordType, string $wordTypeNew): bool
    {
        static::$triTree[$wordType] = static::$triTree[$wordTypeNew];
        return true;
    }

    public function match(string $sentence, string $wordType): array
    {
        if (!isset(static::$triTree[$wordType])) {
            return [];
        }
        return static::$triTree[$wordType]->search($sentence);
    }

    public function findWord(string $word, string $wordType): array
    {
        if (!isset(static::$triTree[$wordType])) {
            return [];
        }
        return static::$triTree[$wordType]->getTreeWord($word);
    }
}