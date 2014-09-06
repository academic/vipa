<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JournalSectionControllerTest extends WebTestCase {
    public function testStatus() {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/journal/section');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
    
}
