<?php

declare(strict_types=1);

namespace App\Service\Sentence;

use App\Util\Dic\WordStorage;
use App\Util\WordType;
use Hyperf\RpcServer\Annotation\RpcService;

/**
 * Class SentenceServiceImplV1
 * @package App\Service\Sentence
 * @RpcService(name="SentenceService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class SentenceServiceImplV1 implements SentenceService
{
    /**
     * @var WordStorage
     */
    private $wordStorage;

    public function __construct(WordStorage $wordStorage)
    {
        $this->wordStorage = $wordStorage;
    }

    public function match(string $sendtence, string $fromSystem, string $type): array
    {
        $result = $this->wordStorage->match($sendtence, WordType::getType($fromSystem, $type));
        $returnData = [];
        foreach ($result as $oneWord) {
            array_push($returnData, $oneWord['word']);
        }
        return $returnData;
    }
}