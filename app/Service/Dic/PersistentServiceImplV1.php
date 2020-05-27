<?php

declare(strict_types=1);

namespace App\Service\Dic;

use App\Model\Word;
use Hyperf\Di\Annotation\Inject;
use App\Dao\Word\WordDao;
use Hyperf\RpcServer\Annotation\RpcService;

/**
 * Class PersistentServiceImplV1
 * @package App\Service\Dic
 * @RpcService(name="PersistentService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class PersistentServiceImplV1 implements PersistentService
{
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
}
