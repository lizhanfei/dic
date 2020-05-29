<?php

declare(strict_types=1);

namespace HyperfTest\Dic;

use App\Dao\Word\WordDao;
use HyperfTest\HttpTestCase;
use Hyperf\Di\Container;
use Hyperf\Utils\ApplicationContext;
use Mockery;
use App\Model\Word;

class DicServiceTest extends HttpTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testSave1()
    {
        $res = $this->getContainerWithSaveTrue()->get(\App\Service\Dic\DicServiceImplV1::class)->add('中国', 'shop', 'main');

        $this->assertEquals(true, $res);
    }
    /**
     * @return Container
     */
    protected function getContainerWithSaveTrue()
    {
        $container = ApplicationContext::getContainer();
        $wordDaoStub = $this->createMock(WordDao::class);
        $wordDaoStub->method('save')->willReturn(true);
        $container->getDefinitionSource()->addDefinition(\App\Dao\Word\WordDao::class, function () use ($wordDaoStub) {
            return $wordDaoStub;
        });
        return $container;
    }

    public function testSave2()
    {
        $res = $this->getContainerWithSaveFalse()->get(\App\Service\Dic\DicServiceImplV1::class)->add('中国', 'shop', 'main');

        $this->assertEquals(false, $res);
    }
    /**
     * @return Container
     */
    protected function getContainerWithSaveFalse()
    {
        $container = ApplicationContext::getContainer();
        $wordDaoStub = $this->createMock(WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(null);
        $wordDaoStub->method('save')->willReturn(false);
        $container->getDefinitionSource()->addDefinition(\App\Dao\Word\WordDao::class, function () use ($wordDaoStub) {
            return $wordDaoStub;
        });
        return $container;
    }

    public function testSave3()
    {
        $res = $this->getContainerWithgetOne()->get(\App\Service\Dic\DicServiceImplV1::class)->add('中国', 'shop', 'main');

        $this->assertEquals(true, $res);
    }
    /**
     * @return Container
     */
    protected function getContainerWithgetOne()
    {
        $container = ApplicationContext::getContainer();
        $wordDaoStub = $this->createMock(WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(new Word());
        $wordDaoStub->method('save')->willReturn(false);
        $container->getDefinitionSource()->addDefinition(\App\Dao\Word\WordDao::class, function () use ($wordDaoStub) {
            return $wordDaoStub;
        });
        return $container;
    }
}
