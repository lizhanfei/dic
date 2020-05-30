<?php

declare(strict_types=1);

namespace HyperfTest\Controller;

use Hyperf\Di\Container;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use HyperfTest\HttpTestCase;
use Mockery;
use App\Controller\DicController;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface;

class DicControllerTest extends HttpTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testAddWord1()
    {
        $container = Mockery::mock(Container::class);

        $persistentServiceStub = $this->createMock(\App\Service\Dic\PersistentService::class);
        $persistentServiceStub->method('add')->willReturn(true);

        $request = $this->createMock(\Hyperf\HttpServer\Contract\RequestInterface::class);
        $request->method('input')->willReturn('用户输入');

        $response = $this->createMock(\Hyperf\HttpServer\Contract\ResponseInterface::class);
        $response->method('json')->will($this->returnCallback([$this, 'responseReturn']));

        //测试保存成功
        $dicController = new DicController($persistentServiceStub, $container, $request, $response);
        $result = $dicController->addWord();
        $this->assertEquals('{"code":200,"msg":"\u4fdd\u5b58\u6210\u529f","data":[]}', $result->getBody());

        //测试保存失败
        $persistentServiceStub = $this->createMock(\App\Service\Dic\PersistentService::class);
        $persistentServiceStub->method('add')->willReturn(false);

        $dicController = new DicController($persistentServiceStub, $container, $request, $response);
        $result = $dicController->addWord();
        $this->assertEquals('{"code":500,"msg":"\u4fdd\u5b58\u8bcd\u8bed\u5931\u8d25","data":[]}', $result->getBody());

        //测试参数错误
        $request = $this->createMock(\Hyperf\HttpServer\Contract\RequestInterface::class);
        $request->method('input')->willReturn('');
        $dicController = new DicController($persistentServiceStub, $container, $request, $response);
        $result = $dicController->addWord();
        $this->assertEquals('{"code":500,"msg":"\u53c2\u6570\u9519\u8bef","data":[]}', $result->getBody());
    }

    public function testRemove()
    {
        $container = Mockery::mock(Container::class);

        $persistentServiceStub = $this->createMock(\App\Service\Dic\PersistentService::class);
        $persistentServiceStub->method('del')->willReturn(true);

        $request = $this->createMock(\Hyperf\HttpServer\Contract\RequestInterface::class);
        $request->method('input')->willReturn('用户输入');

        $response = $this->createMock(\Hyperf\HttpServer\Contract\ResponseInterface::class);
        $response->method('json')->will($this->returnCallback([$this, 'responseReturn']));

        //测试移除成功
        $dicController = new DicController($persistentServiceStub, $container, $request, $response);
        $result = $dicController->removeWord();
        $this->assertEquals('{"code":200,"msg":"\u79fb\u9664\u6210\u529f","data":[]}', $result->getBody());

        //测试移除失败
        $persistentServiceStub = $this->createMock(\App\Service\Dic\PersistentService::class);
        $persistentServiceStub->method('del')->willReturn(false);

        $dicController = new DicController($persistentServiceStub, $container, $request, $response);
        $result = $dicController->removeWord();
        $this->assertEquals('{"code":500,"msg":"\u79fb\u9664\u8bcd\u8bed\u5931\u8d25","data":[]}', $result->getBody());

        //测试参数错误
        $request = $this->createMock(\Hyperf\HttpServer\Contract\RequestInterface::class);
        $request->method('input')->willReturn('');
        $dicController = new DicController($persistentServiceStub, $container, $request, $response);
        $result = $dicController->removeWord();
        $this->assertEquals('{"code":500,"msg":"\u53c2\u6570\u9519\u8bef","data":[]}', $result->getBody());

    }


    public function responseReturn($param)
    {
        $response = new Response();
        $response->withBody(new SwooleStream(json_encode($param)));
        return $response;
    }
}