<?php

declare(strict_types=1);

namespace App\Listener;

use App\Service\Dic\DicService;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Framework\Event\AfterWorkerStart;
use Swoole\Timer;
use Hyperf\Contract\ConfigInterface;

class AfterWorkStartListener implements ListenerInterface
{
    /**
     * @var DicService
     */
    private $dicService;
    /**
     * @var ConfigInterface
     */
    private $applicationConfig;

    public function __construct(DicService $dicService, ConfigInterface $applicationConfig)
    {
        $this->dicService = $dicService;
        $this->applicationConfig = $applicationConfig;
    }

    public function listen(): array
    {
        // 返回一个该监听器要监听的事件数组，可以同时监听多个事件
        return [
            AfterWorkerStart::class,
        ];
    }

    /**
     * 监听进程拉起后，将词典从db加载到高速缓存
     * @param object $event
     */
    public function process(object $event)
    {
        if (!$this->ifTaskProcess($event->workerId, $event->server->setting['worker_num'], $event->server->setting['task_worker_num'])) {
            //初始化词典到告诉缓存
            $this->dicService->db2Dic();
        }
        //注册定时器，每隔一段时间更新内存词典数据
        $timeTick = $this->applicationConfig->get("release_dic_time");
        $timeTick = intval($timeTick);
        $timeTick <= 0 && $timeTick = 600000;//默认十分钟
        $timeId = Timer::tick($timeTick, function () use ($event) {
            $this->dicService->releaseDb2Dic();
        });
        return $timeId;
    }

    /**
     * 是否是task进程
     * @param $workIdNow
     * @param $workNum
     * @param $taskWorkNum
     * @return bool
     */
    private function ifTaskProcess($workIdNow, $workNum, $taskWorkNum)
    {
        if ($workIdNow > ($workNum - 1)) {
            return true;
        } else {
            return false;
        }
    }
}