<?php

namespace Ojs\UserBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class SecurityControllerTest extends BaseTestCase
{
    public function testRegister()
    {
        $this->isAccessible(['register']);
    }

    public function testLogin()
    {
        $this->isAccessible(['login']);
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
