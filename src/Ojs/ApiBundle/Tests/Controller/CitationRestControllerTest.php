<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class CitationRestControllerTest extends BaseTestCase
{

    public function testGetCitation()
    {
        $response = $this->apiRequest('/api/journal/1/citations');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteCitation()
    {
        $response = $this->apiRequest('/api/citations/1', 'DELETE');
        $this->assertEquals(204, $response->getStatusCode());
    }
}
