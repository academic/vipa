<?php
/**
 * Date: 13.01.15
 * Time: 20:38
 */

namespace Ojs\SiteBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\SiteBundle\Form\PageType;

class PageTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        $this->basicSubmitTest(
            new PageType(),
            [
                'title' => $this->faker->text(),
                'body' => $this->faker->paragraphs(10),
                'tags' => $this->faker->text(),
                'image' => ''
            ],
            'Ojs\SiteBundle\Entity\Page');
    }
}
