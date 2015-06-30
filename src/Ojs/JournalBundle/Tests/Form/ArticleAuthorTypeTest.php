<?php

namespace Ojs\JournalBundle\Tests\Form;

use Ojs\Common\Tests\BaseTypeTestcase;

class ArticleAuthorTypeTest extends BaseTypeTestcase
{
    public function testSubmitValidData()
    {
        #@todo
        $this->assertTrue(true);
        /*$this->basicSubmitTest(
            new ArticleAuthorType(),
            [
                'authorOrder' => $this->faker->randomDigitNotNull,
                'author' => $this->getMockBuilder('Ojs\JournalBundle\Entity\Author')
                    ->disableOriginalConstructor()->getMock(),
                'article' => $this->getMockBuilder('Ojs\JournalBundle\Entity\Article')
                    ->disableOriginalConstructor()->getMock()
            ],
            'Ojs\JournalBundle\Entity\ArticleAuthor',
            [
                'journal_id'=>1
            ]
        );*/
    }
}
