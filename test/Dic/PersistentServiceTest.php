<?php

declare(strict_types=1);

namespace HyperfTest\Dic;

use HyperfTest\HttpTestCase;
use Hyperf\Di\Container;
use Hyperf\Utils\ApplicationContext;
use Mockery;
use App\Model\Word;

class PersistentServiceTest extends HttpTestCase
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
        $container = Mockery::mock(Container::class);

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(null);
        $wordDaoStub->method('save')->willReturn(true);

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\PersistentServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\PersistentServiceImplV1($wordDaoStub));

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
        $container = Mockery::mock(Container::class);

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(null);
        $wordDaoStub->method('save')->willReturn(false);

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\PersistentServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\PersistentServiceImplV1($wordDaoStub));
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
        $container = Mockery::mock(Container::class);

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(new Word());
        $wordDaoStub->method('save')->willReturn(false);

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\PersistentServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\PersistentServiceImplV1($wordDaoStub));
        return $container;
    }

    public function testSave4()
    {
        $res = $this->getContainerWithsaveModel()->get(\App\Service\Dic\PersistentServiceImplV1::class)->add('中国', 'shop', 'main');

        $this->assertEquals(true, $res);
    }

    public function ifWord($param)
    {
        if ($param instanceof Word) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return Container
     */
    protected function getContainerWithsaveModel()
    {
        $container = Mockery::mock(Container::class);

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(null);
        $wordDaoStub->method('save')->will($this->returnCallback([$this, 'ifWord']));

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\PersistentServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\PersistentServiceImplV1($wordDaoStub));
        return $container;
    }


    public function testdel1()
    {
        $res = $this->getContainerWithsaveModel()->get(\App\Service\Dic\PersistentServiceImplV1::class)->del('中国', 'shop', 'main');

        $this->assertEquals(true, $res);
    }

    /**
     * @return Container
     */
    protected function getContainerWithdelModel()
    {
        $container = Mockery::mock(Container::class);

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(null);
        $wordDaoStub->method('del')->will($this->returnCallback([$this, 'ifWord']));

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\PersistentServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\PersistentServiceImplV1($wordDaoStub));
        return $container;
    }

    public function testdel2()
    {
        $res = $this->getContainerWithdelModelfasle()->get(\App\Service\Dic\PersistentServiceImplV1::class)->del('中国', 'shop', 'main');

        $this->assertEquals(false, $res);
    }

    /**
     * @return Container
     */
    protected function getContainerWithdelModelfasle()
    {
        $container = Mockery::mock(Container::class);

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(new Word());
        $wordDaoStub->method('delOne')->willReturn(false);

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\PersistentServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\PersistentServiceImplV1($wordDaoStub));
        return $container;
    }

    public function testdel3()
    {
        $res = $this->getContainerWithdelModeltrue()->get(\App\Service\Dic\PersistentServiceImplV1::class)->del('中国', 'shop', 'main');

        $this->assertEquals(true, $res);
    }

    /**
     * @return Container
     */
    protected function getContainerWithdelModeltrue()
    {
        $container = Mockery::mock(Container::class);

        $wordDaoStub = $this->createMock(\App\Dao\Word\WordDao::class);
        $wordDaoStub->method('getOne')->willReturn(new Word());
        $wordDaoStub->method('delOne')->willReturn(true);

        $container->shouldReceive('get')
            ->with(\App\Service\Dic\PersistentServiceImplV1::class)
            ->andReturn(new \App\Service\Dic\PersistentServiceImplV1($wordDaoStub));
        return $container;
    }
}
