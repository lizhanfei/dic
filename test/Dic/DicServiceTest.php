<?php

declare(strict_types=1);

namespace HyperfTest\Dic;

use HyperfTest\HttpTestCase;
use Hyperf\Di\Container;
use Mockery;

class DicServiceTest extends HttpTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testdb2Dic1()
    {
        $db2Dic = $this->getContainerWithtrue()->get(\App\Service\Dic\DicServiceImplV1::class)->db2Dic();
        $sentence = $this->getContainerWithtrue()->get(\App\Service\Sentence\SentenceServiceImplV1::class);
        $wordService = $this->getContainerWithtrue()->get(\App\Service\Word\WordServiceImplV1::class);

        $this->assertEquals(true, $db2Dic);
        $match = $sentence->match('顺义是北京的一个地区', 'zg', 'main');
        $this->assertArraySubset($match, ['顺义', '北京', '区域']);

        $match = $sentence->match('顺义是北京的一个地区', 'zg', 'notmain');
        $this->assertCount(0, $match);
        $this->assertEquals([], $match);

        $find = $wordService->find('北',  'zg', 'main');
        $this->assertArraySubset($find, ['北京']);

        $find = $wordService->find('域',  'zg', 'main');
        $this->assertCount(0, $find);
        $this->assertEquals([], $find);

        $find = $wordService->find('区',  'zg', 'main');
        $this->assertArraySubset($find, ['区域']);

    }
    /**
     * @return Container
     */
    protected function getContainerWithtrue()
    {
        $container = Mockery::mock(Container::class);

        $words = [];
        $words[] = ['word'=>'北京', 'from_system'=>'zg', 'type'=>'main'];
        $words[] = ['word'=>'区域', 'from_system'=>'zg', 'type'=>'main'];
        $words[] = ['word'=>'顺义', 'from_system'=>'zg', 'type'=>'main'];

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('list')->will($this->returnCallback([$this, 'getWord']));
        $wordStorage = new \App\Util\Dic\WordMemory();

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\DicServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\DicServiceImplV1($wordStorage, $wordDaoStub));

        $container->shouldReceive('get')
            ->with(\App\Service\Sentence\SentenceServiceImplV1::class)
            ->andReturn(new \App\Service\Sentence\SentenceServiceImplV1($wordStorage));

        $container->shouldReceive('get')
            ->with(\App\Service\Word\WordServiceImplV1::class)
            ->andReturn(new \App\Service\Word\WordServiceImplV1($wordStorage));


        return $container;
    }

    public function getWord($where, $pageStart, $pageNum)
    {
        if ($pageStart > $pageNum) {
            return [];
        }
        $words = [];
        $words[] = ['word'=>'北京', 'from_system'=>'zg', 'type'=>'main'];
        $words[] = ['word'=>'区域', 'from_system'=>'zg', 'type'=>'main'];
        $words[] = ['word'=>'顺义', 'from_system'=>'zg', 'type'=>'main'];
        return $words;
    }
}