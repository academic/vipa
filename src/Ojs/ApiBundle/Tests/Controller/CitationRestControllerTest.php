<?php
/**
 * Date: 16.11.14
 * Time: 23:53
 * Devs: [
 *   ]
 */

namespace Ojs\ApiBundle\Tests\Controller;


use Ojs\Common\Tests\BaseTestCase;

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
