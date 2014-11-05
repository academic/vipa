<?php

namespace Ojs\ManagerBundle\Tests\Controller;

class ManagerControllerTest extends \Ojs\Common\Helper\TestHelper
{
    public function testManagerDashboard()
    {
        $this->logIn('demo_editor', array('ROLE_EDITOR'));
        $this->client->request('GET', '/editor/dashboard');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/editor/myjournals');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
