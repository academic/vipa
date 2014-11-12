<?php

namespace Ojs\JournalBundle\Tests\Controller;

use Ojs\Common\Helper\TestHelper;

/**
 * @todo new article, update article, delete article  and show article
 */
class ArticleControllerTest extends TestHelper
{
    public function testStatus()
    {
        $client = $this->client;
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $client->request('GET', '/admin/article/');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $client->request('GET', '/admin/article/new');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}
