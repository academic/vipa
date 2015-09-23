<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class InstitutionRestControllerTest extends BaseTestCase
{
    public function testGetInstitutionsAction()
    {
        $url = $this->router->generate('api_1_get_institutions');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewInstitutionAction()
    {
        $content = [
            'name' => 'PHPUnit Test Name Field - POST',
            'translations' => [
                'en' => [
                    'about' => 'PHPUnit Test About Field en - POST',
                ]
            ],
            'tags' => ['phpunit'],
            'institutionType' => 3,
            'address' => 'PHPUnit Test Adress Field - POST',
            'phone' => '12345678910',
            'fax' => '987654321',
            'email' => 'behram.celen@okulbilisim.com',
            'wiki' => 'http://www.wiki.com',
            'domain' => 'behram.org'

        ];
        $url = $this->router->generate('api_1_get_institutions');
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

    public function testGetInstitutionAction()
    {
        $url = $this->router->generate('api_1_get_institution', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutInstitutionAction()
    {
        $content = [
            'name' => 'PHPUnit Test Name Field - PUT',
            'translations' => [
                'en' => [
                    'about' => 'PHPUnit Test About Field en - PUT',
                ]
            ],
            'tags' => ['phpunit'],
            'institutionType' => 3,
            'address' => 'PHPUnit Test Adress Field - PUT',
            'phone' => '12345678910',
            'fax' => '987654321',
            'email' => 'behram.celen@okulbilisim.com',
            'wiki' => 'http://www.wiki.com',
            'domain' => 'behram.org'

        ];
        $url = $this->router->generate('api_1_put_institution', ['id' => 550]);
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

    public function testPatchInstitutionAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'about' => 'PHPUnit Test About Field TR - PATCH',
                ]
            ]
        ];
        $url = $this->router->generate('api_1_patch_institution', ['id' => 1]);
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

    public function testDeleteInstitutionAction()
    {
        $url = $this->router->generate('api_1_delete_institution', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
