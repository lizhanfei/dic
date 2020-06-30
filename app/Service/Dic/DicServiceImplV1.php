<?php

declare(strict_types=1);

namespace App\Service\Dic;

use App\Util\Dic\WordStorage;
use App\Util\WordType;
use App\Dao\Word\WordDao;
use Hyperf\RpcServer\Annotation\RpcService;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;
use Hyperf\Logger\LoggerFactory;
use Swoole\Coroutine;

/**
 * Class DicServiceImplV1
 * @package App\Service\Dic
 * @RpcService(name="DicService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class DicServiceImplV1 implements DicService
{
    /**
     * @var WordStorage
     */
    private $wordStorage;
    /**
     * @var WordDao
     */
    private $wordDao;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(WordStorage $wordStorage, WordDao $wordDao, LoggerFactory $loggerFactory)
    {
        $this->wordStorage = $wordStorage;
        $this->wordDao = $wordDao;
        $this->logger = $loggerFactory->get('dicService', 'default');
    }

    public function db2Dic(): bool
    {
        $page = 1;
        $pageNum = 2000;
        $count = $this->wordDao->count();
        $maxPage = ceil($count / $pageNum);
        $this->logger->debug("one page cost:". $maxPage);

        $parallel = new Parallel(10);
        while ($page < $maxPage) {
            $parallel->add(function () use ($page, $pageNum) {
                $wordModelList = $this->wordDao->list([], ($page - 1) * $pageNum, $pageNum);
                if (0 != count($wordModelList)) {
                    //循环将词写入dic
                    foreach ($wordModelList as $oneWord) {
                        $this->wordStorage->add($oneWord['word'], WordType::getType($oneWord['from_system'], $oneWord['type']));
                    }
                }
            });
            $page++;
        }

        try{
            $parallel->wait();
        } catch(ParallelExecutionException $e){
            $msg = "初始化内存词典失败,msg:{$e->getMessage()}; ";
            $msg .= "file:{$e->getFile()}; ";
            $msg .= "line:{$e->getLine()}; ";

            $this->logger->error($msg);
        }
        return true;
    }

    /**
     * 刷新告诉词典数据
     * @return bool
     */
    public function releaseDb2Dic(): bool
    {
        $prefix = 'replace';
        $wordType = [];
        $page = 1;
        $pageNum = 600;
        while (true) {
            $wordModelList = $this->wordDao->list([], ($page - 1) * $pageNum, $pageNum);
            if (0 == count($wordModelList)) {
                break;
            }
            //循环将词写入dic
            foreach ($wordModelList as $oneWord) {
                $this->wordStorage->add($oneWord['word'], $prefix . WordType::getType($oneWord['from_system'], $oneWord['type']));
                if (!in_array(WordType::getType($oneWord['from_system'], $oneWord['type']), $wordType)) {
                    array_push($wordType, WordType::getType($oneWord['from_system'], $oneWord['type']));
                }
            }
            //让出协程，防止过长时间占用导致程序无法响应
            usleep(1000);
            $page++;
        }
        //替换词典数据
        foreach ($wordType as $oneWordType) {
            $this->wordStorage->replace($oneWordType, $prefix . $oneWordType);
        }
        return true;
    }
}
