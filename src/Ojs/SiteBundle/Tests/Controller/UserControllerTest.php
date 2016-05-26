<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class UserControllerTest extends BaseTestCase
{
    const PROFILE = 'ojs_site_people_index';
    const EDIT_PROFILE = 'ojs_user_edit_profile';

    public function testProfile()
    {
        $routeParameters    = ['slug' => 'admin'];
        $url                = $this->router->generate(self::PROFILE,$routeParameters);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('admin', $response->getContent());
    }

    public function testEditProfile()
    {
        $this->login();
        $url        = $this->router->generate(self::EDIT_PROFILE);
        $crawler    = $this->client->request('GET', $url);
        $response   = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $form = $crawler->filter('form[name=ojs_userbundle_updateuser]')->form();
        $form['ojs_userbundle_updateuser[username]'] = 'admin2';
        $form['ojs_userbundle_updateuser[firstName]'] = 'admin2';
        $result = $this->client->submit($form);

        $firstNameVal = $result->filter('#ojs_userbundle_updateuser_firstName')->attr('value');
        $this->assertEquals('admin2',$firstNameVal);

    }
}
