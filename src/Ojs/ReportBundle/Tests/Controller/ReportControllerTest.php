<?php

namespace Ojs\ReportBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReportControllerTest extends \Ojs\Common\Helper\TestHelper
{

    public function testStatus()
    {
        $client = $this->client;
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $client->request('GET', '/report');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}
