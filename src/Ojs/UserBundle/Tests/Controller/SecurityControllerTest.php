<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;

class SecurityControllerTest extends BaseTestCase
{
    public function testRegister()
    {
        //$this->client->enableProfiler();

        $this->isAccessible(['register']);
        $form = $this->crawler->selectButton('Register')->form();
        $form['ojs_user_register[username]'] = "test-user";
        $form['ojs_user_register[email]'] = "test@user.com";
        $form['ojs_user_register[firstName]'] = "Aybars";
        $form['ojs_user_register[lastName]'] = "Cengaver";
        $form['ojs_user_register[password][first]'] = "cengaver";
        $form['ojs_user_register[password][second]'] = "cengaver";
        $this->client->submit($form);
        $result = $this->client->followRedirect();
        $this->assertEquals(1, $result->filter('html:contains("Success")')->count());
        /** @var MessageDataCollector $mailCollector */
        //  $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');
        //  $this->assertEquals(1,$mailCollector->getMessageCount());
    }

    public function testLogin()
    {
        $this->isAccessible(['login']);
        $form = $this->crawler->selectButton('Sign In')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'admin';
        $result = $this->client->submit($form);
        $this->assertEquals(1, $result->filter('html:contains("/user")')->count());
    }

    public function testUnconfirmedLogin()
    {
        $this->isAccessible(['login']);
        $form = $this->crawler->selectButton('Sign In')->form();
        $form['_username'] = 'test-user';
        $form['_password'] = 'cengaver';
        $this->client->submit($form);
        $result = $this->client->followRedirect();
        $this->assertEquals(1, $result->filter('html:contains("User account is locked.")')->count());
    }

    public function testRegenerateAPI()
    {
        $this->logIn();
        $this->client->request('GET', '/user/apikey/regenerate', [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent());
        $this->assertEquals(true, $content->status);
    }

    public function testCreatePassword()
    {
        $this->logIn();
        $this->isAccessible(['user_create_password']);
        $form = $this->crawler->selectButton('Create')->form();
        $form['ojs_userbundle_createpassword[password][first]'] = 'admin';
        $form['ojs_userbundle_createpassword[password][second]'] = 'admin';
        $this->client->submit($form);
        $result = $this->client->followRedirect();
        $this->assertEquals(1, $result->filter('html:contains("Terms of Service")')->count());
    }
}
