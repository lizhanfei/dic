<?php

declare(strict_types=1);

namespace App\Service\Word;

use App\Util\Dic\WordStorage;
use App\Util\WordType;
use Hyperf\Di\Annotation\Inject;
use Hyperf\RpcServer\Annotation\RpcService;

/**
 * Class WordServiceImplV1
 * @package App\Service\Word
 * @RpcService(name="WordService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class WordServiceImplV1 implements WordService
{
    /**
     * @Inject
     * @var WordStorage
     */
    private $wordStorage;

    public function find(string $word, string $fromSystem, string $type): array
    {
        $result = $this->wordStorage->findWord($word, WordType::getType($fromSystem, $type));
        $returnData = [];
        foreach ($result as $oneWord) {
            array_push($returnData, $oneWord['word']);
        }
        return $returnData;
    }
}