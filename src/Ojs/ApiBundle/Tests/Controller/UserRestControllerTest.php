<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class UserRestControllerTest extends BaseTestCase
{
    public function testGetUsersAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_users', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewUserAction()
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
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_users', $routeParameters);
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }


    public function testGetUserAction()
    {
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_get_user', $routeParameters);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
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
        $routeParameters = $this->getRouteParams(['id'=> 550]);
        $url = $this->router->generate('api_1_put_user', $routeParameters);
        $this->client->request(
            'PUT',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPatchUserAction()
    {
        $content = [
            'username'  => $this->generateRandomString(10).'Patch',
            'email'     => $this->generateRandomString(10).'Patch@'.$this->generateRandomString(5).'.com',
        ];
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_patch_user', $routeParameters);
        $this->client->request(
            'PATCH',
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
