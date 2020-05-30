<?php

declare(strict_types=1);

namespace HyperfTest\Listener;

use App\Listener\AfterWorkStartListener;
use Cassandra\Time;
use Hyperf\Di\Container;
use Hyperf\Framework\Event\AfterWorkerStart;
use HyperfTest\HttpTestCase;
use App\Service\Dic\DicService;
use Hyperf\Contract\ConfigInterface;
use phpDocumentor\Reflection\Types\Object_;
use Swoole\Timer;
use Mockery;

class AfterWorkStartListenerTest extends HttpTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testListen()
    {
        $dicService = $this->createMock(\App\Service\Dic\DicService::class);
        $config = $this->createMock(\Hyperf\Contract\ConfigInterface::class);

        $listener = new AfterWorkStartListener($dicService, $config);

        $this->assertEquals([
            AfterWorkerStart::class,
        ], $listener->listen());
    }

    public function testProcess()
    {
        $dicService = $this->createMock(\App\Service\Dic\DicService::class);
        $dicService->method('db2Dic')->willReturn(true);
        $config = $this->createMock(\Hyperf\Contract\ConfigInterface::class);
        $config->method('get')->willReturn(600000);

        $listener = new AfterWorkStartListener($dicService, $config);
        $timeId = $listener->process($this->getEventObject());
        $this->assertTrue(Timer::exists($timeId));
        Timer::clearAll();
    }

    /**
     * 获取事件对象，模拟当前是1号进程的情况
     * @return mixed
     */
    private function getEventObject()
    {
        $objectArr = [];
        $objectArr['workerId'] = 1;
        $objectArr['server'] = [];
        $objectArr['server']['setting'] = null;

        $result = json_decode(json_encode($objectArr));
        $result->server->setting = ['worker_num' => 2, 'task_worker_num' => 0];

        return $result;
    }
}

