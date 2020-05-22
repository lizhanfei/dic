<?php

declare(strict_types=1);

namespace App\Service\Dic;

use App\Model\Word;
use App\Util\Dic\WordStorage;
use App\Util\WordType;
use Hyperf\Di\Annotation\Inject;
use App\Dao\Word\WordDao;
use Hyperf\RpcServer\Annotation\RpcService;

/**
 * Class DicServiceImplV1
 * @package App\Service\Dic
 * @RpcService(name="DicService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class DicServiceImplV1 implements DicService
{
    /**
     * @Inject
     * @var WordStorage
     */
    private $wordStorage;
    /**
     * @Inject
     * @var WordDao
     */
    private $wordDao;


    /**
     * 增加词库
     * @param string $word
     * @param string $fromSystem
     * @param string $type
     * @return bool
     */
    public function add(string $word, string $fromSystem, string $type): bool
    {
        $where = [];
        $where['word'] = $word;
        $where['from_system'] = $fromSystem;
        $where['type'] = $type;

        $wordModel = $this->wordDao->getOne($where);
        if (!empty($wordModel)) {
            return true;
        }
        $wordModel = new Word();
        //写入db
        $wordModel->word = $word;
        $wordModel->from_system = $fromSystem;
        $wordModel->type = $type;
        return $this->wordDao->save($wordModel);
    }

    /**
     * 删除
     * @param string $word
     * @param string $fromSystem
     * @param string $type
     * @return bool
     */
    public function del(string $word, string $fromSystem, string $type): bool
    {
        $where = [];
        $where['word'] = $word;
        $where['from_system'] = $fromSystem;
        $where['type'] = $type;
        $wordModel = Word::where($where)->first();
        return $this->wordDao->delOne($wordModel);
    }

    public function db2Dic(): bool
    {
        $page = 1;
        $pageNum = 1000;
        while (true) {
            $wordModelList = $this->wordDao->list([], ($page - 1) * $pageNum, $pageNum);
            if (0 == count($wordModelList)) {
                break;
            }
            //循环将词写入dic
            foreach ($wordModelList as $oneWord) {
                $this->wordStorage->add($oneWord['word'], WordType::getType($oneWord['from_system'], $oneWord['type']));
            }
            $page++;
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
        $pageNum = 1000;
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
            $page++;
        }
        //替换词典数据
        foreach ($wordType as $oneWordType) {
            $this->wordStorage->replace($oneWordType, $prefix . $oneWordType);
        }
        return true;
    }
}
