<?php

namespace Ojs\SiteBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class UserControllerTest extends BaseTestCase
{
    const PROFILE = 'ojs_site_people_index';
    const EDIT_PROFILE = 'ojs_user_edit_profile';
    const CUSTOM_FIELD = 'ojs_user_custom_field';
    const CUSTOM_FIELD_CREATE = 'ojs_user_custom_field_create';
    const CUSTOM_FIELD_DELETE = 'ojs_user_custom_field_delete';
    const CONNECTED_ACCOUNT = 'ojs_user_connected_account';
    const CONNECTED_ACCOUNT_ADD = 'ojs_user_connected_account_add';

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
        $form['ojs_userbundle_updateuser[email]'] = 'root@localhost.com';
        $crawler = $this->client->submit($form);

        $usernameVal = $crawler->filter('#ojs_userbundle_updateuser_username')->attr('value');
        $emailVal = $crawler->filter('#ojs_userbundle_updateuser_email')->attr('value');
        $this->assertEquals('admin2',$usernameVal);
        $this->assertEquals('root@localhost.com',$emailVal);

    }

    public function testCustomField()
    {
        $this->logIn();
        $url                = $this->router->generate(self::CUSTOM_FIELD);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateCustomField()
    {
        $this->logIn();
        $url                = $this->router->generate(self::CUSTOM_FIELD_CREATE);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $form = $crawler->filter('form[name=ojs_userbundle_customfieldtype]')->form();
        $form['ojs_userbundle_customfieldtype[label]'] = 'labeltry';
        $form['ojs_userbundle_customfieldtype[value]'] = 'valuetry';
        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        $this->assertContains(
            'labeltry',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDeleteCustomField()
    {
        $this->logIn();
        $url                = $this->router->generate(self::CUSTOM_FIELD_DELETE,['id' => 3]);
        $crawler            = $this->client->request('GET', $url);
        $response           = $this->client->getResponse();

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testConnectedAccount()
    {
        $this->logIn();
        $url                = $this->router->generate(self::CONNECTED_ACCOUNT);
        $crawler            = $this->client->request('GET',$url);
        $response            = $this->client->getResponse();

        $this->assertEquals(200,$response->getStatusCode());
    }

}
