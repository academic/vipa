<?php
/**
 * Date: 13.01.15
 * Time: 20:22
 */
namespace Ojs\SiteBundle\Tests\Form;

use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\SiteBundle\Form\BlockLinkType;

class BlockLinkTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new BlockLinkType(),
            [
              //  'block_id' => 1,
                'text' => $this->faker->text(),
                'url' => $this->faker->url
            ],
            'Ojs\SiteBundle\Entity\BlockLink');
    }
}
