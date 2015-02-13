<?php

namespace Ojs\ReportBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class ReportControllerTest extends BaseTestCase
{

    public function testStatus()
    {
        $client = $this->client;
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $client->request('GET', '/manager/reports/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}
