<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class UserRestControllerTest extends ApiBaseTestCase
{
    public function testNewUserAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/users/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetUsersAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/users?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostUserAction()
    {
        $content = [
            'username'  => $this->generateRandomString(10),
            'email'     => $this->generateRandomString(10).'@'.$this->generateRandomString(5).'.com',
            'about'     => $this->generateRandomString(150),
            'title'     => 1,
            'firstName' => $this->generateRandomString(15),
            'lastName'  => $this->generateRandomString(18),
            'enabled'   => 1,
            'subjects'  => [2],
            'tags'      => ['PHPUnit'],
            'avatar'    => '',
            'country'   => 225,
        ];
        $this->client->request(
            'POST',
            '/api/v1/users?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }


    public function testGetUserAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/users/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutUserAction()
    {
        $content = [
            'username'  => $this->generateRandomString(10),
            'email'     => $this->generateRandomString(10).'@'.$this->generateRandomString(5).'.com',
            'about'     => $this->generateRandomString(150),
            'title'     => 1,
            'firstName' => $this->generateRandomString(15),
            'lastName'  => $this->generateRandomString(18),
            'enabled'   => 1,
            'subjects'  => [2],
            'tags'      => ['PHPUnitPut'],
            'avatar'    => '',
            'country'   => 225,
        ];
        $this->client->request(
            'PUT',
            '/api/v1/users/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchUserAction()
    {
        $content = [
            'username'  => $this->generateRandomString(10).'Patch',
            'email'     => $this->generateRandomString(10).'Patch@'.$this->generateRandomString(5).'.com',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/users/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }
}
