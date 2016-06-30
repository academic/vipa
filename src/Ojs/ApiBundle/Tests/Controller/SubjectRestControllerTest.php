<?php

namespace Ojs\ApiBundle\Tests\Controller;

use Ojs\ApiBundle\Tests\ApiBaseTestCase;

class SubjectRestControllerTest extends ApiBaseTestCase
{
    public function testGetSubjectsAction()
    {
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_subjects', $routeParameters);
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
        $routeParameters = $this->getRouteParams();
        $url = $this->router->generate('api_1_get_subjects', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_get_subject', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['id'=> 550]);
        $url = $this->router->generate('api_1_put_subject', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_patch_subject', $routeParameters);
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
        $routeParameters = $this->getRouteParams(['id'=> 1]);
        $url = $this->router->generate('api_1_delete_subject', $routeParameters);
        $this->client->request(
            'DELETE',
            $url
        );
        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }
}
