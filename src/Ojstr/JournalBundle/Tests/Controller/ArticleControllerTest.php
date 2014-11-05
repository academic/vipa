<?php

namespace Ojstr\JournalBundle\Tests\Controller;

use Ojs\Common\Helper\TestHelper;

/**
 * @todo new article, update article, delete article  and show article
 */
class ArticleControllerTest extends TestHelper
{
    public function testStatus()
    {
        $this->logIn('admin', array('ROLE_SUPER_ADMIN'));

        $this->client->request('GET', '/admin/article/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/admin/article/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

}
