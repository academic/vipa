<?php
/**
 * Date: 13.01.15
 * Time: 20:40
 */
namespace Ojs\JournalBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\Author;
use Ojs\JournalBundle\Form\ArticleAuthorType;
use Symfony\Component\Form\PreloadedExtension;

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
