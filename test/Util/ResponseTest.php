<?php

declare(strict_types=1);

namespace HyperfTest\Util;

use HyperfTest\HttpTestCase;
use App\Util\Response;

class ResponseTest extends HttpTestCase
{
    public function testResponse()
    {
        $result = Response::arr(200, 'success', []);

        $this->assertCount(3, $result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('msg', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(200, $result['code']);
        $this->assertEquals('success', $result['msg']);
        $this->assertEquals([], $result['data']);
    }
}