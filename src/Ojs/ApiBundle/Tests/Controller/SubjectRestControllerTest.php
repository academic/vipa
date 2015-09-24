<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\CoreBundle\Tests\BaseTestCase;

class SubjectRestControllerTest extends BaseTestCase
{
    public function testGetSubjectsAction()
    {
        $url = $this->router->generate('api_1_get_subjects');
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNewSubjectAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'subject' => 'PHPUnit Test Subject Field en - POST',
                    'description' => 'PHPUnit Test Description Field en - POST',
                ]
            ],
            'tags' => ['phpunit'],
        ];
        $url = $this->router->generate('api_1_get_subjects');
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

    public function testGetSubjectAction()
    {
        $url = $this->router->generate('api_1_get_subject', ['id'=> 1]);
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPutSubjectAction()
    {
        $content = [
            'translations' => [
                'en' => [
                    'subject' => 'PHPUnit Test Subject Field en - PUT',
                    'description' => 'PHPUnit Test Description Field en - PUT',
                ]
            ],
            'tags' => ['phpunit'],
        ];
        $url = $this->router->generate('api_1_put_subject', ['id' => 550]);
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

    public function testPatchSubjectAction()
    {
        $content = [
            'translations' => [
                'tr' => [
                    'subject' => 'PHPUnit Test Subject Field TR - PATCH',
                    'description' => 'PHPUnit Test Description Field TR - PATCH',
                ]
            ]
        ];
        $url = $this->router->generate('api_1_patch_subject', ['id' => 1]);
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

    public function testDeleteSubjectAction()
    {
        $url = $this->router->generate('api_1_delete_subject', ['id' => 1]);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
