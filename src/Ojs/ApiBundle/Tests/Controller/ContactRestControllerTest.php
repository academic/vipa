<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class ContactRestControllerTest extends BaseTestCase
{
    public function testGetContacts()
    {
        $response = $this->apiRequest('/api/contacts');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
