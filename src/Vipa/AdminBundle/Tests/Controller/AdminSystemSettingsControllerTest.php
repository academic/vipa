<?php

namespace Vipa\AdminBundle\Tests\Controller;

use Vipa\CoreBundle\Tests\BaseTestSetup as BaseTestCase;

class AdminSystemSettingsControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/settings/');

        $this->assertStatusCode(200, $client);

        $form = $crawler->filter('form[name=system_settings]')->form();
        $form['system_settings[userRegistrationActive]'] = '1';
        $form['system_settings[journalApplicationActive]'] = '1';
        $form['system_settings[publisherApplicationActive]'] = '1';
        $form['system_settings[articleSubmissionActive]'] = '1';

        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('.alert-success')->count());


    }
}