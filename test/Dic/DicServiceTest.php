<?php

declare(strict_types=1);

namespace HyperfTest\Dic;

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
        $res = $this->getContainerWithSaveTrue()->get(\App\Service\Dic\PersistentServiceImplV1::class)->add('中国', 'shop', 'main');

        $this->assertEquals(true, $res);
    }
    /**
     * @return Container
     */
    protected function getContainerWithSaveTrue()
    {
        $container = ApplicationContext::getContainer();
        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(null);
        $wordDaoStub->method('save')->willReturn(true);
        $container->getDefinitionSource()->addDefinition(\App\Dao\Word\WordDao::class, function () use ($wordDaoStub) {
            return $wordDaoStub;
        });
        $dao = $container->get(\App\Dao\Word\WordDao::class)->getOne([]);
        var_dump($dao);
        var_dump("success  ");
        return $container;
    }

    public function testSave2()
    {
        $res = $this->getContainerWithSaveFalse()->get(\App\Service\Dic\PersistentServiceImplV1::class)->add('中国', 'shop', 'main');

        $this->assertEquals(false, $res);
    }
    /**
     * @return Container
     */
    protected function getContainerWithSaveFalse()
    {
        $container = ApplicationContext::getContainer();
        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(null);
        $wordDaoStub->method('save')->willReturn(false);
        $container->getDefinitionSource()->addDefinition(\App\Dao\Word\WordDao::class, function () use ($wordDaoStub) {
            return $wordDaoStub;
        });
        return $container;
    }

    public function testSave3()
    {
        $res = $this->getContainerWithgetOne()->get(\App\Service\Dic\PersistentServiceImplV1::class)->add('中国', 'shop', 'main');

        $this->assertEquals(true, $res);
    }
    /**
     * @return Container
     */
    protected function getContainerWithgetOne()
    {
        $container = ApplicationContext::getContainer();
        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(new Word());
        $wordDaoStub->method('save')->willReturn(false);
        $container->getDefinitionSource()->addDefinition(\App\Dao\Word\WordDao::class, function () use ($wordDaoStub) {
            return $wordDaoStub;
        });
        return $container;
    }
}
