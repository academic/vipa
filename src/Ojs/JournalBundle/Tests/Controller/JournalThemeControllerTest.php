<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JournalThemeControllerTest extends \Ojs\Common\Helper\TestHelper
{

    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/journaltheme/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
