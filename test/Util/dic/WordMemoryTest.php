<?php

declare(strict_types=1);

namespace HyperfTest\Util\dic;

use HyperfTest\HttpTestCase;
use App\Util\Dic\WordMemory;
use Mockery;

class WordMemoryTest extends HttpTestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testWordMemory()
    {
        $wordType = 'zg_main';
        $wordMemory = new WordMemory();

        $wordMemory->add('中国', $wordType);
        $wordMemory->add('黑龙江', $wordType);
        $wordMemory->add('鸭绿江', $wordType);
        $wordMemory->add('长白山', $wordType);

        $match = $wordMemory->match('黑白江长白山流啊流江里的鸭绿江水暖阿暖', $wordType);
        $this->assertCount(2, $match);
        $resultDci = [];
        foreach($match as $oneMatch) {
            $resultDci[] = $oneMatch['word'];
        }
        $this->assertArraySubset(['长白山', '鸭绿江'], $resultDci);

        $this->assertTrue($wordMemory->del('长白山', 'anotexisttype'));
        $this->assertEquals([], $wordMemory->findWord('长', 'anotexisttype'));

        $wordMemory->del('长白山', $wordType);
        $match = $wordMemory->match('黑白江长白山流啊流江里的鸭绿江水暖阿暖', $wordType);
        $this->assertCount(1, $match);
        $resultDci = [];
        foreach($match as $oneMatch) {
            $resultDci[] = $oneMatch['word'];
        }
        $this->assertArraySubset(['鸭绿江'], $resultDci);
        $match = $wordMemory->match('中国黑白江长白山流啊流江里的鸭绿江水暖阿暖', $wordType);
        $this->assertCount(2, $match);
        $resultDci = [];
        foreach($match as $oneMatch) {
            $resultDci[] = $oneMatch['word'];
        }
        $this->assertArraySubset(['中国', '鸭绿江'], $resultDci);

        $wordMemory->clear($wordType);
        $match = $wordMemory->match('黑白江长白山流啊流江里的鸭绿江水暖阿暖', $wordType);
        $this->assertCount(0, $match);


    }

    public function testreplace()
    {
        $wordType = 'zg_main';
        $wordTypeNew = 'zg_main_1';
        $wordMemory = new WordMemory();

        $wordMemory->add('中国', $wordType);
        $wordMemory->add('黑龙江', $wordType);
        $wordMemory->add('鸭绿江', $wordType);
        $wordMemory->add('长白山', $wordType);

        $wordMemory->add('高铁', $wordTypeNew);
        $wordMemory->add('发达', $wordTypeNew);
        $wordMemory->add('速度还不错', $wordTypeNew);

        $match = $wordMemory->match('黑白江长白山流啊流江里的鸭绿江水暖阿暖', $wordType);
        $this->assertCount(2, $match);
        $resultDci = [];
        foreach($match as $oneMatch) {
            $resultDci[] = $oneMatch['word'];
        }
        $this->assertArraySubset(['长白山', '鸭绿江'], $resultDci);

        $find = $wordMemory->findWord('发', $wordType);
        $this->assertCount(0, $find);
        $find = $wordMemory->findWord('发', $wordTypeNew);
        $this->assertCount(1, $find);

        $match = $wordMemory->match('高铁速度还不错，挺发达的', $wordType);
        $this->assertCount(0, $match);

        $match = $wordMemory->match('高铁速度还不错，挺发达的', $wordTypeNew);
        $this->assertCount(3, $match);

        $wordMemory->replace($wordType, $wordTypeNew);
        $match = $wordMemory->match('高铁速度还不错，挺发达的', $wordType);
        $this->assertCount(3, $match);
        $find = $wordMemory->findWord('发', $wordType);
        $this->assertCount(1, $find);

        $wordMemory->clear($wordType);
        $find = $wordMemory->findWord('发', $wordType);
        $this->assertCount(0, $find);
        $find = $wordMemory->findWord('发', $wordTypeNew);
        $this->assertCount(1, $find);

    }
}

