<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class InstitutionRestControllerTest extends ApiBaseTestCase
{
    public function testNewInstitutionAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/institutions/new?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testGetInstitutionsAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/institutions?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPostInstitutionAction()
    {
        $content = [
            'name' => 'PHPUnit Test Name Field - POST'
        ];
        $this->client->request(
            'POST',
            '/api/v1/institutions?apikey='.$this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testGetInstitutionAction()
    {
        $client = $this->client;
        $client->request('GET', '/api/v1/institutions/1?apikey='.$this->apikey);

        $this->assertStatusCode(200, $client);
    }

    public function testPutInstitutionAction()
    {
        $content = [
            'name' => 'PHPUnit Test Name Field - PUT'
        ];
        $this->client->request(
            'PUT',
            '/api/v1/institutions/550?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(201, $this->client);
    }

    public function testPatchInstitutionAction()
    {
        $content = [
            'name' => 'PHPUnit Test Name Field - PATCH',
        ];
        $this->client->request(
            'PATCH',
            '/api/v1/institutions/1?apikey='. $this->apikey,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content)
        );
        $this->assertStatusCode(204, $this->client);
    }

    public function testDeleteInstitutionAction()
    {
        $announcementId = $this->sampleObjectLoader->loadInstitution();
        $this->client->request(
            'DELETE',
            '/api/v1/institutions/'.$announcementId.'?apikey='. $this->apikey
        );
        $this->assertStatusCode(204, $this->client);
    }
}
