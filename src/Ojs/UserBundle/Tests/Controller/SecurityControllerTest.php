<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class SecurityControllerTest extends BaseTestCase
{


    public function testRegister()
    {

        $this->client->request('GET', '/register');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testRegenerateAPI(){
        $this->logIn();
        $this->client->request('GET','/user/apikey/regenerate',[],[],['HTTP_ACCEPT'=>'application/json']);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent());
        $this->assertEquals(true,$content->status);
    }
}
