<?php

namespace Ojs\OAIBundle\Tests\Controller;

use Ojs\Common\Tests\BaseTestCase;

class DefaultControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->assertTrue($this->isAccessible(['ojs_oai_homepage']));
    }
    public function testIdentify()
    {
        $this->assertTrue($this->isAccessible(['ojs_oai_identify']));
    }
    public function testListRecords()
    {
        $this->assertTrue($this->isAccessible(['ojs_oai_list_records']));
    }

    public function testListSets()
    {
        $this->assertTrue($this->isAccessible(['ojs_oai_list_sets']));
    }

    public function testListMetadataFormats()
    {
        $this->assertTrue($this->isAccessible(['ojs_oai_list_metadata_formats']));
    }
    public function testListIdentifiers()
    {
        $this->assertTrue($this->isAccessible(['ojs_oai_list_identifiers']));
    }
}
