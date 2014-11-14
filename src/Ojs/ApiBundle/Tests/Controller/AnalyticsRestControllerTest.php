<?php
/** 
 * User: aybarscengaver
 * Date: 12.11.14
 * Time: 18:30
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 * ]
 * \note "test not work correctly for commands"
 */

namespace Ojs\AnalyticsBundle\Tests\Controller;


use Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\UpdateCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpKernel\Client;

/**
 * Class AnalyticsRestControllerTest
 * @package Ojs\AnalyticsBundle\Tests\Controller
 */
class AnalyticsRestControllerTest extends WebTestCase {
    private $objectId=1;
    private $entity='articles';

    /** @var  Client $client */
    private $client;

    /** @var  Application $application */
    private $application;

    public function setUp()
    {
        $this->client = static::createClient();

        $application = new Application($this->client->getKernel());
        $application->setAutoExit(false);

        $this->application = $application;
    }
    public function testPutObjectView()
    {
        $this->client->request(
            'PUT',
            '/api/analytics/view/'.$this->entity.'/'.$this->objectId.'/?apikey=MWFlZDFlMTUwYzRiNmI2NDU3NzNkZDA2MzEyNzJkNTE5NmJmZjkyZQ==',
            [
                'page_url'=>'/articles/test'
            ],
            [],
            ['HTTP_ACCEPT'=>'application/json']
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200,$response->getStatusCode(),$response->getContent());
    }

    public function testGetObjectView()
    {

        $this->client->request(
            'GET',
            '/api/analytics/view/'.$this->entity.'/'.$this->objectId.'/',
            [
                'apikey'=>'MWFlZDFlMTUwYzRiNmI2NDU3NzNkZDA2MzEyNzJkNTE5NmJmZjkyZQ==',
                'page_url'=>'/articles/test'
            ],
            [],
            ['HTTP_ACCEPT'=>'application/json']
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200,$response->getStatusCode(),$response->getContent());

    }

    public function testPutObjectDownload()
    {
        $this->client->request(
            'PUT',
            '/api/analytics/download/'.$this->entity.'/'.$this->objectId.'/?apikey=MWFlZDFlMTUwYzRiNmI2NDU3NzNkZDA2MzEyNzJkNTE5NmJmZjkyZQ==',
            [
                'file_path'=>'/var/tests.tar.gz',
                'file_size'=>'1024kb'
            ],
            [],
            ['HTTP_ACCEPT'=>'application/json']
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200,$response->getStatusCode(),$response->getContent());
    }

    /*
    public function testGetObjectDownload()
    {
        $this->client->request(
            'GET',
            '/api/'.$this->entity.'/'.$this->objectId.'/analytics/download/total',
            [
                'apikey'=>'MWFlZDFlMTUwYzRiNmI2NDU3NzNkZDA2MzEyNzJkNTE5NmJmZjkyZQ==',
                'file_path'=>'/var/tests.tar.gz'
            ],
            [],
            ['HTTP_ACCEPT'=>'application/json']
        );

        $this->application->run(new StringInput("ojs:analytics:update download"));
        $response = $this->client->getResponse();

        $this->assertNotSame('[]', $response->getContent());

    }
    */
}
 