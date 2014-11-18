<?php
/**
 * User: aybarscengaver
 * Date: 17.11.14
 * Time: 10:56
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\ApiBundle\Tests\Controller;


use Ojs\Common\Tests\BaseTestCase;

class UserRestControllerTest extends BaseTestCase
{
    private $username = 'admin';

    public function testGetUser()
    {
        $response = $this->apiRequest('/api/user/' . $this->username);
        $this->assertEquals(200, $response->getStatusCode());
        $user = json_decode($response->getContent());
        $this->assertContains('admin', $user->username);
    }

    public function testGetUserJournal()
    {
        $response = $this->apiRequest('/api/user/' . $this->username . '/journals');
        $this->assertEquals(204, $response->getStatusCode());
    }


    public function testGetUserRole()
    {
        $response = $this->apiRequest('/api/user/' . $this->username . '/roles');
        $this->assertEquals(204, $response->getStatusCode());
    }


    public function testGetUsers()
    {
        $response = $this->apiRequest('/api/users');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteUser()
    {
        $response = $this->apiRequest('/api/users/5', 'DELETE');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testPutUser()
    {
        $this->assertEquals(true, true);
    }

    public function testActiveUser()
    {
        $response = $this->apiRequest('/api/users/5/active', 'PATCH', ['isActive' => 1]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStatusUser()
    {
        $response = $this->apiRequest('/api/users/5/status', 'PATCH', ['status' => 1]);
        $this->assertEquals(200, $response->getStatusCode());
    }

}

