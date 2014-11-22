<?php

namespace Ojs\WikiBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends BaseTestCase
{
    public function testJournalWiki()
    {
        $this->assertTrue($this->isAccessible(['wiki_detail', ['type' => 'journal']]));
    }

    public function testInstitutionWiki()
    {
        $this->assertTrue($this->isAccessible(['wiki_detail', ['type' => 'institution']]));
    }
}
