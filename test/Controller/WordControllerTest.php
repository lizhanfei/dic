<?php

declare(strict_types=1);

namespace HyperfTest\Controller;

use App\Controller\WordController;
use Hyperf\Di\Container;
use Hyperf\HttpMessage\Stream\SwooleStream;
use HyperfTest\HttpTestCase;
use Mockery;
use App\Service\Word\WordService;

class WordControllerTest extends HttpTestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testFind()
    {
        $container = Mockery::mock(Container::class);

        $request = $this->createMock(\Hyperf\HttpServer\Contract\RequestInterface::class);
        $request->method('input')->willReturn('用户输入');

        $response = $this->createMock(\Hyperf\HttpServer\Contract\ResponseInterface::class);
        $response->method('json')->will($this->returnCallback([$this, 'responseReturn']));

        $wordServiceStub = $this->createMock(WordService::class);
        $wordServiceStub->method('find')->willReturn(['jieguo'=>'yes']);

        //测试保存成功
        $wordController = new WordController($wordServiceStub, $container, $request, $response);
        $result = $wordController->findWord();
        $this->assertEquals('{"code":200,"msg":"\u6210\u529f","data":{"jieguo":"yes"}}', $result->getBody());

        //测试find为空
        $wordServiceStub = $this->createMock(WordService::class);
        $wordServiceStub->method('find')->willReturn([]);
        $wordController = new WordController($wordServiceStub, $container, $request, $response);
        $result = $wordController->findWord();
        $this->assertEquals('{"code":200,"msg":"\u6210\u529f","data":[]}', $result->getBody());

        //测试参数错误
        $request = $this->createMock(\Hyperf\HttpServer\Contract\RequestInterface::class);
        $request->method('input')->willReturn('');

        $wordServiceStub = $this->createMock(WordService::class);
        $wordServiceStub->method('find')->willReturn([]);
        $wordController = new WordController($wordServiceStub, $container, $request, $response);
        $result = $wordController->findWord();
        $this->assertEquals('{"code":500,"msg":"\u53c2\u6570\u9519\u8bef","data":[]}', $result->getBody());
    }

    public function responseReturn($param)
    {
        $response = new Response();
        $response->withBody(new SwooleStream(json_encode($param)));
        return $response;
    }
}