<?php
/**
 * User: aybarscengaver
 * Date: 17.11.14
 * Time: 10:55
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

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
