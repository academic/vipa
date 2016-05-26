<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class PeopleControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $url = $this->router->generate('ojs_site_people_index');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
    
}
