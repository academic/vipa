<?php

namespace Ojs\SiteBundle\Tests\Form;

use Ojs\CoreBundle\Tests\BaseTypeTestcase;
use Ojs\SiteBundle\Form\Type\BlockLinkType;

class BlockLinkTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new BlockLinkType(),
            [
                //  'block_id' => 1,
                'text' => $this->faker->text(),
                'url' => $this->faker->url,
            ],
            'Ojs\SiteBundle\Entity\BlockLink'
        );
    }
}
