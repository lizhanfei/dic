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
        $match = $sentence->match('顺义是北京的一个区域', 'zg', 'main');
        $this->assertArraySubset(['顺义', '北京'], $match, true);

        $match = $sentence->match('顺义是北京的一个地区', 'zg', 'notmain');
        $this->assertCount(0, $match);
        $this->assertEquals([], $match);

        $find = $wordService->find('北',  'zg', 'main');
        $this->assertArraySubset(['北京'], $find);

        $find = $wordService->find('域',  'zg', 'main');
        $this->assertCount(0, $find);
        $this->assertEquals([], $find);

        $find = $wordService->find('区',  'zg', 'main');
        $this->assertArraySubset(['区域'], $find);

    }
    /**
     * @return Container
     */
    protected function getContainerWithtrue()
    {
        $container = Mockery::mock(Container::class);

        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $logerFactory = $this->createMock(\Hyperf\Logger\LoggerFactory::class);
        $logerFactory->method('get')->willReturn($logger);
        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('list')->will($this->returnCallback([$this, 'getWord']));
        $wordDaoStub->method('count')->willReturn(1);

        $wordStorage = new \App\Util\Dic\WordMemory();

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\DicServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\DicServiceImplV1($wordStorage, $wordDaoStub, $logerFactory));

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

    public function getWordAgain($where, $pageStart, $pageNum)
    {
        if ($pageStart > $pageNum) {
            return [];
        }
        $words = [];
        $words[] = ['word'=>'北京', 'from_system'=>'zg', 'type'=>'main'];
        $words[] = ['word'=>'区域', 'from_system'=>'zg', 'type'=>'main'];
        $words[] = ['word'=>'顺义', 'from_system'=>'zg', 'type'=>'main'];
        $words[] = ['word'=>'东北', 'from_system'=>'zg', 'type'=>'main'];
        $words[] = ['word'=>'songhuajiang', 'from_system'=>'zg', 'type'=>'main'];

        return $words;
    }


    public function testDicRelease()
    {
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $logerFactory = $this->createMock(\Hyperf\Logger\LoggerFactory::class);
        $logerFactory->method('get')->willReturn($logger);

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('list')->will($this->returnCallback([$this, 'getWord']));
        $wordDaoStub->method('count')->willReturn(1);

        $wordStorage = new \App\Util\Dic\WordMemory();

        $sentence = new \App\Service\Sentence\SentenceServiceImplV1($wordStorage);
        $wordService = new \App\Service\Word\WordServiceImplV1($wordStorage);
        $dic = new \App\Service\Dic\DicServiceImplV1($wordStorage, $wordDaoStub, $logerFactory);
        //第一次数据录入
        $this->assertEquals(true, $dic->db2Dic());

        $match = $sentence->match('顺义是北京的一个区域', 'zg', 'main');
        $this->assertArraySubset(['顺义', '北京', '区域'], $match);

        $match = $sentence->match('顺义是北京的一个地区', 'zg', 'notmain');
        $this->assertCount(0, $match);
        $this->assertEquals([], $match);

        $find = $wordService->find('北',  'zg', 'main');
        $this->assertArraySubset(['北京'], $find, true);

        $find = $wordService->find('东',  'zg', 'main');
        $this->assertArraySubset($find, [],true);

        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $logerFactory = $this->createMock(\Hyperf\Logger\LoggerFactory::class);
        $logerFactory->method('get')->willReturn($logger);

        //覆盖索引
        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('list')->will($this->returnCallback([$this, 'getWordAgain']));
        $dic = new \App\Service\Dic\DicServiceImplV1($wordStorage, $wordDaoStub, $logerFactory);
        $dic->releaseDb2Dic();

        $match = $sentence->match('顺义是北京的一个地区', 'zg', 'main');
        $this->assertArraySubset(['顺义', '北京'], $match, true);

        $match = $sentence->match('我的家在东北songhuajiang', 'zg', 'main');
        $this->assertArraySubset(['东北', 'songhuajiang'], $match, true);

        $find = $wordService->find('songhua',  'zg', 'main');
        $this->assertArraySubset(['songhuajiang'], $find,true);

        $find = $wordService->find('东',  'zg', 'main');
        $this->assertArraySubset($find, ['东北']);
    }


}