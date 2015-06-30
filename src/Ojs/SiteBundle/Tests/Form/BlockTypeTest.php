<?php

namespace Ojs\SiteBundle\Tests\Form;

use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\SiteBundle\Form\Type\BlockType;

class BlockTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new BlockType(),
            [
                'title' => $this->faker->text(100),
                'type' => 'html',
                'content' => $this->faker->text(),
                //'object_id' => 1,
                //'object_type' => $this->faker->word,
                'color' => 'default',
            ],
            'Ojs\SiteBundle\Entity\Block'
        );
    }
}
