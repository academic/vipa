<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class TestRestControllerTest extends BaseTestCase
{
    public function testTest()
    {
        $response = $this->apiRequest('/api/test/1');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
