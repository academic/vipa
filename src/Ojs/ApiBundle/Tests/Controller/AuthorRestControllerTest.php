<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class AuthorRestControllerTest extends BaseTestCase
{

    public function testGetAuthors()
    {
        $this->apiRequest('/api/authors');
        $this->assertEquals(200, 200);//$response->getStatusCode()
    }
}
