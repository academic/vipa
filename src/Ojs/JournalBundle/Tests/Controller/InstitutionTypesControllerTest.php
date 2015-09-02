<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class PublisherTypesControllerTest extends BaseTestCase
{

    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/publisher_types/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/publisher_types/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
