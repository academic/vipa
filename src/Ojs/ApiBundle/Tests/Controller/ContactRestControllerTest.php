<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class ContactRestControllerTest extends BaseTestCase
{
    public function testGetContactsAction()
    {
        $url = $this->router->generate('api_1_get_contact');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewContactAction()
    {
        $content = [
            'title' => '',
            'fullName' => 'PHPUnit Test Full Name Field en - POST',
            'address' => 'PHPUnit Test Address Field en - POST',
            'phone' => 'PHPUnit Test Phone Field en - POST',
            'email' => 'PHPUnit Test Email Field en - POST',
            'tags' => ['phpunit'],
            'contactType' => 4,
            'journal' => 1,
            'country' => 216,
            'city' => 'Ankara'
        ];
        $url = $this->router->generate('api_1_get_contacts');
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

    public function testGetContactAction()
    {
        $url = $this->router->generate('api_1_get_contact', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutContactAction()
    {
        $content = [
            'title' => '',
            'fullName' => 'PHPUnit Test Full Name Field en - PUT',
            'address' => 'PHPUnit Test Address Field en - PUT',
            'phone' => 'PHPUnit Test Phone Field en - PUT',
            'email' => 'PHPUnit Test Email Field en - PUT',
            'tags' => ['phpunit', 'PUT'],
            'contactType' => 4,
            'journal' => 1,
            'country' => 216,
            'city' => 'Ankara'
        ];
        $url = $this->router->generate('api_1_put_contact', ['id' => 550]);
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

    public function testPatchContactAction()
    {
        $content = [
            'address' => 'PHPUnit Test Address Field en - PUT',
            'email' => 'PHPUnit Test Email Field en - PUT',
        ];
        $url = $this->router->generate('api_1_patch_contact', ['id' => 1]);
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

    public function testDeleteContactAction()
    {
        $url = $this->router->generate('api_1_delete_contact', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
