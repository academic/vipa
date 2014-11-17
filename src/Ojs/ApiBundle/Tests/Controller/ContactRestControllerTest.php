<?php
/**
 * User: aybarscengaver
 * Date: 17.11.14
 * Time: 10:17
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\ApiBundle\Tests\Controller;


use Ojs\Common\Tests\BaseTestCase;

class ContactRestControllerTest extends BaseTestCase {
    public function testGetContacts()
    {
        $response = $this->apiRequest('/api/contacts');
        $this->assertEquals(200,$response->getStatusCode());
    }
}
