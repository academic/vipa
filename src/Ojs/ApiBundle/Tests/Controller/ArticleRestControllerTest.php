<?php
/**
 * User: aybarscengaver
 * Date: 16.11.14
 * Time: 23:35
 * URI: www.emre.xyz
 * Devs: [
 * 'Aybars Cengaver'=>'aybarscengaver@yahoo.com',
 *   ]
 */

namespace Ojs\ApiBundle\Tests\Controller;


use Ojs\Common\Tests\BaseTestCase;

/**
 * Class ArticleRestControllerTest
 * @package Ojs\ApiBundle\Tests\Controller
 */
class ArticleRestControllerTest extends BaseTestCase
{

    public function testGetArticles()
    {
        $response = $this->apiRequest('/api/articles/bulk/0/10');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetArticle()
    {
        $response = $this->apiRequest('/api/article/1');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetArticleCitations()
    {
        $response = $this->apiRequest('/api/article/1/citations');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostArticleBulkCitation()
    {
        $citations = [
            [
                'raw' => 'Demo',
                'type' => 'test',
                'orderNum' => 0,
                'settings' => [
                    'address' => 'Adres demo',
                    'author' => 'Sanatçının sanatı'
                ]
            ], [
                'raw' => 'Demo',
                'type' => 'test',
                'orderNum' => 1,
                'settings' => [
                    'address' => 'Adres demo',
                    'author' => 'Sanatçının sanatı'
                ]
            ], [
                'raw' => 'Demo',
                'type' => 'test',
                'orderNum' => 2,
                'settings' => [
                    'address' => 'Adres demo',
                    'author' => 'Sanatçının sanatı'
                ]
            ], [
                'raw' => 'Demo',
                'type' => 'test',
                'orderNum' => 3,
                'settings' => [
                    'address' => 'Adres demo',
                    'author' => 'Sanatçının sanatı'
                ]
            ],
        ];
        $response = $this->apiRequest('/api/articles/1/bulkcitations','POST',[
            'cites'=>json_encode($citations)
        ]);
        $this->assertEquals(204,$response->getStatusCode());
    }
    //@todo addCitation, postArticleCitation, postArticleBulkCitation test not here
}
