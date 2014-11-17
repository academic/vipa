<?php
/**
 * User: aybarscengaver
 * Date: 17.11.14
 * Time: 10:20
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\ApiBundle\Tests\Controller;


use Ojs\Common\Tests\BaseTestCase;

class JournalRestControllerTest extends BaseTestCase
{
    public function testJournal()
    {
        $response = $this->apiRequest('/api/journal/1');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testJournalUsers()
    {
        $response = $this->apiRequest('/api/journal/1/users','GET',['page'=>1,'limit'=>1]);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
